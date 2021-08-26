<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['name' => "Clientes"]
        ];

        $users = User::select('id', 'name', 'email', 'telefono', 'veterinaria_id', 'active')
            ->byVeterinaria()
            ->whereHas('roles', function ($q) {
                $q->where('name', 'cliente');
            })
            ->orderBy('active', 'DESC')
            ->orderBy('name')
            ->get();

        return view('clientes.index', compact('users', 'breadcrumbs'));
    }
}
