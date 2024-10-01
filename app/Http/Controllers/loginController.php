<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginRequest;
use App\Models\Comunidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCode; 

class loginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('panel');
        }

        // Obtener los roles
        $roles = Role::all();
        $comunidades = Comunidade::all();

        // Pasar los roles a la vista
        return view("auth.login", compact('roles', 'comunidades'));
    }

    public function login(loginRequest $request)
    {
        // Validar credenciales
        if (!Auth::validate($request->only("email", "password"))) {
            return redirect()->to('login')->withErrors('Credenciales Incorrectas');
        }

        $user = Auth::getProvider()->retrieveByCredentials($request->only('email', 'password'));

        // Generar código de verificación
        $verificationCode = rand(100000, 999999);

        // Guardar el código de verificación y el usuario en la sesión
        session(['verification_code' => $verificationCode, 'user' => $user]);

        // Enviar el código de verificación al correo del usuario
        Mail::to($user->email)->send(new VerificationCode($verificationCode));

        // Redirigir a la vista donde el usuario ingresará el código de verificación
        return redirect()->route('verify');
    }

    // Método para mostrar la vista donde el usuario ingresa el código
    public function showVerificationForm()
    {
        return view('auth.verify');
    }

    // Método para verificar el código
    public function verifyCode(Request $request)
    {
        // Validar el código ingresado
        $request->validate([
            'verification_code' => 'required|numeric',
        ]);

        // Verificar si el código es correcto
        if ($request->verification_code == session('verification_code')) {
            // Código correcto, loguear al usuario
            Auth::login(session('user'));

            // Limpiar la sesión
            session()->forget('verification_code');
            session()->forget('user');

            return redirect()->route('panel')->with('success', 'Bienvenido ' . Auth::user()->nombre . ' ' . Auth::user()->apellido);
        }

        // Si el código es incorrecto, redirigir con error
        return redirect()->route('verify')->withErrors('Código de verificación incorrecto.');
    }
}
