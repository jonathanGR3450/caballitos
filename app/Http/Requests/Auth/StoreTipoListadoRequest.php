<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StoreTipoListadoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre'            => ['required','string','max:150'],
            'slug'              => ['nullable','string','max:150','unique:tipo_listados,slug'],
            'descripcion'       => ['nullable','string','max:1000'],
            'is_ilimitado'      => ['required','boolean'],
            'max_productos'     => ['nullable','integer','min:1','prohibited_if:is_ilimitado,1'], // si es ilimitado, no permitir max
            'periodo'           => ['required','in:day,week,month,year'],
            'periodo_cantidad'  => ['required','integer','min:1'],
            'precio'            => ['required','numeric','min:0'],
            'is_activo'         => ['required','boolean'],
        ];
    }
}
