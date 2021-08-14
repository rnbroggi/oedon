<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Requests\StorePermissionRequest;
use App\User;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['name' => "Permisos"]
        ];

        $permissions = Permission::select('id', 'name')->get();
        return view('permissions.index', compact('permissions', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/permissions", 'name' => "Permisos"], ['name' => "Crear permiso"]
        ];

        $roles = Role::select('id', 'name')->get();
        $users = User::select('id', 'name')->get();

        return view('permissions.create', compact('roles', 'users', 'breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermissionRequest $request)
    {
        DB::beginTransaction();
        try {
            $permission = Permission::create([
                'name'          => $request->name,
                'guard_name'    => 'web',
            ]);

            $permission->users()->sync($request->users);
            $permission->roles()->sync($request->roles);

            DB::commit();

            return redirect()->route('permissions.index')
                ->with('success', 'Permiso agregado correctamente');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('permissions.create')
                ->with('error', 'Ocurrió un error intentado crear el permiso');
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
            ['link' => "/", 'name' => "Home"], ['link' => "/permissions", 'name' => "Permisos"], ['name' => "Editar permiso"]
        ];

        $permission = Permission::findOrFail($id);
        $roles = Role::select('id', 'name')->get();
        $users = User::select('id', 'name')->get();

        return view('permissions.edit', compact('permission', 'roles', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePermissionRequest $request, $id)
    {
        $permission = Permission::findOrFail($id);
        DB::beginTransaction();
        try {
            $permission->update([
                'name' => $request->name,
            ]);

            $permission->users()->sync($request->users);
            $permission->roles()->sync($request->roles);

            DB::commit();

            return redirect()->route('permissions.index')
                ->with('success', 'Permiso modificado correctamente');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('permissions.create')
                ->with('error', 'Ocurrió un error intentado crear el permiso');
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
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions.index')
                ->with('success', 'Permiso eliminado correctamente');
    }
}
