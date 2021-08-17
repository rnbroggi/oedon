<?php

namespace App\Http\Controllers;

use App\Animal;
use App\Mascota;
use App\Raza;
use App\Sexo;
use App\User;
use App\Veterinaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MascotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['name' => "Mascotas"]
        ];

        $mascotas = Mascota::with([
            'sexo:id,nombre',
            'cliente' => function ($q) {
                $q->with('veterinaria:id,nombre')->select('id', 'name', 'veterinaria_id');
            },
            'raza' => function ($q2) {
                $q2->with('animal:id,nombre')->select('id', 'nombre', 'animal_id');
            }
        ])
            ->select('id', 'nombre', 'raza_id', 'sexo_id', 'user_id')
            ->get();

        return view('mascotas.index', compact('mascotas', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/mascotas", 'name' => "Mascotas"], ['name' => "Crear mascota"]
        ];

        $animales = Animal::select('id', 'nombre')->get();
        $razas = Raza::select('id', 'nombre', 'animal_id')->get();

        $veterinarios = User::byVeterinaria()->whereHas('roles', function ($q) {
            $q->where('name', 'veterinario');
        })
        ->select('id', 'name', 'veterinaria_id')
        ->get();

        $clientes = User::byVeterinaria()->whereHas('roles', function ($q) {
            $q->where('name', 'cliente');
        })
        ->select('id', 'name', 'veterinaria_id')
        ->get();

        $veterinarias = Veterinaria::select('id', 'nombre')->get();

        $sexos = Sexo::select('id', 'nombre')->get();

        return view('mascotas.create', compact('breadcrumbs', 'animales', 'razas', 'veterinarios', 'clientes', 'veterinarias', 'sexos'));
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
     * @param  \App\Mascota  $mascota
     * @return \Illuminate\Http\Response
     */
    public function show(Mascota $mascota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Mascota  $mascota
     * @return \Illuminate\Http\Response
     */
    public function edit(Mascota $mascota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Mascota  $mascota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mascota $mascota)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mascota  $mascota
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mascota $mascota)
    {
        //
    }
}
