<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validar los datos que envía el frontend
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Buscar al usuario
        $user = User::where('email', $request->email)->first();

        // 3. Verificar si el usuario existe y la contraseña es correcta
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // 4. Generar el Token de seguridad
        // El nombre 'admin-token' es descriptivo, puedes ponerle como quieras
        $token = $user->createToken('admin-token')->plainTextToken;

        // 5. Retornar el token al frontend
        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso.',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        // Revocar todos los tokens del usuario actual (cierra sesión en todos lados)
        // Opcional: $request->user()->currentAccessToken()->delete(); para cerrar solo la sesión actual
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente.'
        ]);
    }
}
