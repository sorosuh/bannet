<?php

namespace App\Http\Middleware;

use Closure;
use http\Env\Request;

class APP_KEY
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!isset($request->header()["app-key"]) || $request->header()["app-key"]["0"] != env('APP_KEY')) {
            return response()->json([
                'message' => 'دسترسی غیرمجاز !',
            ]);
        }else{
            return $next($request);
        }

    }
}
