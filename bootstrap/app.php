<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
   ->withRouting(
       web: [
           __DIR__ . '/../routes/web.php',
           __DIR__ . '/../routes/webhook.php', // ✅ load webhook routes
       ],
       api: __DIR__ . '/../routes/api.php',
       commands: __DIR__ . '/../routes/console.php',
       health: '/up'
   )
   ->withMiddleware(function (Middleware $middleware): void {
       // Default 'web' middleware group
       $middleware->web(append: [
           \Illuminate\Cookie\Middleware\EncryptCookies::class,
           \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
           \Illuminate\Session\Middleware\StartSession::class,
           \Illuminate\View\Middleware\ShareErrorsFromSession::class,
           \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, // built-in
           \Illuminate\Routing\Middleware\SubstituteBindings::class,
       ]);

       // Default 'api' middleware group
       $middleware->api(append: [
           \Illuminate\Routing\Middleware\SubstituteBindings::class,
       ]);

       // Custom 'admin' middleware group
       $middleware->appendToGroup('admin', [
           \App\Http\Middleware\EnsureTwoFactorEnabled::class,
       ]);

       // Middleware aliases
       $middleware->alias([
           'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
           'role' => RoleMiddleware::class,
           'permission' => PermissionMiddleware::class,
           'role_or_permission' => RoleOrPermissionMiddleware::class,
           'verify.webhook' => \App\Http\Middleware\VerifyWebhookSignature::class, // ✅ only alias
       ]);

       // Middleware execution priority
       $middleware->priority([
           \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
           \Illuminate\Cookie\Middleware\EncryptCookies::class,
           \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
           \Illuminate\Session\Middleware\StartSession::class,
           \Illuminate\View\Middleware\ShareErrorsFromSession::class,
           \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
           \Illuminate\Routing\Middleware\SubstituteBindings::class,
       ]);
   })
   ->withExceptions(function ($exceptions): void {
       // Optional custom exception handling
   })
   ->create();
