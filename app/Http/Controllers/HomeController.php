<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\Notification;
use App\Flag;
use Carbon\Carbon;
use App\Http\Controllers\CheckController;
use App\Employee;
use App\Check;
use Route;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
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
    public static function getPerm(){
        return Auth::user()->is_admin;
    }
    public static function getUser(){
        return Auth::user();
    }
    public function getNotifications(){
        $session_id = Auth::user()->id;
        if(Auth::user()->is_admin==-1)$notifications = Notification::where('employee_id',Employee::where('token',Auth::user()->token)->get()[0]->id)
                                                        ->where('status','pending')
                                                        ->orderBy('created_at','desc')
                                                        ->select('id','name','text', 'type', 'created_at')
                                                        ->get();
        else $notifications = Notification::where('admin_id',$session_id)
                                            ->where('status','pending')
                                            ->orderBy('created_at','desc')
                                            ->select('id','name','text', 'type', 'created_at')
                                            ->get();
        foreach($notifications as $n){
            $data = explode(" ",$n['created_at']);
            $n['data'] = $data;
        }
        return json_encode($notifications);
    }
    public function updateNotification(Request $r){
        $notification = Notification::findOrFail($r['id']);
        $notification->status = 'seen';
        $notification->save();
        return json_encode(array('error'=>false,'message'=>'Notification updated'));
    }
    public function clearAllNot(){
        $session_id = Auth::user()->id;
        if(Auth::user()->is_admin==-1)$notifications = Notification::where('employee_id',Employee::where('token',Auth::user()->token)->get()[0]->id)->delete();
        else $notifications = Notification::where('admin_id',$session_id)->delete();
        return json_encode(array('error'=>false));
    }
    public function getFlagNot(){
        date_default_timezone_set('America/Sao_Paulo');
        HomeController::Cleaner();
        if(Auth::user()->is_admin==-1){
            //is_admin -1 = not admin;
            $emp_id = Employee::where('token',Auth::user()->token)->get()[0]->id;
            $coll = Flag::where('type','emp notification')->where('receiver',$emp_id);
            if(sizeof($coll)!=0){
                Flag::where('type','emp notification')->where('receiver',$emp_id)->delete();
                return 'true';
            }
            return 'false';
        }else{
            $coll = Flag::where('type','notification')->where('receiver',Auth::user()->id)->get();
            if(sizeof($coll)!=0){
                Flag::where('type','notification')->where('receiver',Auth::user()->id)->delete();
                return 'true';
            }
            return 'false';
        }
    }
    public static function Cleaner(){
        date_default_timezone_set('America/Sao_Paulo');
        $date=Carbon::now()->toArray();
        foreach(Notification::all() as $n){
            $update=explode(" ",$n['updated_at']);
            $limit=str_split($update[0],2)[4]+2;
            if($limit==$date['day']){
                $n->delete();
            }
        }
    }
    public static function getRoutes(){
        $routeCollection = Route::getRoutes();
        $routes = [];
        foreach ($routeCollection as $value) {
            if($value->getName() != '')$routes[$value->getName()] = URL($value->uri);
        }
        return "routes=".json_encode($routes,JSON_PRETTY_PRINT);
    }
}
