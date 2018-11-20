<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Profile;
use Illuminate\Http\Request;
use Auth;
use App\Check;
use App\Checklist;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view("employee");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$this -> Employee = \Auth::Employee();
        //print_r($request->all());
        //return;
        if($request["id"] != "") $Employee = Employee::find($request["id"]);
        else $Employee = new Employee();
        $Employee -> name = $request['name'];
        $Employee -> email = $request['email'];
        $Employee -> profile_id = $request['profile_id'];
        $Employee -> CPF = $request['cpf'];
        $Employee -> fone = $request['fone'];
        $Employee -> site = $request['site'];
        if ($Employee -> save()) {
            return json_encode(array('error' => false,
                'message' => $Employee -> id));
        } else {
            return json_encode(array('error' => true,
                'message' => 'Ocorreu um erro, tente novamente!'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $employee = Employee::findOrFail($request["id"]);
        $employee->profile=Profile::find($employee->profile_id)->name;
        return $employee;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $employee = Employee::findOrFail($request["id"]);
        if($employee->delete()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }

    public function list(){
        $site = Auth::user()->site;
        $list = Employee::where("site",$site)->get();
        foreach($list as $emp){
            $a = Checklist::where("employee_id",$emp->id)->get();
            foreach($a as $b){
                $emp['check_true_size'] = Check::where("checklist_id",$b->id)->where("status",1)->count();
                $emp["check_size"] = Check::where("checklist_id",$b->id)->count();;
            }
            $emp->profile=Profile::find($emp->profile_id)->name;
        }
        return json_encode($list);
    }
}
