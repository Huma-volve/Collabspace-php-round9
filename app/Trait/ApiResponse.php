<?php

namespace App\Trait;

trait ApiResponse
{
    public function success($msg){
        return response()->json([
            'msg'=>$msg,
            'status'=>200
        ]);
    }
    public function create($msg){
        return response()->json([
            'msg'=>$msg,
            'status'=>201
        ]);
    }
    public function error($msg){
        return response()->json([
            'msg'=>$msg,
            'status'=>400
        ]);
    }
    public function returndata($msg,$data){
        return response()->json([
            'msg'=>$msg,
            'data'=>$data,
        ]);
    }
}
