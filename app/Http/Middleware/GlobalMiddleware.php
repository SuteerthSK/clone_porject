<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\TrustProxies;

class GlobalMiddleware
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run on every request to your application.
     *
     * @var array
     */
    public static $middleware = [
        TrustProxies::class,
        PreventRequestsDuringMaintenance::class,
        ValidatePostSize::class,
        TrimStrings::class,
        'web' => [
            // Your web middleware here, e.g., for global web-only tasks
        ],
        'api' => [
            // Your API middleware here, e.g., for global API-only tasks
        ],
    ];
}