<?php

namespace App\Http\Controllers;

use App\Animal;
use App\Http\Requests\StoreMascota;
use App\Http\Requests\UpdateMascota;
use App\Mascota;
use App\Raza;
use App\Sexo;
use App\User;
use App\Veterinaria;
use App\Visita;
use Carbon\Carbon;
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

        $animales = Animal::select('id', 'nombre')->orderBy('nombre')->get();
        $razas = Raza::select('id', 'nombre', 'animal_id')->orderBy('nombre')->get();

        $veterinarios = User::with('veterinaria:id,nombre')->byVeterinaria()->whereHas('roles', function ($q) {
            $q->where('name', 'veterinario');
        })
            ->orderBy('name')
            ->select('id', 'name', 'veterinaria_id')
            ->get();

        $clientes = User::with('veterinaria:id,nombre')->byVeterinaria()->whereHas('roles', function ($q) {
            $q->where('name', 'cliente');
        })
            ->orderBy('name')
            ->select('id', 'name', 'veterinaria_id')
            ->get();

        $sexos = Sexo::select('id', 'nombre')->get();

        if (Auth::user()->hasRole('superadmin')) {
            foreach ($clientes as $cliente) {
                $nombre_veterinaria = $cliente->veterinaria->nombre ?? null;
                if($nombre_veterinaria)
                    $cliente->name = "$cliente->name ($nombre_veterinaria)";
            }

            foreach ($veterinarios as $veterinario) {
                $nombre_veterinaria = $veterinario->veterinaria->nombre ?? null;
                if($nombre_veterinaria)
                    $veterinario->name = "$veterinario->name ($nombre_veterinaria)";
            }
        }

        return view('mascotas.create', compact('breadcrumbs', 'animales', 'razas', 'veterinarios', 'clientes', 'sexos'));
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
                'observaciones'    => $request->observaciones,
            ]);

            $mascota = $this->createUser($request, $mascota);
            $this->registrarVisita($request, $mascota);

            // Agrego imagen
            if ($request->hasFile('foto')) {
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
        $visitas = $mascota->visitas;
        return view('mascotas.show', compact('mascota', 'visitas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Mascota  $mascota
     * @return \Illuminate\Http\Response
     */
    public function edit(Mascota $mascota)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/mascotas", 'name' => "Mascotas"], ['name' => "Editar mascota"]
        ];

        $animales = Animal::select('id', 'nombre')->orderBy('nombre')->get();
        $razas = Raza::select('id', 'nombre', 'animal_id')->orderBy('nombre')->get();

        $veterinarios = User::with('veterinaria:id,nombre')->byVeterinaria()->whereHas('roles', function ($q) {
            $q->where('name', 'veterinario');
        })
            ->orderBy('name')
            ->select('id', 'name', 'veterinaria_id')
            ->get();

        $clientes = User::with('veterinaria:id,nombre')->byVeterinaria()->whereHas('roles', function ($q) {
            $q->where('name', 'cliente');
        })
            ->orderBy('name')
            ->select('id', 'name', 'veterinaria_id')
            ->get();

        $sexos = Sexo::select('id', 'nombre')->get();

        if (Auth::user()->hasRole('superadmin')) {
            foreach ($clientes as $cliente) {
                $nombre_veterinaria = $cliente->veterinaria->nombre ?? null;
                if($nombre_veterinaria)
                    $cliente->name = "$cliente->name ($nombre_veterinaria)";
            }

            foreach ($veterinarios as $veterinario) {
                $nombre_veterinaria = $veterinario->veterinaria->nombre ?? null;
                if($nombre_veterinaria)
                    $veterinario->name = "$veterinario->name ($nombre_veterinaria)";
            }
        }

        return view('mascotas.edit', compact('breadcrumbs', 'animales', 'razas', 'veterinarios', 'clientes', 'sexos', 'mascota'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Mascota  $mascota
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMascota $request, Mascota $mascota)
    {
        $mascota->update([
            'nombre'           => $request->nombre,
            'raza_id'          => $request->raza_id,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'peso'             => $request->peso,
            'activo'           => $request->activo,
            'sexo_id'          => $request->sexo_id,
            'observaciones'    => $request->observaciones,
            'user_id'          => $request->cliente,
        ]);

        // Agrego imagen
        if ($request->hasFile('foto')) {
            $mascota->clearMediaCollection('foto');
            $mascota->addMedia($request->foto)->toMediaCollection('foto');
        }

        return redirect()->route('mascotas.index')
            ->with('success', "Mascota $mascota->nombre actualizada correctamente");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mascota  $mascota
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mascota $mascota)
    {
        $mascota->delete();

        return redirect()->route('mascotas.index')
            ->with('success', "Mascota $mascota->nombre eliminada correctamente");
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
                'veterinaria_id' => Auth::user()->veterinaria_id ?? (User::find($request->veterinario_id)->veterinaria_id ?? null),
            ]);

            $user->assignRole('cliente');

            $mascota->update(['user_id' => $user->id]);
        }

        return $mascota;
    }

    private function registrarVisita(Request $request, $mascota)
    {
        Visita::create([
            'mascota_id'          => $mascota->id,
            'fecha'               => Carbon::now(),
            'peso'                => $mascota->peso,
            'user_veterinario_id' => $request->veterinario_id,
            'veterinaria_id'      => Auth::user()->veterinaria_id ?? (User::find($request->veterinario_id)->veterinaria_id ?? null),
            'observaciones'       => "Primer consulta \n\n" . $mascota->observaciones,
        ]);
    }

    public function updatePicture(Request $request)
    {
        return;
    }
}
