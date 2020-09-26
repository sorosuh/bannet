<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use MongoDB\BSON\Javascript;
use Morilog\Jalali\Jalalian;
use \Morilog\Jalali\CalendarUtils;
use PhpParser\Node\Expr\Cast\Object_;
use PragmaRX\Google2FA\Google2FA;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\JWTAuth;

class dashboardController extends Controller
{

    public function login(Request $request)
    {
        //dd($request->username);
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

    public function loginPage()
    {
        return view('admin.login');
    }

    public function mainPage()
    {

        $curentShmasiYear = Jalalian::now()->getYear();
        $month_timestamp = [];
        for ($i = 1; $i <= 13; $i++) {
            $toGregorian = CalendarUtils::toGregorian($curentShmasiYear, $i, 1);
            $month_timestamp [$i] = $GregorianDate = $toGregorian[0] . '-' . $toGregorian[1] . '-' . $toGregorian[2];
        }


        //--------------------------------------------------------//
        $userCount = User::where('grid', 0)->count();
        $userCountInMonth = [];

        for ($i = 1; $i <= 12; $i++) {
            $userCountInMonth[$i] = DB::table('users')
                ->where('grid', 0)
                ->whereDate('created_at', '>', $month_timestamp[$i])
                ->whereDate('created_at', '<', $month_timestamp[$i + 1])
                ->get();
        }

        //----------------- قراردادها به حز اگهی ها براساس سال جاری-----------------//

        $ads = DB::table('user_ads')
            ->where('status', '!=', 0)
            ->whereDate('created_at', '>', $month_timestamp[1])
            ->count();

        $adsCountInMonth = [];
        for ($i = 1; $i <= 12; $i++) {
            $adsCountInMonth[$i] = DB::table('user_ads')
                ->where('status', '!=', 0)
                ->whereDate('created_at', '>', $month_timestamp[$i])
                ->whereDate('created_at', '<', $month_timestamp[$i + 1])
                ->get();
        }

        //---------------- بیشترین اگهی ها براساس شهر-------------------//

        $cites = DB::table('user_ads')
            ->where('status', 0)
            ->select('city')
            ->distinct()
            ->get();
        $countOfCityAds = [];
        foreach ($cites as $city) {
            $count = DB::table('user_ads')
                ->where('status', 0)
                ->where('city', $city->city)
                ->count();
            $countOfCityAds[$city->city] = $count;
        }
        arsort($countOfCityAds);

        //-------------------------------------------//


        //-------------درامد های ثبت شده-------------//

        $invoice = DB::table('admin_invoice')->count('price');
        $invoiceInMonth = [];
        for ($i = 1; $i <= 12; $i++) {
            $invoiceInMonth[$i] = DB::table('admin_invoice')
                ->whereDate('created_at', '>', $month_timestamp[$i])
                ->whereDate('created_at', '<', $month_timestamp[$i + 1])
                ->get();
        }

        //----------------------------------------------//


        //------------------موجودی کاربران--------------------//

        $usersBalance = DB::table('user_financial')
            ->sum('balance');
        //dd($usersBalance);

        $distinctCity = DB::table('user_meta')
            ->select('city')
            ->distinct()
            ->get();

        $cityWithBalance = [];
        foreach ($distinctCity as $city) {

            $userWithSameCity = DB::table('user_meta')
                ->where('city', $city->city)
                ->select('user_id')
                ->get();

            $sum = 0;
            foreach ($userWithSameCity as $user) {

                $b = DB::table('user_financial')
                    ->where('user_id', $user->user_id)
                    ->select('balance')
                    ->get();
                if (!$b->isEmpty()) $sum += $b[0]->balance;
            }
            $cityWithBalance[$city->city] = $sum;
        }

        arsort($cityWithBalance);

        //----------------------------------------------//

        //---------------------درآمدهای سالانه-------------------------//

        $allKindOfinvoice = [];

        $invoiclvl1 = DB::table('admin_invoice')
            ->where('status', 1)
            ->whereDate('created_at', '>', $month_timestamp[1])
            ->sum('price');
        array_push($allKindOfinvoice, $invoiclvl1);

        $invoiclvl2 = DB::table('admin_invoice')
            ->where('status', 2)
            ->whereDate('created_at', '>', $month_timestamp[1])
            ->sum('price');
        array_push($allKindOfinvoice, $invoiclvl2);

        $memberShip = DB::table('admin_invoice')
            ->where('status', 3)
            ->whereDate('created_at', '>', $month_timestamp[1])
            ->sum('price');
        array_push($allKindOfinvoice, $memberShip);

        $unity = DB::table('admin_invoice')
            ->where('status', 4)
            ->whereDate('created_at', '>', $month_timestamp[1])
            ->sum('price');
        array_push($allKindOfinvoice, $unity);


        //----------------------------------------------//


        //----------------جدید ترین کاربران----------------------//

        $newUsersInfo = DB::table('users')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->select('users.id', 'users.username', 'users.created_at', 'user_meta.name', 'user_meta.family')
            ->where('grid', 0)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
       // dd($newUsersInfo);

        //----------------------------------------------//

        //------------------محصولات جدید--------------------//


        $newProducts = DB::table('product')
            ->select('id', 'model', 'brand', 'country')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();


        //----------------------------------------------//

        //-------------------بسته های معاملاتی-------------------//


        $memberShipsWithCount = [];

        $memberShips = DB::table('membership')
            ->select('*')
            ->get();

        foreach ($memberShips as $memberShip) {
            $countOfMembership = DB::table('user_financial')
                ->where('membership_id', '=', $memberShip->id)
                ->count();

            $memberShip->count = $countOfMembership;


            array_push($memberShipsWithCount, [0 => $memberShip]);
        }
        // dd($memberShipsWithCount);

        //----------------------------------------------//


        //-------------------js chart data---------------------//


        $MonthlyBannet = [
            "1" => [
                count($userCountInMonth[1]),
                count($adsCountInMonth[1]),
                count($invoiceInMonth[1])
            ],
            "2" => [
                count($userCountInMonth[2]),
                count($adsCountInMonth[2]),
                count($invoiceInMonth[2])
            ],
            "3" => [
                count($userCountInMonth[3]),
                count($adsCountInMonth[3]),
                count($invoiceInMonth[3])
            ],
            "4" => [
                count($userCountInMonth[4]),
                count($adsCountInMonth[4]),
                count($invoiceInMonth[4])
            ],
            "5" => [
                count($userCountInMonth[5]),
                count($adsCountInMonth[5]),
                count($invoiceInMonth[5])
            ],
            "6" => [
                count($userCountInMonth[6]),
                count($adsCountInMonth[6]),
                count($invoiceInMonth[6])
            ],
            "7" => [
                count($userCountInMonth[7]),
                count($adsCountInMonth[7]),
                count($invoiceInMonth[7])
            ], "8" => [
                count($userCountInMonth[8]),
                count($adsCountInMonth[8]),
                count($invoiceInMonth[8])
            ],
            "9" => [
                count($userCountInMonth[9]),
                count($adsCountInMonth[9]),
                count($invoiceInMonth[9])
            ],
            "10" => [
                count($userCountInMonth[10]),
                count($adsCountInMonth[10]),
                count($invoiceInMonth[10])
            ],
            "11" => [
                count($userCountInMonth[11]),
                count($adsCountInMonth[11]),
                count($invoiceInMonth[11])
            ],
            "12" => [
                count($userCountInMonth[12]),
                count($adsCountInMonth[12]),
                count($invoiceInMonth[12])
            ]
        ];
        //dd($MonthlyBannet);


        //-----------------------------------------------------//



        return view('admin.main.index', compact([
            'userCount',
            'userCountInMonth',
            'ads',
            'adsCountInMonth',
            'countOfCityAds',
            'invoice',
            'invoiceInMonth',
            'usersBalance',
            'cityWithBalance',
            'allKindOfinvoice',
            'newUsersInfo',
            'newProducts',
            'memberShipsWithCount',
            'MonthlyBannet'
        ]));
    }

    public function logout(){
        auth()->logout();
        Session::flash('logout', 'خروج موفقیت امیز');
        return redirect()->intended('/panel');
    }

    public function jsData()
    {

    }
}
