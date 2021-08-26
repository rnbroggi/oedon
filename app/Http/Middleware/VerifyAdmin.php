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
        $user = $request->route('user');
        if (!$request->user()->hasRole('superadmin')) {
            $veterinaria_id = $user->veterinaria_id ?? null;

            if ($request->user()->veterinaria_id != $veterinaria_id)
                abort(404);

            if ($request->user()->hasRole('veterinario')) {
                if (!$user->hasRole('cliente'))
                    abort(404);
            }
        }

        return $next($request, $user);
    }
}
