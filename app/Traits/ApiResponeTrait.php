<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

trait ApiResponeTrait
{

    /**
     * Undocumented function
     *
     * @param array $data
     * @param integer $statusCode
     * @param array $headers
     * @return array
     */
    private function parseGivenData(array $data = [], int $statusCode = 200, array $headers = []): array
    {
        $responseStructure = [
            'success' => $data['success'] ?? false,
            'message' => $data['message'] ?? null,
            'result' => $data['result'] ?? null,
        ];
        // dd($responseStructure);
        if (isset($data['error'])) $responseStructure['error'] = $data['error'];
        if (isset($data['status'])) $statusCode = $data['status'];

        if (isset($data['exception']) && ($data['exception'] instanceof \Exception || $data['exception'] instanceof \Error)) {
            if (config('app.env') != 'production') {
                $responseStructure['exception'] = [
                    'message' => $data['exception']->getMessage(),
                    'file' => $data['exception']->getFile(),
                    'line' => $data['exception']->getLine(),
                    'code' => $data['exception']->getCode(),
                    'trace' => $data['exception']->getTrace()
                ];
            }
            if ($statusCode == 200) $statusCode = 500;
        }
        if ($data['success'] === false) {
            if (isset($data['error_code'])) {
                $responseStructure['error_code'] = $data['error_code'];
            } else {
                $responseStructure['error_code'] = 1;
            }
        }
        return ['content' => $responseStructure, 'statusCode' => $statusCode, 'headers' => $headers];
    }

    public function apiResponse($data = [], int $statusCode = 200, array $headers = [])
    {

        $result = $this->parseGivenData($data, $statusCode, $headers);
        return response()->json($result['content'], $result['statusCode'], $result['headers']);
    }

    public function sendSuccess(mixed $data, string $message)
    {
        return $this->apiResponse([
            'success' => true,
            'result' => $data,
            'message' => $message
        ]);
    }

    public function sendError(string $message, int $statusCode = 404, Exception $exception = null, int $error_code = 1)
    {
        return $this->apiResponse([
            'success' => false,
            'error_code' => $error_code,
            'message' => $message,
            'exception' => $exception
        ], $statusCode);
    }

    public function sendUnAuthorized(string $message)
    {
        return $this->sendError($message);
    }
    public function sendForbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->sendError($message);
    }

    public function sendInternalServerError(string $message = 'Internal Server Error'): JsonResponse
    {
        return $this->sendError($message);
    }
    public function sendValidationErrors(ValidationException $exception): JsonResponse
    {
        return $this->apiResponse([
            'success' => false,
            'message' => $exception->getMessage(),
            'errors' => $exception->errors()
        ]);
    }


}
