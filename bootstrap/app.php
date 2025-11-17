<?php

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
    ->withMiddleware(function (Middleware $middleware): void {
        // CSRF Protection is ENABLED for all routes
        // Cart routes properly send CSRF tokens via JavaScript
        // Only exclude read-only product search API
        $middleware->validateCsrfTokens(except: [
            'api/products/search',  // Read-only search endpoint
            'api/products',         // Read-only product list
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
