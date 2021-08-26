<?php

namespace App\Http\Middleware;

use App\Visita;
use Closure;

class VerifyUserOnVisit
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
            $route_name = $request->route()->getName();
            $visita = null;

            if (in_array($route_name, ['visitas.singleFileDownload', 'visitas.deleteSingleFile'])) {
                $file = $request->route('file');
                $visita_id = $file->model_id ?? null;
                $visita = Visita::findOrFail($visita_id);
            }

            $visita = $visita ?? $request->route('visita');
            $veterinaria_id = $visita->veterinaria_id ?? null;

            if ($request->user()->veterinaria_id != $veterinaria_id)
                abort(404);
        }
        
        return $next($request, $visita);
    }
}