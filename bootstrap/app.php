<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'ensure.api.token' => \App\Http\Middleware\EnsureApiTokenIsValid::class,
            'auth.sanctum' => \Illuminate\Auth\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
        if ($request->expectsJson() || str_starts_with($request->path(), 'api/')) {

            // Invalid or missing token
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'message' => 'Unauthenticated or invalid token.'
                ], 401);
            }

            // Authorization error
            if ($e instanceof AuthorizationException) {
                return response()->json([
                    'message' => 'Forbidden.'
                ], 403);
            }

            // Validation errors
            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $e->errors()
                ], 422);
            }

            // Not found
            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'message' => 'Not Found.'
                ], 404);
            }

            // Default fallback
            $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

            return response()->json([
                'message' => $e->getMessage() ?: 'Server Error'
            ], $status);
        }

        return null; // fallback to Laravel default for non-API routes
    });

    })->create();
