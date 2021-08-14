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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['name' => "Usuarios"]
        ];

        $users = User::with('roles', 'veterinaria:id,nombre')->select('id', 'name', 'email', 'veterinaria_id')->get();
        return view('users.index', compact('users', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/users", 'name' => "Usuarios"], ['name' => "Crear usuario"]
        ];

        $roles = Role::select('id', 'name')->get();
        $permissions = Permission::select('id', 'name')->get();
        $veterinarias = Veterinaria::select('id', 'nombre')->get();

        return view('users.create', compact('breadcrumbs', 'roles', 'permissions', 'veterinarias'));
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
                'veterinaria_id' => $request->veterinaria_id,
                'password'       => bcrypt($request->password),
            ]);

            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);

            DB::commit();

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
    public function edit($id)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/users", 'name' => "Usuarios"], ['name' => "Editar usuario"]
        ];

        $user = User::findOrFail($id);
        $roles = Role::select('id', 'name')->get();
        $permissions = Permission::select('id', 'name')->get();
        $veterinarias = Veterinaria::select('id', 'nombre')->get();

        return view('users.edit', compact('user', 'roles', 'permissions', 'breadcrumbs', 'veterinarias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        DB::beginTransaction();
        try {
            $user->update([
                'name'           => $request->name,
                'email'          => $request->email,
                'veterinaria_id' => $request->veterinaria_id,
                'password'       => $request->password ? bcrypt($request->password) : $user->password,
            ]);

            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuario modificado correctamente');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('users.edit', $id)
                ->with('error', 'Ocurrió un error intentado modificar el usuario');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

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
        return redirect()->route('home');
    }
}