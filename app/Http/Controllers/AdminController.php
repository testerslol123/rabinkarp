<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/index')->with('userInfo', Auth::user())->with('userData', User::all());
    }

    public function edituser($user_id=false) {
        // echo $user_id;
        // $getUser = [];
        $getUser = User::where('id', $user_id)->first();
        // print_r($getUser);
        return view('admin/edituser')->with('userInfo', Auth::user())->with('userData', $getUser);
    }

    public function submitEditUser (Request $request) {
        $getUser = User::where('id', $request->user_id)->first();
        $getUser->name = $request->nama;
        $getUser->email = $request->email;
        $getUser->save();
        return redirect('dashboard');
    }

    public function deleteUser($user_id) {
        $getUser = User::where('id', $user_id)->first();
        $getUser->delete();
        return redirect('dashboard');
    }

    public function user() {
        return view('admin/user');
    }
}
