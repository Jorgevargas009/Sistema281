<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $user=$this->route("user");
        return [
            "nombre"=> "required|max:255",
            "apellido"=> "required|max:255",
            "telefono"=> "integer|between:60000000,99999999",
            "email"=> "required|email|max:255|unique:users,email,".$user->id,
            "password"=> "nullable|min:8|string|same:password_confirm|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/",
            "role"=> "required|exists:roles,name",
        ];
    }
    
    public function messages(){
        return [
            'nombre.required'=> 'Se necesita asignar un nombre de usuario',
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.regex' => 'La contraseña debe tener al menos una letra minúscula, una letra mayúscula y un número.',
        
        ];
    }
}
