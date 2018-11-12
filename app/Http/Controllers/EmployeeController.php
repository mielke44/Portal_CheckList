<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Auth;

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
        $Employee -> type = $request['type'];
        $Employee -> CPF = $request['cpf'];
        $Employee -> fone = $request['fone'];
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
        $list = Employee::all();
        foreach($list as $emp){
            $emp -> checks= 2;
            $emp -> list = 22;
        }
        return json_encode($list);
    }
}
