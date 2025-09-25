<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware; // 👈 Add this
use App\Http\Middleware\JwtMiddleware; // 👈 Add this
use App\Http\Middleware\OptionalJwtAuth; // 👈 Add this

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'jwt' => JwtMiddleware::class,
            'jwt.optional' => OptionalJwtAuth::class, // ✅ Add this alias
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();