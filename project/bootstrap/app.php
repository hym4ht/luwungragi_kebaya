<?php

use App\Http\Middleware\EnsureJwtAuthenticated;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\HydrateJwtUser;
use App\Http\Middleware\RedirectIfJwtAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            HydrateJwtUser::class,
        ]);

        $middleware->alias([
            'jwt.auth' => EnsureJwtAuthenticated::class,
            'jwt.guest' => RedirectIfJwtAuthenticated::class,
            'role' => EnsureUserHasRole::class,
        ]);

        // Exclude Midtrans webhook from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'payment/webhook/midtrans',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
