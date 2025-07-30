<?php

use App\Http\Middleware\AdminAuthenticated;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'admin.auth' => AdminAuthenticated::class,
            'locale' => SetLocale::class,
        ]);

        // Apply SetLocale middleware globally to web routes
        $middleware->web(append: [
            SetLocale::class,
        ]);

        // Or apply it to specific route groups
        // $middleware->group('admin', [
        //     SetLocale::class,
        //     AdminAuthenticated::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
