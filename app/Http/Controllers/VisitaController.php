<?php

namespace App\Http\Controllers;

use App\Mascota;
use App\User;
use App\Visita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visitas = Visita::with('veterinaria:id,nombre', 'veterinario:id,name')->select('id', 'mascota_id', 'fecha', 'peso', 'user_veterinario_id', 'veterinaria_id')
            ->orderBy('id', 'DESC')
            ->get();

        return view('visitas.index', compact('visitas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mascotas = Mascota::with(['cliente' => function ($q) {
            $q->with('veterinaria:id,nombre');
        }])
            ->byVeterinaria()
            ->select('id', 'nombre', 'user_id')
            ->orderBy('nombre', 'ASC')
            ->get();

        $veterinarios = User::with('veterinaria:id,nombre')->byVeterinaria()->whereHas('roles', function ($q) {
            $q->where('name', 'veterinario');
        })
            ->orderBy('name')
            ->select('id', 'name', 'veterinaria_id')
            ->get();

        if (Auth::user()->hasRole('superadmin')) {
            foreach ($mascotas as $mascota) {
                $nombre_veterinaria = $mascota->cliente->veterinaria->nombre ?? null;
                if ($nombre_veterinaria)
                    $mascota->nombre = "$mascota->nombre ($nombre_veterinaria)";
            }
        }

        return view('visitas.create', compact('mascotas', 'veterinarios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Visita  $visita
     * @return \Illuminate\Http\Response
     */
    public function show(Visita $visita)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Visita  $visita
     * @return \Illuminate\Http\Response
     */
    public function edit(Visita $visita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Visita  $visita
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visita $visita)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Visita  $visita
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visita $visita)
    {
        //
    }

    public function getVeterinarios(Request $request, $mascota_id)
    {
        $mascota = Mascota::findOrFail($mascota_id);
        $veterinaria_id = $mascota->cliente->veterinaria_id ?? null;

        return User::whereHas('roles', function($q){
            $q->whereIn('name', ['administrativo', 'veterinario']);
        })
        ->select('id', 'name', 'veterinaria_id')
        ->where('veterinaria_id', $veterinaria_id)
        ->get();
    }
}
