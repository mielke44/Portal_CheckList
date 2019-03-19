<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AdminCheck
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
        if(Auth::user()->is_admin!=2)return redirect('/')->with(['error'=>true,'message'=>'Seu usuário não tem permissão para acessar esta página!']);
        else return $next($request);
    }
}
