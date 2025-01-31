<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class BaseResponse implements Responsable
{
    protected int $httpCode;
    protected $data;
    protected string $errorMessage;

    public function __construct(int $httpCode, $data = null, string $errorMessage = '')
    {

        if (! (($httpCode >= 200 && $httpCode <= 300) || ($httpCode >= 400 && $httpCode <= 600))) {
            throw new \RuntimeException($httpCode . ' is not valid');
        }

        $this->httpCode = $httpCode;
        $this->data = $data;
        $this->errorMessage = $errorMessage;
    }

    public function toResponse($request): \Illuminate\Http\JsonResponse
    {
        $payload = match (true) {
            $this->httpCode >= 500 => ['status' => 'error', 'data' => $this->data, 'error_message' => 'Server error'],
            $this->httpCode >= 400 => ['status' => 'error', 'data' => $this->data, 'error_message' => $this->errorMessage],
            $this->httpCode >= 200 => ['status' => 'ok', 'data' => $this->data, 'error_message' => null],
        };

        return response()->json(
            data: $payload,
            status: $this->httpCode,
            options: JSON_UNESCAPED_UNICODE
        );
    }

    public static function ok($data)
    {
        return new static(200, $data);
    }

    public static function created($data)
    {
        return new static(201, $data);
    }

    public static function notFound(string $errorMessage = "Item not found")
    {
        return new static(404, errorMessage: $errorMessage);
    }
}
