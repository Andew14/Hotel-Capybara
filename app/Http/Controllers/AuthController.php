<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        try {
            // API URL from .env
            $apiUrl = env('API_URL');

            // Use withoutVerifying() for local development with self-signed certs
            $response = Http::withoutVerifying()->post("{$apiUrl}/Auth/login", [
                'username' => $request->username,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $token = $data['token'] ?? null;

                if ($token) {
                    Session::put('jwt_token', $token);
                    Session::put('username', $request->username);
                    
                    // Extract and store role and user ID
                    $role = $this->getRoleFromToken($token);
                    Session::put('role', $role);
                    
                    $userId = $this->getUserIdFromToken($token);
                    Session::put('user_id', $userId);

                    return redirect()->route('dashboard');
                } else {
                    return back()->withErrors(['message' => 'No se recibió el token del servidor.']);
                }
            } else {
                $errorMessage = $response->json()['message'] ?? 'Credenciales inválidas';
                if ($response->status() == 401) {
                    $errorMessage = 'Usuario o contraseña incorrectos.';
                }
                return back()->withErrors(['message' => $errorMessage]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Error de conexión con el servidor. Por favor intente más tarde.']);
        }
    }

    private function getRoleFromToken($token)
    {
        $tokenParts = explode('.', $token);
        if (count($tokenParts) < 2) return null;
        
        $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[1]));
        $claims = json_decode($payload, true);
        
        return $claims['http://schemas.microsoft.com/ws/2008/06/identity/claims/role'] 
            ?? $claims['role'] 
            ?? null;
    }

    private function getUserIdFromToken($token)
    {
        $tokenParts = explode('.', $token);
        if (count($tokenParts) < 2) return null;
        
        $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[1]));
        $claims = json_decode($payload, true);
        
        // Usually 'nameid' or 'sub' holds the ID
        return $claims['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/nameidentifier'] 
            ?? $claims['nameid'] 
            ?? $claims['sub'] 
            ?? null;
    }

    public function logout()
    {
        Session::forget('jwt_token');
        Session::forget('username');
        Session::forget('role');
        return redirect()->route('login');
    }
}
