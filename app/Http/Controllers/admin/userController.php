<?php

namespace App\Http\Controllers\admin;

use App\folder;
use App\Http\Controllers\Controller;
use App\media;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use function foo\func;

class userController extends Controller
{
    public function index()
    {

//        $users = DB::table('users')
//            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
//            ->where('grid', 0)
//            ->select('users.username', 'users.id', 'users.created_at', 'user_meta.name', 'user_meta.family')
//            ->get();

        $users = DB::table('users')
            ->where('grid', 0)
            ->get();

        $usersWithInfo = [];
        foreach ($users as $user) {
            $info = DB::table('user_meta')
                ->where('user_id', $user->id)
                ->get();
            array_push($usersWithInfo, [$info, $user->username, $user->created_at, $user->id]);
        }
        // dd($usersWithInfo);


        return view('admin.users.index', compact(['usersWithInfo']));
    }

    public function userInfo($id)
    {

        $info = DB::table('users')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->where('users.id', $id)
            ->select(
                'users.*',
                'user_meta.user_id',
                'user_meta.name',
                'user_meta.family',
                'user_meta.tell',
                'user_meta.city',
                'user_meta.id_card',
                'user_meta.mail',
                'user_meta.postal_code',
                'user_meta.license_code',
                'user_meta.score',
                'user_meta.address',
                'user_meta.is_validated'
            )
            ->get();


        $user_financial = DB::table('user_financial')
            ->where('user_id', $id)
            ->get();


        //--------------userImage
        /*        $kind = 1;
                $owner = $info[0]->id;
                $media = media::where('kind', $kind)
                    ->where('owner', $owner)
                    ->get();

                if (isset($media[0]))
                    $userImage = 'http://127.0.0.1:8000/'. 'public/files/' . media::find($media[0]->id)->folder->name . "/" . $media[0]->name;*/
        //dd($userImage);

        //--------------nationalCart
        $kind = 2;
        // dd(isset($info[0]));
        if (isset($info[0])) {
            $owner = $info[0]->id;
            $media = media::where('kind', $kind)
                ->where('owner', $owner)
                ->get();

            $nationalCart = [];
            if (isset($media[0])) {
                $nationalCart[0] = env('APP_URL') . 'public/files/' . media::find($media[0]->id)->folder->name . "/" . $media[0]->name;
                $nationalCart[1] = $media[0]->alt;
            }
        }

        //dd($nationalCart);


        //--------------bankCart
        $kind = 4;
        if (isset($info[0])) {
            $owner = $info[0]->id;
            $media = media::where('kind', $kind)
                ->where('owner', $owner)
                ->get();

            $bankCart = [];
            if (isset($media[0])) {
                $bankCart[0] = env('APP_URL')  . 'public/files/' . media::find($media[0]->id)->folder->name . "/" . $media[0]->name;
                $bankCart[1] = $media[0]->alt;
            }
        }


        //--------------licenseImage
        $kind = 3;
        if (isset($info[0])) {
            $owner = $info[0]->id;
            $media = media::where('kind', $kind)
                ->where('owner', $owner)
                ->get();

            $licenseImage = [];
            if (isset($media[0])) {
                $licenseImage[0] = env('APP_URL')  . 'public/files/' . media::find($media[0]->id)->folder->name . "/" . $media[0]->name;
                $licenseImage[1] = $media[0]->alt;
            }

        }

        //---------------isConfirmMedia
        $isConfirmMedia = DB::table('user_uploads')
            ->where('user_id', $id)
            ->get();


        if (isset($user_financial[0]))
            $user_memberShip = DB::table('membership')
                ->where('id', $user_financial[0]->membership_id)
                ->get();

        //------------userInvoices

        $userInvoices = DB::table('invoice')
            ->where('user_id', $id)
            ->get();


        //------------user_ads

        $userAdsInfo = [];
        $user_ads = DB::table('user_ads')
            ->where('user_id', $id)
            ->Join('product', 'user_ads.product_id', '=', 'product.id')
            ->select('user_ads.*', 'product.model')
            ->get();
        foreach ($user_ads as $ads) {
            $winner = DB::table('user_meta')->where('user_id', $ads->winner_id)->get();
            array_push($userAdsInfo, [$ads, $winner]);
        }


        //---------------userBuy
        $userBuy = DB::table('user_ads')
            ->where('winner_id','=', $id)
            ->where('status','=',3)
            ->sum('count');

        //----------------usercontract (قرارداد)
         $userContract = DB::table('user_ads')
             ->where(function($user) use ($id) {
                 $user->where('user_id','=',$id)->orWhere('winner_id','=', $id);
             })
             ->count();


        //---------------userSold
         $userSold = DB::table('user_ads')
             ->where('user_id','=',$id)
             ->count();




        return view('admin.users.userInfo', compact([
            'info',
            'user_financial',
            'user_memberShip',
            'nationalCart',
            'bankCart',
            'licenseImage',
            'userInvoices',
            'isConfirmMedia',
            'userAdsInfo',
            'userBuy',
            'userContract',
            'userSold',
        ]));
    }

    // 0 => unconfirm
    // 1 => confirm
    public function unconfirmMedia($id, $status)
    {
        $media = [];
        if ($status == 0) {
            $media[0] = $nationalCart = DB::table('media')->where('owner', $id)->where('kind', 2)->get();
            $media[1] = $licence = DB::table('media')->where('owner', $id)->where('kind', 3)->get();
            $media[2] = $bankCart = DB::table('media')->where('owner', $id)->where('kind', 4)->get();

            foreach ($media as $a) {
                if (isset($a[0])) {

                    $db = media::find($a[0]->id)->delete();
                    $folder = folder::findOrFail($a[0]->folder_id);
                    $path = 'public/files/' . $folder->name . '/' . $a[0]->name;
                    $unlink = unlink($path);
                    $folder->size--;
                    $folder->save();

                    DB::table('user_uploads')
                        ->where('user_id', $id)
                        ->update([
                            "license_code" => 0,
                            "credit_card" => 0,
                            "id_card" => 0
                        ]);
                }

            }
            Session::flash('sendAgainRequest', 'درخواست ارسال مجدد ارسال شد.   ');
            return redirect('/panel/users/userInfo/' . $id);

        }
        if ($status == 1) {
            DB::table('user_uploads')
                ->where('user_id', $id)
                ->update([
                    "license_code" => 2,
                    "credit_card" => 2,
                    "id_card" => 2
                ]);

            Session::flash('confirmInfo', 'مشخصات تایید شد.');
            return redirect('/panel/users/userInfo/' . $id);
        }
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        Session::flash('deleteUser', $user->username . ' با موفقیت حذف شد.   ');
        return redirect('/panel/users');
    }
}
