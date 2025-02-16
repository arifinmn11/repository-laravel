<?php

namespace App\Exceptions;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;

use Exception;

class Handler extends Exception
{
    public function render($request, Throwable $exception)
    {
        // Handle "Wrong Path" errors
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'The requested endpoint does not exist.',
            ], 404);
        }
    }
}
