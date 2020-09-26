<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\membershipRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class memberShipController extends Controller
{
    public function create(membershipRequest $request)
    {
        DB::table('membership')
            ->insert([
                'title' => $request->title,
                'price' => $request->price,
                'unity' => $request->unity,
                'revival' => $request->revival,
                'dmg_level_one' => $request->dmg_level_one,
                'dmg_level_two' => $request->dmg_level_two,
                'days' => $request->days,
                'created_at' => Carbon::now()->toDateTimeString()
            ]);
        Session::flash('addMembership', 'بسته معاملاتی جدید با موفقیت ایجاد شد');
        return redirect('/panel/main');

    }

    public function delete($id)
    {

        $memberShip = DB::delete('delete from memberShip where id = ?', [$id]);
        Session::flash('deleteMemberShip', 'بسته معاملاتی  با موفقیت حذف شد');
        return redirect('/panel/main');
    }

    public function edit($id)
    {
        $memberShip = DB::table('memberShip')
            ->where('id', $id)
            ->select('status')
            ->get();

        if ($memberShip[0]->status == 0) {
            $memberShip = DB::table('memberShip')
                ->where('id', $id)
                ->update([
                    "status" => 1
                ]);
        } elseif ($memberShip[0]->status == 1) {
            $memberShip = DB::table('memberShip')
                ->where('id', $id)
                ->update([
                    "status" => 0
                ]);
        }

        Session::flash('editMemberShip', 'وضعیت بسته معاملاتی با موفقیت تغییر کرد.');
        return redirect('/panel/main');
    }
}
