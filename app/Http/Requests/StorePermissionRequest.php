<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('crud permisos');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required',
            'users'       => 'nullable|array',
            'permissions' => 'nullable|array',
        ];
    }

    public function attributes()
    {
        return [
            'name'        => 'Nombre',
            'permissions' => 'Permisos',
            'users'       => 'Usuarios',
        ];
    }
}
