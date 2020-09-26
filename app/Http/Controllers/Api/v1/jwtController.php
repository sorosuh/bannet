<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;

//use Tymon\JWTAuth\JWTAuth;

class jwtController extends Controller
{

    public function __construct(Request $request)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    function getPayload()
    {
        return JWTAuth::getPayload();
    }

    function unsetJwt(Request $request)
    {
        $this->validate($request, ['token' => 'required']);
        try {
            JWTAuth::invalidate($request->input('token'));
            return response()->json(['success' => true]);
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'error' => 'Destory faild'], 500);
        }
    }
}
