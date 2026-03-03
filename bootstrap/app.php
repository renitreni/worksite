<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware as SpatieRoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__ . '/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

    $middleware->redirectGuestsTo(function ($request) {

        // 🚀 VERY IMPORTANT: Do NOT redirect broadcasting auth
        if ($request->is('broadcasting/*')) {
            return null;
        }

        if ($request->is('admin/*')) {
            return route('admin.login');
        }

        $middleware->alias([
            // ✅ Your custom aliases (keep)
            'role'   => \App\Http\Middleware\RoleMiddleware::class,
            'active' => \App\Http\Middleware\EnsureAccountIsActive::class,

            // ✅ Spatie permission aliases (add)
            'permission' => PermissionMiddleware::class,

            // Avoid conflict with your 'role' alias:
            'spatie.role' => SpatieRoleMiddleware::class,

            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withBroadcasting(
        __DIR__ . '/../routes/channels.php',
        attributes: ['middleware' => ['web', 'auth']]

    )->create();
