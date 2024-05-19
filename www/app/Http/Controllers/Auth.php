<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\RequestAuth;
use App\Models\User;
use Hash;

class Auth extends Controller
{
    public function getToken(RequestAuth $request)
    {

        try {
        
            $user = User::where('email', $request->email)->first();
        
            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'E-mail ou Senha inválidos'], 401);
            }
        
            $token = $user->createToken('auth_token')->plainTextToken;
        
            return response()->json([
                'success'      => true,
                'access_token' => $token,
                'token_type'   => 'Bearer'
            ]);

        } catch (\Throwable $th) {
            Log::channel('database')->error('Auth.login', ['exception' => $th]);
            return response()->json([
                'error'   => true,
                'message' => 'Falha na execução'
            ]);
        }
    }
}
