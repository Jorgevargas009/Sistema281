<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $producto= $this->route('producto');
        return [
            'nombre' => 'required|max:100',
            'descripcion'=> 'nullable|max:255',
            'precio'=> 'required|not_in:0',
            'precio_venta'=> 'required|not_in:0',
            'stock'=> 'required',
            'img_path'=> 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ];
    }
    public function messages(){
        return [
            'nombre.required'=> 'Se necesita asignar un nombre al producto',
            'precio.not_in'=> 'El precio del producto debe ser mayor a 0',
            'precio_venta.not_in'=> 'El precio del producto debe ser mayor a 0',
        ];
    }
}
