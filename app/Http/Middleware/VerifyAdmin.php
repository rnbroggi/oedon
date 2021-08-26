<?php

namespace App\Http\Middleware;

use Closure;

class VerifyAdmin
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
        if($request->user()->hasRole('administrativo')){
            $user = $request->route('user');
            $veterinaria_id = $user->veterinaria_id ?? null;
            
            if($request->user()->veterinaria_id != $veterinaria_id)
                abort(404);
        }
        return $next($request, $user);
    }
}
