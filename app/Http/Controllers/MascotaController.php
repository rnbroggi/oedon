<?php

namespace App\Http\Controllers;

use App\Animal;
use App\Http\Requests\StoreMascota;
use App\Mascota;
use App\Raza;
use App\Sexo;
use App\User;
use App\Veterinaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $veterinarios = User::with('veterinaria:id,nombre')->byVeterinaria()->whereHas('roles', function ($q) {
            $q->where('name', 'veterinario');
        })
            ->select('id', 'name', 'veterinaria_id')
            ->get();

        $clientes = User::with('veterinaria:id,nombre')->byVeterinaria()->whereHas('roles', function ($q) {
            $q->where('name', 'cliente');
        })
            ->select('id', 'name', 'veterinaria_id')
            ->get();

        $veterinarias = Veterinaria::select('id', 'nombre')->get();

        $sexos = Sexo::select('id', 'nombre')->get();

        if (Auth::user()->hasRole('superadmin')) {
            foreach ($clientes as $cliente) {
                $nombre_veterinaria = $cliente->veterinaria->nombre ?? null;
                $cliente->name = "$cliente->name ($nombre_veterinaria)";
            }

            foreach ($veterinarios as $veterinario) {
                $nombre_veterinaria = $veterinario->veterinaria->nombre ?? null;
                $veterinario->name = "$veterinario->name ($nombre_veterinaria)";
            }
        }

        return view('mascotas.create', compact('breadcrumbs', 'animales', 'razas', 'veterinarios', 'clientes', 'veterinarias', 'sexos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMascota $request)
    {
        DB::beginTransaction();

        try {
            $mascota = Mascota::create([
                'nombre'           => $request->nombre,
                'raza_id'          => $request->raza_id,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'peso'             => $request->peso,
                'activo'           => $request->activo,
                'sexo_id'          => $request->sexo_id,
                'veterinario_id'   => $request->veterinario_id,
                'observaciones'    => $request->observaciones,
            ]);

            $mascota = $this->createUser($request, $mascota);

            // Agrego imagen
            if($request->hasFile('foto')){
                $mascota->addMedia($request->foto)->toMediaCollection('foto');
            }

            DB::commit();

            return redirect()->route('mascotas.index')
                ->with('success', "Mascota $mascota->nombre agregada correctamente");

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
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

    private function createUser($request, $mascota)
    {
        if ($request->owner_exists == 'on') {
            $this->validate($request, [
                'cliente' => 'required|exists:users,id'
            ]);
            $mascota->update(['user_id' => $request->cliente]);
        } else {
            $this->validate($request, [
                'nombre_cliente'    => 'required|string|max:255',
                'email_cliente'     => 'required|email|max:255',
                'telefono_cliente'  => 'nullable|string|max:255',
                'password'          => 'required|min:6',
            ]);

            $user = User::create([
                'name'           => $request->nombre_cliente,
                'email'          => $request->email_cliente,
                'telefono'       => $request->telefono_cliente,
                'password'       => bcrypt($request->password),
                'veterinaria_id' => Auth::user()->veterinaria_id ?? (User::find($request->veterinario_id)->veterinara_id ?? null),
            ]);

            $user->assignRole('cliente');

            $mascota->update(['user_id' => $user->id]);
        }

        return $mascota;
    }
}
