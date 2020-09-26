<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

/*    public function accessUrl(Request $request)
    {
        if (!isset($request->header()["app-key"]) || $request->header()["app-key"]["0"] != env('APP_KEY')) {
            return response()->json([
                'message' => 'دسترسی غیرمجاز !',
            ]);
        }
    }*/
}
