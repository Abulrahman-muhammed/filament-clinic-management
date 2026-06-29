<?php
namespace App\Helpers;

class ApiResponse
{
    static function sendResponse($code = 200, $message = '', $data = []) {
        return response()->json(
            [
                'status'  => $code,
                'message' => $message,
                'data'    => $data
            ],
            $code
        );
    }
}