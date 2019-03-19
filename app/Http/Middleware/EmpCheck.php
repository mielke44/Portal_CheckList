<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class EmpCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->is_admin==0)return redirect('/')->with(['error'=>true,'message'=>'Seu usuário não tem permissão para acessar esta página!']);
    
        else return $next($request);
    }
}
