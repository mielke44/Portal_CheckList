<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Admin;
use App\User;
use App\Profile;
use Illuminate\Http\Request;
use Auth;
use App\Check;
use App\Checklist;
use App\Events\NewEmployeeEvent;

class EmployeeController extends Controller
{
    public function __construct(Request $r)
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view("employee");
    }

    public function store(Request $request)
    {
        if($request["id"] != ""){
            $Employee = Employee::find($request["id"]);
            $r = 'update';
        }
        else {
            $Employee = new Employee();
            $Employee ->token = bcrypt($request['name'].rand(100000,999999));
            $r = 'new';
        }
        $Employee -> name = $request['name'];
        $Employee -> email = $request['email'];
        $Employee -> profile_id = $request['profile_id'];
        $Employee -> CPF = $request['cpf'];
        $Employee -> fone = $request['fone'];
        $Employee -> site = $request['site'];
        $Employee -> gestor = $request['gestor'];
        if ($Employee -> save()) {
            event( new NewEmployeeEvent($Employee, Auth::user(),$r));
            return json_encode(array('error' => false,
                'message' => $Employee -> id));
        } else {
            return json_encode(array('error' => true,
                'message' => 'Ocorreu um erro, tente novamente!'));
        }
    }

    public function edit(Request $request)
    {
        $employee = Employee::findOrFail($request["id"]);
        $employee->profile=Profile::find($employee->profile_id)->name;
        $employee->gestor_name = Admin::findOrFail($employee->gestor)->name;
        return $employee;
    }

    public function destroy(Request $request)
    {
        $employee = Employee::findOrFail($request["id"]);
        if($employee->delete()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }

    public function list(Request $r){
        $list = Employee::all();
        foreach($list as $emp){
            $a = Checklist::where("employee_id",$emp->id)->get();
            $emp['check_true_size']=0;
            $emp["check_size"]=0;
            foreach($a as $b){
                $emp['check_true_size'] += Check::where("checklist_id",$b->id)->where("status",1)->count();
                $emp["check_size"] += Check::where("checklist_id",$b->id)->count();;
            }
            $emp->profile=Profile::find($emp->profile_id)->name;
        }
        return json_encode($list);
    }
}
