<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait HandleApi
{

    public function sendResponse($result, $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response);
    }


    public function sendError($error, $message): JsonResponse
    {
        $response = [
            'success' => false,
            'error' => $error,
            'message' => $message,
        ];
        return response()->json($response);
    }
}
