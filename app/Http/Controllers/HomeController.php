<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\Notification;

class HomeController extends Controller
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
        return view('home');
    }
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect("/login");

    }
    public static function getName()
    {
        $name = explode(" ",Auth::user()->name);
        return ucfirst($name[0]);

    }

    public function getNotifications(){
        $session_id = Auth::user()->id; 
        $notifications = Notification::where('admin_id',$session_id)->select('name','text')->get();
        return json_encode($notifications);
    }
}
