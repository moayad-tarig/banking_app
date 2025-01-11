<?php

namespace App\Exceptions;

use App\Traits\ApiResponeTrait;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomeHandlingException extends Exception
{
    use ApiResponeTrait;
   

    public function handle(Exception $e) 
    {
        Log::error($e);
        if ($e instanceof AuthenticationException) {
            $statusCode = Response::HTTP_UNAUTHORIZED;
            return $this->apiResponse([
                'message' => "Unauthenticated or Token expired, please try to login again",
                'success' => false,
                'exception' => $e,
                'error_code' => $statusCode,
            ], $statusCode);
        }
        if ($e instanceof NotFoundHttpException) {
            return $this->apiResponse([
                // 'message' => $e->getMessage(),
                'message' => 'No Data Found ',
                'success' => false,
                'exception' => $e,
                'error_code' => $e->getStatusCode(),
            ], $e->getStatusCode());
        }
        if ($e instanceof ValidationException) {
            $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
            return $this->apiResponse([
                'message' => "Validation failed",
                'success' => false,
                'exception' => $e,
                'error_code' => $statusCode,
                'errors' => $e->errors(),
            ], $statusCode);
        }
        if ($e instanceof ModelNotFoundException) {
            $statusCode = Response::HTTP_NOT_FOUND;
            return $this->apiResponse([
                'message' => "Resource could not be found",
                'success' => false,
                'exception' => $e,
                'error_code' => $statusCode,
            ], $statusCode);
        }

        if ($e instanceof UniqueConstraintViolationException) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->apiResponse([
                'message' => "Duplicate entry found",
                'success' => false,
                'exception' => $e,
                'error_code' => $statusCode,
            ]);
        }
        if ($e instanceof QueryException) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->apiResponse([
                'message' => "Could not execute query",
                'success' => false,
                'exception' => $e,
                'error_code' => $statusCode,
            ]);
        }
        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->apiResponse([
                'message' => $e->getMessage(),
                'success' => false,
                'exception' => $e,
                'error_code' => Response::HTTP_METHOD_NOT_ALLOWED,
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        }
     
        if ($e instanceof \Exception) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->apiResponse([
                'message' => "We could not handle your request, please try again later",
                'success' => false,
                'exception' => $e,
                'error_code' => $statusCode,
            ]);
        }

        if ($e instanceof \Error) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->apiResponse([
                'message' => "We could not handle your request, please try again later",
                'success' => false,
                'exception' => $e,
                'error_code' => $statusCode,
            ]);
        }
    }
}
