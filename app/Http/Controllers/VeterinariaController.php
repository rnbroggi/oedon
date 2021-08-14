<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVeterinaria;
use App\Veterinaria;
use Illuminate\Http\Request;

class VeterinariaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['name' => "Veterinarias"]
        ];

        $veterinarias = Veterinaria::select('id', 'nombre', 'direccion', 'email', 'telefono')
            ->orderBy('nombre')
            ->get();

        return view('veterinarias.index', compact('veterinarias', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/veterinarias", 'name' => "Veterinarias"], ['name' => "Crear veterinaria"]
        ];

        return view('veterinarias.create', compact('breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVeterinaria $request)
    {
        $veterinaria = Veterinaria::create($request->validated());

        return redirect()->route('veterinarias.index')
                ->with('success', "Veterinaria $veterinaria->nombre creada correctamente");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Veterinaria  $veterinaria
     * @return \Illuminate\Http\Response
     */
    public function show(Veterinaria $veterinaria)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/veterinarias", 'name' => "Veterinarias"], ['name' => "Detalle veterinaria"]
        ];

        return view('veterinarias.show', compact('veterinaria', 'breadcrumbs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Veterinaria  $veterinaria
     * @return \Illuminate\Http\Response
     */
    public function edit(Veterinaria $veterinaria)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/veterinarias", 'name' => "Veterinarias"], ['name' => "Editar veterinaria"]
        ];

        return view('veterinarias.edit', compact('veterinaria', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Veterinaria  $veterinaria
     * @return \Illuminate\Http\Response
     */
    public function update(StoreVeterinaria $request, Veterinaria $veterinaria)
    {
        $veterinaria->update($request->validated());

        return redirect()->route('veterinarias.index')
                ->with('success', "Veterinaria $veterinaria->nombre actualizada correctamente");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Veterinaria  $veterinaria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Veterinaria $veterinaria)
    {
        $veterinaria->delete();

        return redirect()->route('veterinarias.index')
            ->with('success', "Veterinaria $veterinaria->nombre eliminada correctamente");
    }
}
