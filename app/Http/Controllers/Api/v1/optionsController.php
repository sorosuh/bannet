<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\option;
use JWTAuth;

class optionsController extends Controller
{

    public function __construct()
    {
        if(isset($request->token)){
            $this->user = JWTAuth::parseToken()->authenticate();
        }
    }

    public function create(Request $request)
    {

        $option_Name = $request->name;
        $option_value = $request->value;
        //dd($request->value);

        $option = new option;
        $option->option_name = $option_Name;
        $option->option_value = $option_value;
        $option->save();

    }


    public function edit(Request $request, $id)
    {
        $option = option::findOrFail($id);
        $option->option_name = $request->name;
        $option->option_value = $request->value;
        if ($option->save()) {
            return response()->json([
                'message' => 'option edited'
            ]);
        } else {
            return response()->json([
                'message' => 'error'
            ]);
        }
    }


    public function show($id)
    {
        $option = option::findOrFail($id);
        return response()->json([
            $option
        ]);
    }

}
