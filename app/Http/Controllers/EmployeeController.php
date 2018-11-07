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
<<<<<<< HEAD
        return view("Employee/create");
=======
        return view("Employee.create");
>>>>>>> Users
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
        $Employee -> name = $request['name'];
        $Employee -> email = $request['email'];
        $Employee -> password = bcrypt($request['password']);
        $Employee -> type = $request['select']['type'];
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->Employee = \Auth::Employee();

            $Employee = Employee::findOrFail($request->get('id'));
            $Employee->name        = $request->name;
            $Employee->email      =$request->email;
            $Employee->is_admin     =$request->is_admin;

            $all_request = $request->all();
            $Employee->fill($all_request);

            if ($Employee->save()) {
                return json_encode(array('error' => false,
                                        'message' => $Employee->id));
            }
            else {
                return json_encode(array('error' => true,
                                        'message' => 'Erro ao editar usuário. Por favor, tente novamente.'));
            }

            return redirect()->route('employee');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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

    public function list(){
        $list = Employee::all();
        foreach($list as $emp){
            $emp -> checks= 2;
            $emp -> list = 22;
        }
        return json_encode($list);
    }
}
