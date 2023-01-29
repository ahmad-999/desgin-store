<?php

namespace  App\Traits;

trait MyResponse
{

    public static function returnData($key, $value, $msg = "", $code = 200,$errors = [])
    {
        return response()->json([
            'code' => $code,
            "msg" => $msg,
            $key => $value,
            "errors" =>$errors
        ], 200);
    }
    public static function returnError($msg, $code)
    {
        return response()->json([
            'code' => $code,
            "errors" => $msg,
        ], 200);
    }
    public static function returnMessage($msg, $code=200)
    {
        return response()->json([
            'code' => $code,
            "msg" => $msg,
        ], 200);
    }
}
