<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("employee");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("create");
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

        $Employee = new Employee;
        $Employee -> name = $request -> name;
        $Employee -> email = $request -> email;
        $Employee -> password = bcrypt($request -> password);
        $Employee -> type = $request -> type;
        $Employee -> CPF = $request -> cpf;
        $Employee -> fone = $request -> fone;
        if ($Employee -> save()) {
            return json_encode(array('error' => false,
                'message' => $Employee -> id));
        } else {
            return json_encode(array('error' => true,
                'message' => 'Ocorreu um erro, tente novamente!'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $Employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $eid = Employee::get($id);
        return view("emp-edit",compact('eid'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $Employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $Employee)
    {
        $employee = employee::findOrFail($id);
        if ($employee->delete()) {
            return json_encode(array('error' => false,
                                        'message' => ''));
        } else {
            return json_encode(array('error' => true,
                                    'message' => 'Erro ao deletar usuário. Por favor, tente novamente.'));
        }
        //return redirect()->route('Employee$Employee.list');
    }
}
