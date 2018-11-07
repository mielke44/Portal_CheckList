<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    public function login(){
        return view('layouts.vuetify-login');
    }

    public function create(UserRequest $request)
    {
        $this->_user = \Auth::user();

            $user = new user;
            $user->name      = $request->name;
            $user->email   = $request->email;
            $user->is_admin     =$request->is_admin;
            $user->password     =bcrypt($request->password);
            if ($user->save()) {
                return json_encode(array('error' => false,
                                        'message' => $user->id));
            }
            else {
                return json_encode(array('error' => true,
                                        'message' => 'Ocorreu um erro, tente novamente!'));
            }
    }


    public function update(UserUpdateRequest $request)
    {
        $this->_user = \Auth::user();
        if (password_verify($request->password,Auth::user()->password)) {
                $user = user::findOrFail($request->get('user_id'));
                $user->name        = $request->name;
                $user->email      =$request->email;
                $user->is_admin     =$request->is_admin;
                $user->password     =bcrypt($request->new_password);

                //$all_request = $request->all();
                //$user->fill($all_request); *password change wont work*
                if ($user->save()) {
                    return json_encode(array('error' => false,
                                            'message' => $user->id));
                }
                else {
                    return json_encode(array('error' => true,
                                            'message' => 'Ocorreu um erro, tente novamente!'));
                }

                return redirect()->route('user.list');
        }else{
            return json_encode(array('error' => true,
                                     'message' => 'A senha atual não é compatível'));
        }

    }

    public function remove($id)
    {
        $user = user::findOrFail($id);
        if ($user->delete()) {
            return json_encode(array('error' => false,
                                         'message' => ''));
        } else {
            return json_encode(array('error' => true,
                                     'message' => 'Erro ao deletar usuário. Por favor, tente novamente.'));
        }
        //return redirect()->route('user.list');
    }

}
