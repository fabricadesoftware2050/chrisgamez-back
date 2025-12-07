<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        try {
            $credentials = request(['email', 'password']);

        // Agregar más datos al token
        $user = User::where('email', $credentials['email'])->where('active', true)->first();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized','message' => 'Datos de acceso incorrectos o usuario inactivo'], 401);
        }elseif ($user->role=='gmail'){
            return response()->json(['error' => 'Unauthorized','message' => 'Su inicio de sesión es con Gmail'], 401);
        }
        $customClaims = [
            'role' => $user->role,
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'expires_in' => auth()->factory()->getTTL() * 60

        ];
        if (! $token = auth()->claims($customClaims)->attempt($credentials)) {
        //if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized','message' => 'Datos de acceso incorrectos'], 401);
        }

        return $this->respondWithToken($token);
        } catch (Exception $ex) {
            return response()->json(['error' => 'Login failed', 'message' => $ex->getMessage()], 500);
            //throw $th;
        }
    }

    public function register(Request $request)
{
    try {

        // VALIDACIÓN
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ]);
        // BUSCAR USUARIO EXISTENTE
        $user = User::where('email', $request->email)->first();
        if ($user) {

            // SI ES UN USUARIO QUE FUE REGISTRADO POR GOOGLE → LOGIN DIRECTO
            if ($user->role === 'gmail') {

                $customClaims = [
                    'role'       => $user->role,
                    'user_id'    => $user->id,
                    'email'      => $user->email,
                    'name'       => $user->name,
                    'expires_in' => auth()->factory()->getTTL() * 60
                ];

                $token = auth()->claims($customClaims)->login($user);

                return $this->respondWithToken($token);
            }

            // SI EXISTE PERO NO ES GOOGLE → ERROR
            return response()->json([
                'error' => 'Email in use',
                'message' => 'El correo ya está registrado con método normal.'
            ], 400);
        }

        // CREAR USUARIO
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role'     => $validated['role'],   // Puedes modificarlo
            'active'   => true,
        ]);

        // CUSTOM CLAIMS (mismos que login)
        $customClaims = [
            'role'       => $user->role,
            'user_id'    => $user->id,
            'email'      => $user->email,
            'name'       => $user->name,
            'expires_in' => auth()->factory()->getTTL() * 60
        ];

        // GENERAR TOKEN
        $token = auth()->claims($customClaims)->login($user);

        return $this->respondWithToken($token);

    } catch (\Exception $ex) {
        return response()->json([
            'error' => 'Register failed',
            'message' => $ex->getMessage()
        ], 500);
    }
}

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            $user = auth()->user();
            return response()->json([
                'data' => $user,
                'message' => 'Operación exitosa'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to get user', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth()->logout();
            return response()->json([
                'data' => null,
                'message' => 'Operación exitosa'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to logout', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'data' => array(
                'user' => auth()->user(),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ),
            'message' => 'Operación exitosa'
        ]);
    }
}
