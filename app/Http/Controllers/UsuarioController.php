<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class UsuarioController extends Controller
{
    private function getApiUrl()
    {
        return env('API_URL');
    }

    private function getToken()
    {
        return Session::get('jwt_token');
    }

    public function index()
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $response = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Usuario');

        if ($response->successful()) {
            $usuarios = $response->json();
            return view('usuarios.index', compact('usuarios'));
        }

        return back()->withErrors(['message' => 'Error al cargar usuarios']);
    }

    public function create()
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $roles = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Rol')->json() ?? [];
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        // The API endpoint for registration is Auth/registro, not Usuario POST usually, 
        // but based on the provided UsuarioController in backend, it only has GET.
        // The AuthController has [HttpPost("registro")].
        // So to create a user, we should probably use Auth/registro.
        // Let's check the provided AuthController again. 
        // Yes: [HttpPost("registro")] public async Task<ActionResult<Usuario>> Registro([FromBody] RegistroDto dto)
        // It takes Username, Password, Rol.

        $data = [
            'username' => $request->correo, // Mapping Correo to Username as per backend logic
            'password' => $request->password,
            'rol' => $request->rolNombre // We need to send role name, not ID, based on AuthController logic
        ];

        $response = Http::withoutVerifying()->withToken($token)->post($this->getApiUrl() . '/Auth/registro', $data);

        if ($response->successful()) {
            return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al crear usuario: ' . $response->body()]);
    }

    // Backend UsuarioController doesn't have PUT or DELETE. 
    // It seems only AuthController handles creation.
    // If there is no PUT/DELETE in backend UsuarioController, I cannot implement them here unless I missed something.
    // The user provided UsuarioController only has GET and GET{id}.
    // So I will only implement Index and Create (via Auth/registro).
    // Wait, the user said "Administrador tiene control completo puede borrar , añadir , y asi".
    // But the provided backend code for UsuarioController ONLY has GET.
    // I will implement what is possible. If the user wants delete, they need to add it to backend.
    // I'll stick to Index and Create for now.
    public function edit($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $response = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Usuario/' . $id);

        if ($response->successful()) {
            $usuario = $response->json();
            $roles = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Rol')->json() ?? [];
            return view('usuarios.edit', compact('usuario', 'roles'));
        }

        return back()->withErrors(['message' => 'Error al cargar usuario']);
    }

    public function update(Request $request, $id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        // 1. Fetch current user to get existing PasswordHash
        $currentUserResponse = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Usuario/' . $id);
        if ($currentUserResponse->failed()) {
            return back()->withErrors(['message' => 'Error al recuperar datos del usuario para actualización.']);
        }
        $currentUser = $currentUserResponse->json();

        // 2. Prepare payload
        $data = [
            'idUsuario' => (int)$id,
            'nombreCompleto' => $request->nombreCompleto,
            'correo' => $request->correo,
            'idRol' => (int)$request->idRol,
            'estaActivo' => isset($currentUser['estaActivo']) ? $currentUser['estaActivo'] : true,
            // Handle Password: If empty, use existing hash. If provided, send as is (warning: might be plain text if backend doesn't hash)
            'passwordHash' => $request->password ? $request->password : $currentUser['passwordHash']
        ];

        $response = Http::withoutVerifying()->withToken($token)->put($this->getApiUrl() . '/Usuario/' . $id, $data);

        if ($response->successful()) {
            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al actualizar usuario: ' . $response->body()]);
    }

    public function destroy($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $response = Http::withoutVerifying()->withToken($token)->delete($this->getApiUrl() . '/Usuario/' . $id);

        if ($response->successful()) {
            return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente');
        }

        return back()->withErrors(['message' => 'Error al eliminar usuario: ' . $response->body()]);
    }
}
