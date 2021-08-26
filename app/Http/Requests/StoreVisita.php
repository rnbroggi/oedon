<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreVisita extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('crud visitas');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mascota_id'          => 'required|integer|exists:mascotas,id',
            'fecha'               => 'required|date_format:"Y-m-d\TH:i',
            'peso'                => 'nullable|integer',
            'user_veterinario_id' => 'required|integer|exists:users,id',
            'observaciones'       => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'mascota_id'          => 'Mascota',
            'user_veterinario_id' => 'Veterinario',
        ];
    }
}
