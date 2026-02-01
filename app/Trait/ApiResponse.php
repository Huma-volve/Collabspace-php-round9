<?php

namespace App\Trait;

trait ApiResponse
{

     public function success(string $message, $data = null, int $status = 200)
    {
        $response = [
            'message' => $message,
            'status'  => $status,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    public function error(string $message, int $status = 404)
    {
        return response()->json([
            'success'=>True,
            'message' => $message,
            'status'  => $status,
        ], $status);
    }
}
