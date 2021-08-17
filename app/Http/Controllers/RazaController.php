<?php

namespace App\Http\Controllers;

use App\Animal;
use App\Http\Requests\StoreRaza;
use App\Raza;
use Illuminate\Http\Request;

class RazaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['name' => "Razas"]
        ];

        $razas = Raza::with('animal:id,nombre')->select('id', 'nombre', 'animal_id')->orderBy('nombre')->get('id');
        return view('razas.index', compact('razas', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/razas", 'name' => "Razas"], ['name' => "Crear raza"]
        ];

        $animales = Animal::select('id', 'nombre')->get();

        return view('razas.create', compact('breadcrumbs', 'animales'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRaza $request)
    {
        $raza = Raza::create($request->validated());

        return redirect()->route('razas.index')
                ->with('success', "Raza $raza->nombre creada correctamente");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Raza  $raza
     * @return \Illuminate\Http\Response
     */
    public function edit(Raza $raza)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/razas", 'name' => "Razas"], ['name' => "Editar raza"]
        ];

        $animales = Animal::select('id', 'nombre')->get();

        return view('razas.edit', compact('raza', 'breadcrumbs', 'animales'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Raza  $raza
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRaza $request, Raza $raza)
    {
        $raza->update($request->validated());

        return redirect()->route('razas.index')
                ->with('success', "Raza $raza->nombre actualizada correctamente");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Raza  $raza
     * @return \Illuminate\Http\Response
     */
    public function destroy(Raza $raza)
    {
        $raza->delete();

        return redirect()->route('razas.index')
            ->with('success', "Raza $raza->nombre eliminada correctamente");
    }
}
