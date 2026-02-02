<?php

namespace App\Traits;

trait ApiResponser
{
    protected function success($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'status' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function error($message = 'Error', $errors = [], $code = 400)
    {
        return response()->json([
            'status' => false,
            'code' => $code,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}