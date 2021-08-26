<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Veterinaria;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('verify_admin')->only(['edit', 'update', 'destroy', 'changeStatus']);
        $this->middleware('can:list usuarios')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['name' => "Usuarios"]
        ];

        $users = User::with('roles', 'veterinaria:id,nombre')
            ->byVeterinaria()
            ->select('id', 'name', 'email', 'veterinaria_id', 'active')
            ->orderBy('active', 'DESC')
            ->orderBy('name')
            ->get();

        return view('users.index', compact('users', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $users_link = Auth::user()->hasRole('veterinario') ? ['link' => "/clientes", 'name' => "Clientes"] : ['link' => "/users", 'name' => "Usuarios"];
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], $users_link, ['name' => "Crear usuario"]
        ];

        $roles = Role::byRole()->select('id', 'name')->get();
        $permissions = Permission::select('id', 'name')->get();
        $veterinarias = Veterinaria::select('id', 'nombre')->get();

        $selected_role = $request->user_role;

        $request_view = $request->view;

        return view('users.create', compact('breadcrumbs', 'roles', 'permissions', 'veterinarias', 'selected_role', 'request_view'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name'           => $request->name,
                'email'          => $request->email,
                'telefono'       => $request->telefono,
                'veterinaria_id' => Auth::user()->hasRole('superadmin') ? $request->veterinaria_id : Auth::user()->veterinaria_id,
                'active'         => $request->active == 'on',
                'password'       => bcrypt($request->password),
            ]);

            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);

            DB::commit();

            if ($request->view == 'clientes') {
                return redirect()->route('clientes.index')
                    ->with('success', 'Cliente agregado correctamente');
            }

            return redirect()->route('users.index')
                ->with('success', 'Usuario agregado correctamente');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('users.create')
                ->with('error', 'Ocurrió un error intentado crear el usuario');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        $users_link = Auth::user()->hasRole('veterinario') ? ['link' => "/clientes", 'name' => "Clientes"] : ['link' => "/users", 'name' => "Usuarios"];
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], $users_link, ['name' => "Editar usuario"]
        ];

        $roles = Role::byRole()->select('id', 'name')->get();
        $permissions = Permission::select('id', 'name')->get();
        $veterinarias = Veterinaria::select('id', 'nombre')->get();

        $request_view = $request->view;

        return view('users.edit', compact('user', 'roles', 'permissions', 'breadcrumbs', 'veterinarias', 'request_view'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        DB::beginTransaction();
        try {
            $user->update([
                'name'           => $request->name,
                'email'          => $request->email,
                'telefono'       => $request->telefono,
                'veterinaria_id' => $request->veterinaria_id  ?? $user->veterinaria_id,
                'veterinaria_id' => Auth::user()->hasRole('superadmin') ? $request->veterinaria_id : $user->veterinaria_id,
                'active'         => $request->active == 'on',
                'password'       => $request->password ? bcrypt($request->password) : $user->password,
            ]);

            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);

            DB::commit();

            if ($request->view == 'clientes') {
                return redirect()->route('clientes.index')
                    ->with('success', 'Cliente actualizado correctamente');
            }

            return redirect()->route('users.index')
                ->with('success', 'Usuario modificado correctamente');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('users.edit', $user->id)
                ->with('error', 'Ocurrió un error intentado modificar el usuario');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $user->delete();

        if ($request->view == 'clientes') {
            return redirect()->route('clientes.index')
                ->with('success', 'Cliente eliminado correctamente');
        }

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado correctamente');
    }

    public function impersonate($user_id)
    {
        $user = User::findOrFail($user_id);
        Auth::user()->impersonate($user);
        return redirect()->route('home');
    }

    public function impersonateLeave()
    {
        Auth::user()->leaveImpersonation();
        return redirect()->route('users.index');
    }

    public function changeStatus(Request $request, User $user)
    {
        $user->changeStatus();

        $msg = $user->active ? 'activado' : 'desactivado';

        if ($request->view == 'clientes') {
            return redirect()->route('clientes.index')
                ->with('success', "Usuario $msg correctamente");
        }

        return redirect()->route('users.index')
            ->with('success', "Usuario $msg correctamente");
    }
}
