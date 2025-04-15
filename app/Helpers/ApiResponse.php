<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = [], $message = 'Request complete', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function error($message = 'Something went wrong', $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }

    public static function validationError($errors, $message = 'Validation failed', $code = 422)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
