<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function clearSession(){
        if(!isset($_SESSION)) session_start();
        $_SESSION['token'] = null;
        $_SESSION['text'] = null;
    }
    /******************************************************************************************************************
     *                                               response to json
     * @param $obj
     * @return mixed
     */
    protected function response_json($obj)
    {
        return response(json_encode($obj), 200)->header('Content-Type', 'application/json');
    }

}
