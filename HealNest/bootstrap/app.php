<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$viewCompiledPath = dirname(__DIR__) . '/.cache/views';
putenv("VIEW_COMPILED_PATH={$viewCompiledPath}");
$_ENV['VIEW_COMPILED_PATH'] = $viewCompiledPath;
$_SERVER['VIEW_COMPILED_PATH'] = $viewCompiledPath;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['role' => \App\Http\Middleware\RoleMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

return $app;
