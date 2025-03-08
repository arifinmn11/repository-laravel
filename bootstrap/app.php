<?php

use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // 'throttle:api',
        ]);

        // $middleware->alias([
        //     'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        // ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]); // until thiss
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->json())
                return response()->json([
                    'status' => 'error',
                    'error' => null,
                    'message' => 'Not Found',
                    'code' => 404,
                    'data' => null
                ], 404);

            throw $e;
        });

        $exceptions->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->json())
                return response()->json([
                    'status' => 'error',
                    'error' => null,
                    'message' => 'Method not allowed',
                    'code' => 405,
                    'data' => null
                ], 405);

            throw $e;
        });

        // $exceptions->renderable(function (Exception $e, $request) {
        //     if ($request->json())
        //         return response()->json([
        //             'status' => 'error',
        //             'error' => null,
        //             'message' => 'Internal Server Error',
        //             'code' => 500,
        //             'data' => null
        //         ], 500);

        //     throw $e;
        // });
    })->create();
