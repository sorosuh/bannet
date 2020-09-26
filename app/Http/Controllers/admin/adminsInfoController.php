<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\adminRegisterRequest;
use App\Http\Requests\editAdminRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class adminsInfoController extends Controller
{
    public function index()
    {
        $admins = DB::table('users')
            ->select('*')
            ->where('grid', '=', 1)
            ->paginate(5);
        return view('admin.adminsInfo.adminsInfo', compact("admins"));
    }

    public function addAdmin(adminRegisterRequest $request)
    {

        $admin = new User();
        $admin->username = $request->username;
        $admin->password = bcrypt($request->password);
        $admin->phone = $request->phone;
        $admin->grid = 1;
        $admin->save();
        Session::flash('addAdmin', $admin->username . ' با موفقیت بعنوان ادمین اضافه شد .   ');
        return redirect('/panel/adminsInfo');
    }

    public function deleteAdmin($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();
        Session::flash('deleteAdmin', $admin->username . ' با موفقیت حذف شد.   ');
        return redirect('/panel/adminsInfo');

    }

    public function editPage($id)
    {
        $admin = User::find($id);
        $admins = DB::table('users')
            ->select('*')
            ->where('grid', '=', 1)
            ->paginate(5);
        return view('admin.adminsInfo.adminsInfo', compact(["admin", "admins"]));
    }

    public function editAdmin(editAdminRequest $request, $id)
    {
        //echo "edit admin";
        $admin = User::findOrFail($id);
        $username = $admin->username;
        $admin->username = $request->username ? $request->username : $admin->username;
        $admin->password = $request->password ? bcrypt($request->password) : $admin->password;
        $admin->phone = $request->phone ? $request->phone : $admin->phone;
        $admin->save();
        Session::flash('editAdmin', $username . '  با موفقیت بروزرسانی شد.   ');
        return redirect('/panel/adminsInfo');
    }


}
