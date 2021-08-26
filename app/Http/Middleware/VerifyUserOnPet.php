<?php

namespace App\Http\Middleware;

use Closure;

class VerifyUserOnPet
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
        if (!$request->user()->hasRole('superadmin')) {
            $mascota = $request->route('mascota');
            $veterinaria_id = $mascota->cliente->veterinaria_id ?? null;

            if ($request->user()->veterinaria_id != $veterinaria_id)
                abort(404);

            if ($request->user()->hasRole('cliente')) {
                if ($mascota->user_id != $request->user()->id)
                    abort(404);
            }
        }
        return $next($request, $mascota);
    }
}
