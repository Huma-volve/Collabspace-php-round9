<?php

namespace App;

trait ApiResponse
{
     public function success($data = [], $message, $status = 200)
    {
        return response()->json([
            'success' => true,
            'code' => $status,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
