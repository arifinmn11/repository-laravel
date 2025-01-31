<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponse
{

    public function successResponse($data = null, $code = Response::HTTP_OK, $message = null)
    {
        return response()->json([
            'error' => null,
            'message' => $message,
            'code' => $code,
            'data' => $data
        ], $code);
    }

    public function errorResponse($data = null, $code = Response::HTTP_BAD_REQUEST, $error = null, $message = null)
    {
        return response()->json([
            'error' => $error,
            'message' => $message,
            'code' => $code,
            'data' => $data
        ], $code);
    }
}
