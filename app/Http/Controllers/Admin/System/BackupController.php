<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\RestoreBackupRequest;
use App\Models\BackupRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use ZipArchive;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->middleware(function ($request, $next) {

            $user = auth('admin')->user();

            // Superadmin only access
            if ($user->role !== 'superadmin') {
                abort(403);
            }

            return $next($request);
        })->only(['index', 'run', 'restore']);
    }

    public function index()
    {
        $runs = BackupRun::query()->latest()->paginate(15);

        // for restore dropdown
        $restoreCandidates = BackupRun::query()
            ->where('status', 'success')
            ->whereNotNull('file_path')
            ->latest()
            ->limit(50)
            ->get();

        return view('adminpage.backups.index', compact('runs', 'restoreCandidates'));
    }

    public function run(Request $request)
    {
        $run = BackupRun::create([
            'type' => 'database',
            'status' => 'running',
            'started_at' => now(),
            'requested_by' => $request->user('admin')->id,
        ]);

        try {
            Artisan::call('backup:run', ['--only-db' => true]);

            // Try to capture newest backup zip path (local disk)
            $filePath = $this->detectLatestBackupZipPath();

            $run->update([
                'status' => 'success',
                'finished_at' => now(),
                'log' => Artisan::output(),
                'file_path' => $filePath, // may be null if detection fails
            ]);

            return back()->with('success', 'Backup completed.');
        } catch (\Throwable $e) {
            $run->update([
                'status' => 'failed',
                'finished_at' => now(),
                'log' => $e->getMessage(),
            ]);

            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * REAL RESTORE:
     * - Extract the backup zip
     * - Find the .sql inside
     * - Import using mysql client
     */
    public function restore(RestoreBackupRequest $request)
    {
        $run = BackupRun::query()->findOrFail($request->validated()['backup_run_id']);

        if ($run->status !== 'success' || !$run->file_path) {
            return back()->with('error', 'Selected backup is not restorable (missing file_path or not successful).');
        }

        $disk = Storage::disk('local');

        if (!$disk->exists($run->file_path)) {
            return back()->with('error', 'Backup file not found on disk: ' . $run->file_path);
        }

        // Create a "restore run" log entry (optional, but helpful)
        $restoreRun = BackupRun::create([
            'type' => 'restore',
            'status' => 'running',
            'started_at' => now(),
            'requested_by' => $request->user('admin')->id,
            'file_path' => $run->file_path,
            'log' => 'Restore started from backup_run_id=' . $run->id,
        ]);

        $tempDir = storage_path('app/backup-restore-temp/' . $restoreRun->id);
        if (!is_dir($tempDir))
            mkdir($tempDir, 0777, true);

        try {
            // 1) Copy zip to temp
            $zipLocalPath = $tempDir . '/backup.zip';
            file_put_contents($zipLocalPath, $disk->get($run->file_path));

            // 2) Extract zip
            $zip = new ZipArchive();
            if ($zip->open($zipLocalPath) !== true) {
                throw new \RuntimeException('Failed to open backup zip.');
            }
            $zip->extractTo($tempDir);
            $zip->close();

            // 3) Find SQL file inside extracted contents
            $sqlPath = $this->findSqlFile($tempDir);
            if (!$sqlPath) {
                throw new \RuntimeException('No .sql file found inside backup zip.');
            }

            // 4) Import SQL into MySQL
            $this->importSqlWithMysqlClient($sqlPath);

            $restoreRun->update([
                'status' => 'success',
                'finished_at' => now(),
                'log' => $restoreRun->log . "\nRestore completed successfully.\nSQL: " . $sqlPath,
            ]);

            return back()->with('success', 'Database restored successfully.');
        } catch (\Throwable $e) {
            $restoreRun->update([
                'status' => 'failed',
                'finished_at' => now(),
                'log' => $restoreRun->log . "\nRestore failed: " . $e->getMessage(),
            ]);

            return back()->with('error', 'Restore failed: ' . $e->getMessage());
        } finally {
            // Optional cleanup (keep for debugging if you want)
            // $this->deleteDirectory($tempDir);
        }
    }

    private function detectLatestBackupZipPath(): ?string
    {
        // Spatie default: storage/app/Laravel/<app-name>/*.zip
        $base = 'Laravel/' . config('backup.backup.name');
        $disk = Storage::disk('local');

        if (!$disk->exists($base))
            return null;

        $files = collect($disk->allFiles($base))
            ->filter(fn($p) => str_ends_with(strtolower($p), '.zip'))
            ->values();

        if ($files->isEmpty())
            return null;

        // pick newest by lastModified
        $newest = $files->sortByDesc(fn($p) => $disk->lastModified($p))->first();

        return $newest;
    }

    private function findSqlFile(string $dir): ?string
    {
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($rii as $file) {
            if ($file->isDir())
                continue;
            $path = $file->getPathname();
            if (str_ends_with(strtolower($path), '.sql')) {
                return $path;
            }
        }
        return null;
    }

    private function importSqlWithMysqlClient(string $sqlPath): void
    {
        $mysqlBin = env('MYSQL_CLIENT_BIN', 'mysql');

        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port', 3306);
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // Safety: prevent importing into wrong DB
        if (!$dbName) {
            throw new \RuntimeException('DB name is missing.');
        }

        // Build command:
        // mysql -h HOST -P PORT -u USER -pPASS DB < file.sql
        // Process can't use "<", so we pipe content to stdin.
        $process = new Process([
            $mysqlBin,
            '-h',
            (string) $dbHost,
            '-P',
            (string) $dbPort,
            '-u',
            (string) $dbUser,
            '-p' . (string) $dbPass,
            (string) $dbName,
        ]);

        $process->setTimeout(600);

        $sql = file_get_contents($sqlPath);
        if ($sql === false) {
            throw new \RuntimeException('Failed to read SQL file.');
        }

        $process->setInput($sql);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                "MySQL import failed.\n" .
                "Output: " . $process->getOutput() . "\n" .
                "Error: " . $process->getErrorOutput()
            );
        }
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir))
            return;

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }

        rmdir($dir);
    }
}