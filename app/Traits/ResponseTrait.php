<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ResponseTrait
{
    private function successResponse($data, int $code): JsonResponse
    {
        return response()->json(['data' => $data], $code);
    }

    protected function errorResponse($message, int $code = Response::HTTP_BAD_REQUEST, string $details = null): JsonResponse
    {
        return response()->json($this->generateErrorResponse($message, $details), $code);
    }


    protected function showAll(Collection $collection, int $code = Response::HTTP_OK): JsonResponse
    {
        return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $instance, int $code = Response::HTTP_OK): JsonResponse
    {
        return $this->successResponse($instance, $code);
    }

    protected function showMessage(string $message, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['message' => $message, 'code' => $code]);
    }

    protected function successMessage(): JsonResponse
    {
        return response()->json(['message' => 'ok'], Response::HTTP_OK);
    }

    /**
     * Generate a standardized error response.
     *
     * @param string $message The error message to include in the response.
     * @param string|null $details Additional details or hints for the error.
     * @return array An array containing the status, message, and optional details for the error.
     */
    private function generateErrorResponse(string $message, string $details = null)
    {
        $errorResponse = [
            'status' => 'error',
            'message' => $message
        ];

        if ($details) {
            $errorResponse['details'] = $details;
        }

        return $errorResponse;
    }

    
    /**
     * Generate a standardized success response.
     *
     * @param string $message The success message to include in the response.
     * @return array An array containing the type, message, and HTTP status for the success.
     */
    private function generateSuccessResponse(string $message)
    {
        // Return the success response array with the provided message and an OK status
        return [
            'type' => 'success',
            'message' => $message,
            'status' => Response::HTTP_OK
        ];
    }
}