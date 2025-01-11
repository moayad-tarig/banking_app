<?php

use App\Exceptions\CustomeHandlingException;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'force.json' => ForceJsonResponse::class,
        ]);
        $middleware->group('api', [
            'force.json',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
       
        $handler = new CustomeHandlingException();

        $exceptions->render(function (\Throwable $exception) use ($handler) {
            return $handler->handle($exception);
        });
        
    })->create();
