<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Response;
use mysql_xdevapi\Session;
use JWTAuth;

class sessionsController extends Controller
{
//    public function __construct()
//    {
//        $this->user = JWTAuth::parseToken()->authenticate();
//    }


    public function sessionController(Request $request)
    {

        $response = null;
        $function = ($request->function) ? $request->function : null;
        $key = ($request->key) ? $request->key : null;
        $value = ($request->value) ? $request->value : null;
        $session_name = ($request->session_name) ? $request->session_name : null;


        switch ($function) {
            case 'setSession':
                $response = $this->setSession($key, $value);
                break;
            case 'getSession' :
                $response = $this->getSession($key);
                break;
            case 'getAllSession':
                $response = $this->getAllSession();
                break;
            case 'flash':
                $response = $this->flash($key, $value);
                break;
            case 'flush' :
                $response = $this->flush();
                break;
            case 'has' :
                $response = $this->has($key);
                break;
            case 'forgetSession':
                $response = $this->forgetSession($key);
                break;
            case 'forget_value_of_session':
                $response = $this->forget_value_of_session($session_name, $value);
                break;
            default :
                "function doesn't exists ";
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return string
     * @debug true
     * @author soroush bagherian
     */
    protected function setSession($key, $value)
    {
        //like this   "product"=>["item"=>"bag" , "id"=>45 , "color"=>"red"]
        session([$key => $value]);
        return response()->json([
            'message' => 'session set',
            'sessions' => session()->all()
        ]);
    }


    /**
     * @param $key
     * @return JsonResponse
     * @debug false
     * @author soroush bagherian
     */
    protected function getSession($key)
    {
        return response()->json([
            session()->get($key, 'not exists')
        ]);
    }


    /**
     * @param
     * @return JsonResponse
     * @debug true
     * @author soroush bagherian
     */
    protected function getAllSession()
    {
        return response()->json([
            session()->all()
        ]);
    }


    /**
     * @param $key
     * @param $value
     * @return String message
     * @debug true
     * @author soroush bagherian
     */
    protected function flash($key, $value)
    {
        session()->flash($key, array($value));
        return response()->json([
            'message' => 'set session flash'
        ]);
    }


    /**
     * @param
     * @return String message
     * @debug true
     * @author soroush bagherian
     */
    protected function flush()
    {
        session()->flush();
        return response()->json([
            'message' => 'all sessions deleted'
        ]);
    }

    /**
     * @param String $key
     * @return JsonResponse
     * @debug true
     * @author soroush bagherian
     */
    protected function has($key)
    {
        return response()->json([
            session()->has($key)
        ]);
    }

    protected function forgetSession($key)
    {
        session()->forget($key);
        return response()->json([
            'message' => $key . ' session deleted .'
        ]);
    }

    protected function forget_value_of_session($session_name, $value)
    {
        session()->forget($session_name . "." . $value);
        return response()->json([
            'message' => 'value deleted from session .'
        ]);
    }

}
