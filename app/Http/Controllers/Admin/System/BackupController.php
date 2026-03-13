<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\RestoreBackupRequest;
use App\Models\BackupRun;
use Illuminate\Http\Request;
use App\Services\Admin\BackupService;

class BackupController extends Controller
{
    public function __construct(
        private BackupService $backupService
    ) {
        $this->middleware('auth:admin');

        $this->middleware(function ($request, $next) {

            $user = auth('admin')->user();

            if ($user->role !== 'superadmin') {
                abort(403);
            }

            return $next($request);

        });
    }

    public function index()
    {
        $data = $this->backupService->getBackups();

        return view('adminpage.backups.index', $data);
    }

    public function run(Request $request)
    {
        $this->backupService->runBackup($request->user('admin')->id);

        return back()->with('success', 'Backup completed.');
    }

    public function restore(RestoreBackupRequest $request)
    {
        $this->backupService->restoreBackup(
            $request->validated()['backup_run_id'],
            $request->user('admin')->id
        );

        return back()->with('success', 'Database restored successfully.');
    }
}