<?php

namespace App\Http\Traits;


trait HttpResponse {

    function success($data, $msg, $code) {
        
        return response()->json([
            'data' => $data,
            'message' => $msg, 
            'code' => $code
        ]);
    }
    
    function error($msg, $code) {
        
        return response()->json([
            'message' => $msg, 
            'code' => $code
        ]);
    }
}