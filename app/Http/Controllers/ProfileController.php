<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function edit()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['name' => "Perfil"]
        ];

        return view('profile', compact('breadcrumbs'));
    }

    public function update(UpdateProfileRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $user->update([
                'email'    => $request->email,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
            ]);

            if ($request->hasFile('avatar')) {
                $user->clearMediaCollection('avatar');
                $user->addMedia($request->avatar)->toMediaCollection('avatar');
            }

            if($request->remove_avatar_input){
                $user->clearMediaCollection('avatar');
            }

            DB::commit();

            return redirect()->route('home')->with('profileSuccess', 'Perfil modificado!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('profile')
                ->with('error', 'Ocurrió un error intentado actualizar su perfil');
        }
    }
}
