<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__ . '/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->redirectGuestsTo(function ($request) {
                
        if ($request->is('broadcasting/*')) {
                return null;
            }

            if ($request->is('admin/*')) {
                return route('admin.login');
            }

            if ($request->is('employer/*')) {
                return route('employer.login');
            }

            // Default login page
            return route('home');
        });

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'active' => \App\Http\Middleware\EnsureAccountIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->withBroadcasting(
        __DIR__ . '/../routes/channels.php',
        attributes: ['middleware' => ['web', 'auth:web,admin']]

    )->create();