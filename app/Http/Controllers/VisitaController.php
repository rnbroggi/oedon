<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVisita;
use App\Mascota;
use App\User;
use App\Visita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\MediaStream;
class VisitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visitas = Visita::with('veterinaria:id,nombre', 'veterinario:id,name')
            ->select('id', 'mascota_id', 'fecha', 'peso', 'user_veterinario_id', 'veterinaria_id')
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
    public function store(StoreVisita $request)
    {
        DB::beginTransaction();
        try {
            $visita = Visita::create($request->validated());
            $visita->veterinaria_id = Auth::user()->veterinaria_id ?? (User::find($request->user_veterinario_id)->veterinaria_id ?? null);
            $visita->save();

            $visita->mascota()->update(['peso' => $visita->peso]);

            $this->uploadFiles($visita, $request);

            DB::commit();

            return redirect()->route('visitas.index')
                ->with('success', "Visita registrada correctamente");
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Visita  $visita
     * @return \Illuminate\Http\Response
     */
    public function show(Visita $visita)
    {
        return view('visitas.show', compact('visita'));
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
        $visita->delete();

        return redirect()->route('visitas.index')
            ->with('success', "Visita eliminada correctamente");
    }

    public function getVeterinarios(Request $request, $mascota_id)
    {
        $mascota = Mascota::findOrFail($mascota_id);
        $veterinaria_id = $mascota->cliente->veterinaria_id ?? null;

        return User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['administrativo', 'veterinario']);
        })
            ->select('id', 'name', 'veterinaria_id')
            ->where('veterinaria_id', $veterinaria_id)
            ->get();
    }

    private function uploadFiles(Visita $visita, $request)
    {
        if($request->hasFile('adjuntos')){
            foreach($request->adjuntos as $adjunto){
                $visita->addMedia($adjunto)->toMediaCollection('archivo');
            }
        }
    }

    public function multipleFileDownload(Visita $visita)
    {
        $this->checkUser($visita);
        $downloads = $visita->getMedia('archivo');

        $date = $visita->fecha->toDateString();
        $pet = $visita->mascota->nombre ?? '';

        return MediaStream::create("$pet-archivos-$date.zip")->addMedia($downloads);
    }

    public function singleFileDownload(Media $file)
    {
        $visita = Visita::findOrFail($file->model_id);
        $this->checkUser($visita);
        return $file;
    }    
    
    public function deleteSingleFile(Media $file)
    {
        $visita_id = $file->model_id;
        $visita = Visita::findOrFail($visita_id);
        $this->checkUser($visita);
        $file->delete();

        return redirect()->route('visitas.show', $visita_id)
                ->with('success', "Archivo eliminado correctamente");
    }

    private function checkUser($visita)
    {
        $logged_user = Auth::user();
        if ($logged_user->hasRole('superadmin')) return;

        if($visita->veterinaria_id != $logged_user->veterinaria_id)
            abort(404);
    }
}
