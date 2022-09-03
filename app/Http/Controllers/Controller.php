<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($result, $message, $code = 200)
    {
        $result = ($result ? ($result == "[]" ? [] : $result) : json_decode("{}"));
        return response ([
            'status' => TRUE,
            'data' => $result,
            'message' => $message
        ], $code);
    }

    public function sendError($result, $message, $code = 400)
    {
        $result = ($result ? ($result == "[]" ? [] : $result) : json_decode("{}"));
        return response ([
            'status' => FALSE,
            'data' => $result,
            'message' => $message
        ], $code);
    }

}
