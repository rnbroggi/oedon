<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMascota extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('crud mascotas');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombre'             => 'required|string|max:255',
            'raza_id'            => 'integer|required|exists:razas,id',
            'fecha_nacimiento'   => 'nullable|date',
            'peso'               => 'nullable|integer',
            'activo'             => 'boolean',
            'sexo_id'            => 'integer|nullable|exists:sexos,id',
            'foto'               => 'nullable|image',
            'observaciones'      => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'fecha_nacimiento' => 'Fecha de nacimiento',
            'raza_id'          => 'Raza',
        ];
    }
}
