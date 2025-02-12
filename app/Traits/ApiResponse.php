<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponse
{

    public function successResponse($data = null, $message = null, $code = Response::HTTP_OK,)
    {
        return response()->json([
            'status' => 'ok',
            'error' => null,
            'message' => $message,
            'code' => $code,
            'data' => $data
        ], $code);
    }

    public function errorResponse($error = null, $message = null, $code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'status' => 'error',
            'error' => $error,
            'message' => $message,
            'code' => $code,
            'data' => null
        ], $code);
    }
}
