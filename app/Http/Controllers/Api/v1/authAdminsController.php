<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\adminRegisterRequest;
use App\Sms;
use App\SmsLogs;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\support\str;
use Illuminate\Validation\ValidationException;
use mysql_xdevapi\Exception;
use PragmaRX\Google2FA\Google2FA;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;


class authAdminsController extends Controller
{

    public function otp(Request $request)
    {
        $phone = $request->phone;
        $otpCode = rand(10000000, 99999999);
        return $otpCode;
    }

    /**
     * @param adminRegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @debug true
     * @author soroush bagherian
     */
    public function register(adminRegisterRequest $request)
    {

        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'grid' => (isset($request->grid)) ? $request->grid : 0,
        ]);


        return response()->json([
            'message' => 'success',
            'userId' => $user->id,
            'username' => $user->username,
            'jwt' => JWTAuth::attempt(['username' => $request->username, 'password' => $request->password])
        ]);
    }

    /**
     * @example after login user , users's file path dunamicly change
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @debug true
     * @author soroush bagherian
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $token = null;
        $google2fa = new Google2FA();
        $valid = $this->validate($request, [
            'username' => 'required | exists:users',
            'password' => 'required',
        ]);


        if (!$token = JWTAuth::attempt($valid)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid input',
            ], 401);
        }

        $admin = auth()->user();

//        foreach ($admin->media as $media) {
//            $media->folder->name = hash('md5', $folder = $media->folder->name);
//            $media->folder->save();
//        }

        if ($admin->google2fa_secret) {
            return response()->json([
                'admin' => $admin,
                'message' => 'call url "completeLogin_with_google2fa" with "admin" argument.'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'userId' => $admin->id,
                'username' => $admin->username,
                'token' => $token,
            ], 200);
        }
    }

    public function adminLogin(Request $request)
    {
        //dd($request->all());
        $token = null;
        try {
            $valid = $this->validate($request, [
                'username' => 'required | exists:users',
                'password' => 'required',
            ]);
        } catch (ValidationException $e) {
            echo $e->errorBag;
            Session::flash('errorInLogin', 'نام کاربری یا رمزورود اشتباه است.');
            return redirect()->intended('/panel');
        }

        $is_Admin = DB::table('users')
            ->where('username',$request->username)
            ->select('grid')
            ->get();

        if ($is_Admin[0]->grid== 1){
            if (Auth::attempt(['username' => $request->username , 'password' => $request->password]))
            {
                $admin = auth()->user();
                Session::flash('welcomeAdmin', 'به پنل مدیریت خوش امدید.');
                return redirect()->intended('/panel/main');
            }else{
                Session::flash('errorInLogin', 'نام کاربری یا رمزعبور اشتباه است.');
                return redirect()->intended('/panel');
            }

        }else{
           echo"شما دسترسی به این صفحه را ندارید.";
        }

    }

    public function completeLogin_with_google2fa(Request $request, $admin)
    {
        $google2fa = new Google2FA();
        if ($google2fa->verifyKey($admin->google2fa_secret, $request->secret)) {
            return response()->json([
                'message' => 'success',
                'api_token' => $admin->api_token
            ]);
        }
        return response()->json([
            'message' => 'invalid secretCode'
        ]);
    }

    public function google2faLogin(Request $request)
    {

        $google2fa = new Google2FA();

        $secretKey = $google2fa->generateSecretKey();

        $QR_url = $google2fa->getQRCodeUrl(
            config('app.name'),
            'email',
            'google2fa_secret'
        );

        $user = User::where('api_token', auth()->user()->api_token)->update([
            'google2fa_secret' => $secretKey
        ]);


        return response()->json([
            $secretKey,
            $QR_url
        ]);


    }

}
