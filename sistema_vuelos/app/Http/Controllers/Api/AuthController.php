<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Services\MailService;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected MailService $mail;
    public function __construct(MailService $mail){ $this->mail = $mail; }

    // POST /api/auth/registro
    public function registro(Request $request)
    {
        $datos = $request->validate([
            'nombre'   => 'required|string|max:100',
            'email'    => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6',
            'rol'      => 'sometimes|in:admin,operador,cliente',
        ]);

        $token = Str::random(40);
        $usuario = Usuario::create([
            'nombre'               => $datos['nombre'],
            'email'                => $datos['email'],
            'password'             => $datos['password'],
            'rol'                  => $datos['rol'] ?? 'cliente',
            'token_verificacion'   => $token,
            'email_verificado'     => true,
        ]);

        $enviado = $this->mail->enviarVerificacion($datos['email'], $datos['nombre'], $token);

        return response()->json([
            'status'  => 'success',
            'mensaje' => 'Registro exitoso. ' . ($enviado ? 'Revisa tu correo para verificar tu cuenta.' : 'No se pudo enviar el correo de verificación.'),
            'data'    => ['id' => $usuario->id, 'nombre' => $usuario->nombre, 'email' => $usuario->email],
        ], 201);
    }

    // GET /api/auth/verificar/{token}
    public function verificar($token)
    {
        $usuario = Usuario::where('token_verificacion', $token)->first();
        if (!$usuario) {
            return response()->json(['status' => 'error', 'mensaje' => 'Token inválido o ya usado.'], 404);
        }
        $usuario->update(['email_verificado' => true, 'token_verificacion' => null]);
        return response()->json(['status' => 'success', 'mensaje' => '✅ Correo verificado. Ya puedes iniciar sesión.']);
    }

    // POST /api/auth/login
    public function login(Request $request)
    {
        $datos = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where('email', $datos['email'])->first();

        if (!$usuario || !$usuario->verificarPassword($datos['password'])) {
            return response()->json(['status' => 'error', 'mensaje' => 'Credenciales incorrectas.'], 401);
        }
        if (!$usuario->email_verificado) {
            return response()->json(['status' => 'error', 'mensaje' => 'Debes verificar tu correo electrónico primero.'], 403);
        }

        // Token simple de sesión (para proyecto sin Sanctum)
        $sessionToken = Str::random(60);
        $usuario->update(['token_reset' => $sessionToken]); // reutilizamos campo para el token de sesión

        return response()->json([
            'status'  => 'success',
            'mensaje' => 'Sesión iniciada correctamente.',
            'token'   => $sessionToken,
            'usuario' => ['id' => $usuario->id, 'nombre' => $usuario->nombre, 'email' => $usuario->email, 'rol' => $usuario->rol],
        ]);
    }

    // POST /api/auth/reset-solicitud
    public function resetSolicitud(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario) {
            // Por seguridad, siempre respondemos igual
            return response()->json(['status' => 'success', 'mensaje' => 'Si el correo existe, recibirás instrucciones.']);
        }

        $token = Str::random(40);
        $usuario->update([
            'token_reset'         => $token,
            'token_reset_expira'  => now()->addMinutes(60),
        ]);

        $this->mail->enviarResetPassword($usuario->email, $usuario->nombre, $token);

        return response()->json(['status' => 'success', 'mensaje' => 'Si el correo existe, recibirás instrucciones.']);
    }

    // POST /api/auth/reset-password
    public function resetPassword(Request $request)
    {
        $datos = $request->validate([
            'token'    => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $usuario = Usuario::where('token_reset', $datos['token'])
                          ->where('token_reset_expira', '>', now())
                          ->first();

        if (!$usuario) {
            return response()->json(['status' => 'error', 'mensaje' => 'Token inválido o expirado.'], 400);
        }

        $usuario->update(['password' => $datos['password'], 'token_reset' => null, 'token_reset_expira' => null]);

        return response()->json(['status' => 'success', 'mensaje' => '✅ Contraseña actualizada correctamente.']);
    }
}
