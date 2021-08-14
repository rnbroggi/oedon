<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => ['required', 'email', Rule::unique('users')->ignore(Auth::user())], // Validar que el email no se repita pero penmita seguir usando el mismo
            'password' => 'nullable|confirmed|min:6',
            'avatar'   => 'nullable|image'
        ];
    }

    public function attributes()
    {
        return [
            'name'     => 'Nombre',
            'password' => 'Contraseña',
        ];
    }
}
