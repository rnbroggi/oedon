<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoleRequest;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['name' => "Roles"]
        ];

        $roles = Role::select('id', 'name')->get();
        return view('roles.index', compact('roles', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/roles", 'name' => "Roles"], ['name' => "Crear rol"]
        ];

        $users = User::select('id', 'name')->get();
        $permissions = Permission::select('id', 'name')->get();

        return view('roles.create', compact('users', 'permissions', 'breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $role = Role::create([
                'name'          => $request->name,
                'guard_name'    => 'web',
            ]);

            $role->users()->sync($request->users);
            $role->syncPermissions($request->permissions);

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'Rol agregado correctamente');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('roles.create')
                ->with('error', 'Ocurrió un error intentado crear el rol');
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
            ['link' => "/", 'name' => "Home"], ['link' => "/roles", 'name' => "Roles"], ['name' => "Editar rol"]
        ];

        $role = Role::findOrFail($id);
        $users = User::select('id', 'name')->get();
        $permissions = Permission::select('id', 'name')->get();

        return view('roles.edit', compact('role', 'users', 'permissions', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        DB::beginTransaction();
        try {
            $role->update([
                'name' => $request->name,
            ]);

            $role->users()->sync($request->users);
            $role->syncPermissions($request->permissions);

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'Rol actualizado correctamente');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('roles.create')
                ->with('error', 'Ocurrió un error intentado actualizar el rol');
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
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')
                ->with('success', 'Rol eliminado correctamente');
    }
}
