<?php

use App\Exceptions\CustomeHandlingException;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\HasSetPinMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;

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
            'has.set.pin' => HasSetPinMiddleware::class,
        ]);
      
        $middleware->group('api', [
            'force.json',
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {

        
       
        $handler = new CustomeHandlingException();

        $exceptions->render(function (\Throwable $exception , Request $request) use ($handler) {
            if(Str::contains($request->path(), 'api') || $request->expectsJson()){
                
                return $handler->handle($exception);
            }
        });
        
    })->create();
