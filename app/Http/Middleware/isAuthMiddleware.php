<?php

namespace App\Http\Middleware;

use App\Http\Helpers;
use Closure;
use Illuminate\Support\Facades\Session;

class isAuthMiddleware
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
        $token = Session::get('token');
        $userAuth = Helpers::isAuthenticated($token);

        // Valido que exista datos del usuario
        if ( isset($userAuth['result']) && ($userAuth['result'] === false) ) {
            return redirect('/');
        }

        return $next($request);
    }
}
