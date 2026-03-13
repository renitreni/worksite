<?php

namespace App\Services\Admin;

use App\Models\BackupRun;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Symfony\Component\Process\Process;

class BackupService
{
    public function getBackups(): array
    {
        $runs = BackupRun::latest()->paginate(15);

        $restoreCandidates = BackupRun::query()
            ->where('status','success')
            ->whereNotNull('file_path')
            ->latest()
            ->limit(50)
            ->get();

        return compact('runs','restoreCandidates');
    }

    public function runBackup(int $adminId): void
    {
        $run = BackupRun::create([
            'type'=>'database',
            'status'=>'running',
            'started_at'=>now(),
            'requested_by'=>$adminId
        ]);

        try {

            Artisan::call('backup:run',['--only-db'=>true]);

            $filePath = $this->detectLatestBackupZipPath();

            $run->update([
                'status'=>'success',
                'finished_at'=>now(),
                'log'=>Artisan::output(),
                'file_path'=>$filePath
            ]);

        } catch (\Throwable $e) {

            $run->update([
                'status'=>'failed',
                'finished_at'=>now(),
                'log'=>$e->getMessage()
            ]);

            throw $e;
        }
    }

    public function restoreBackup(int $backupRunId, int $adminId): void
    {
        $run = BackupRun::findOrFail($backupRunId);

        $disk = Storage::disk('local');

        if (!$disk->exists($run->file_path)) {
            throw new \RuntimeException('Backup file not found.');
        }

        $restoreRun = BackupRun::create([
            'type'=>'restore',
            'status'=>'running',
            'started_at'=>now(),
            'requested_by'=>$adminId,
            'file_path'=>$run->file_path
        ]);

        $tempDir = storage_path('app/backup-restore-temp/'.$restoreRun->id);

        if(!is_dir($tempDir)){
            mkdir($tempDir,0777,true);
        }

        $zipPath = $tempDir.'/backup.zip';

        file_put_contents($zipPath,$disk->get($run->file_path));

        $zip = new ZipArchive();

        if($zip->open($zipPath)!==true){
            throw new \RuntimeException('Failed to open backup zip.');
        }

        $zip->extractTo($tempDir);
        $zip->close();

        $sqlPath = $this->findSqlFile($tempDir);

        $this->importSql($sqlPath);

        $restoreRun->update([
            'status'=>'success',
            'finished_at'=>now()
        ]);
    }

    private function detectLatestBackupZipPath(): ?string
    {
        $base = 'Laravel/'.config('backup.backup.name');

        $disk = Storage::disk('local');

        if(!$disk->exists($base)){
            return null;
        }

        $files = collect($disk->allFiles($base))
            ->filter(fn($p)=>str_ends_with(strtolower($p),'.zip'));

        return $files
            ->sortByDesc(fn($p)=>$disk->lastModified($p))
            ->first();
    }

    private function findSqlFile(string $dir): ?string
    {
        $rii = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir)
        );

        foreach($rii as $file){

            if($file->isDir()) continue;

            $path = $file->getPathname();

            if(str_ends_with(strtolower($path),'.sql')){
                return $path;
            }

        }

        return null;
    }

    private function importSql(string $sqlPath): void
    {
        $process = new Process([
            'mysql',
            '-h',config('database.connections.mysql.host'),
            '-u',config('database.connections.mysql.username'),
            '-p'.config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
        ]);

        $process->setInput(file_get_contents($sqlPath));

        $process->run();

        if(!$process->isSuccessful()){
            throw new \RuntimeException('MySQL import failed.');
        }
    }
}