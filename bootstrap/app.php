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

        /*
        |--------------------------------------------------------------------------
        | Middleware Aliases
        |--------------------------------------------------------------------------
        */
        $middleware->alias([
            // Your custom middleware
            'role'   => \App\Http\Middleware\RoleMiddleware::class,
            'active' => \App\Http\Middleware\EnsureAccountIsActive::class,

            // Spatie permission middleware
            'permission' => PermissionMiddleware::class,

            // avoid conflict with your custom role middleware
            'spatie.role' => SpatieRoleMiddleware::class,

            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Guest Redirects
        |--------------------------------------------------------------------------
        */
        $middleware->redirectGuestsTo(function ($request) {

            // Do NOT redirect broadcasting auth routes
            if ($request->is('broadcasting/*')) {
                return null;
            }

            // Admin routes go to admin login
            if ($request->is('admin/*')) {
                return route('admin.login');
            }

            // Default redirect
            return route('login');
        });
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->withBroadcasting(
        __DIR__ . '/../routes/channels.php',
        attributes: ['middleware' => ['web', 'auth']]
    )

    ->create();