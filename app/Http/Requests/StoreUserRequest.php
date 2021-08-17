<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('crud usuarios');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'           => 'required',
            'email'          => 'required|email|unique:users',
            'telefono'       => 'nullable|max:255',
            'password'       => 'required|confirmed|min:6',
            'roles'          => 'required|array',
            'permissions'    => 'nullable|array',
            'veterinaria_id' => 'nullable|exists:veterinarias,id',
        ];
    }

    public function attributes()
    {
        return [
            'name'        => 'Nombre',
            'password'    => 'Contraseña',
            'permissions' => 'Permisos',
        ];
    }
}
