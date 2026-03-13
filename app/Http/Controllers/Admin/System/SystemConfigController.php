<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminSystemConfigService;
use App\Http\Requests\Admin\System\UpdateGeneralSettingsRequest;
use App\Http\Requests\Admin\System\UpdateNotificationSettingsRequest;
use App\Http\Requests\Admin\System\UpdateSecuritySettingsRequest;

class SystemConfigController extends Controller
{
    public function __construct(
        private AdminSystemConfigService $configService
    ) {
        $this->middleware(function ($request, $next) {

            if (auth('admin')->user()->role !== 'superadmin') {
                abort(403);
            }

            return $next($request);

        });
    }

    public function index()
    {
        $data = $this->configService->getSystemSettings();

        return view('adminpage.system.index', $data);
    }

    public function updateGeneral(UpdateGeneralSettingsRequest $request)
    {
        $this->configService->updateGeneral($request->validated());

        return back()->with('success', 'General settings updated.');
    }

    public function updateNotifications(UpdateNotificationSettingsRequest $request)
    {
        $this->configService->updateNotifications($request->validated());

        return back()->with('success', 'Notification settings updated.');
    }

    public function updateSecurity(UpdateSecuritySettingsRequest $request)
    {
        $this->configService->updateSecurity($request->validated());

        return back()->with('success', 'Security settings updated.');
    }
}