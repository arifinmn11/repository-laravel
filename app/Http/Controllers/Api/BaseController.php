<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    const STAT_OK = 'OK';
    const STAT_ERROR = 'ERROR';

    public function successResponse($data = null, $message = 'Success!', $code = Response::HTTP_OK)
    {
        return response()->json([
            'status' => self::STAT_OK,
            'error' => null,
            'message' => $message,
            'code' => $code,
            'data' => $data
        ], $code);
    }

    public function errorResponse($error = null, $message = null, $code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'status' => self::STAT_ERROR,
            'error' => $error,
            'message' => $message,
            'code' => $code,
            'data' => null
        ], $code);
    }
}
