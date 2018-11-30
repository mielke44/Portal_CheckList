<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\Notification;
use App\Flag;

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
        $notifications = Notification::where('admin_id',$session_id)->where('status','pending')->orderBy('created_at','desc')->select('id','name','text', 'type', 'created_at')->get();
        $date = array(); 
        foreach($notifications as $n){
            $data = explode(" ",$n['created_at']);
            $n['data'] = $data;
        }
        return json_encode($notifications);
    }

    
    public function getFlagNot(){
        $coll = Flag::where('type','notification')->where('receiver',Auth::user()->id)->get();
        if(sizeof($coll)!=0){
            Flag::where('type','notification')->where('receiver',Auth::user()->id)->delete();
            return 'true';
        }
        return 'false';
    }
}
