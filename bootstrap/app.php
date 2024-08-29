<?php

use App\Http\Middleware\EnsureEmailIsVerified;
use App\Models\Article;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function() {
            Route::bind('article', function($value) {
                return Article::whereId($value)->orWhere('slug', $value)->firstOrFail();
            });
        })->withMiddleware(function(Middleware $middleware) {
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => EnsureEmailIsVerified::class,
        ]);

        //
    })->withExceptions(function(Exceptions $exceptions) {
        $exceptions->renderable(function(NotFoundHttpException $exception, $request) {
            if($request->is('api/*')) {
                return response()->json([
                    'error' => [
                        'message' => 'Resource not found',
                        'type' => 'NotFoundHttpException',
                        'code' => 404,
                        'link' => 'example.com/link',
                        'status_code' => (string)$exception->getStatusCode()
                    ]
                ], 404);
            }
        });
    })->create();
