<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class RolController extends Controller
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

        $response = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Rol');

        if ($response->successful()) {
            $roles = $response->json();
            return view('roles.index', compact('roles'));
        }

        return back()->withErrors(['message' => 'Error al cargar roles']);
    }
    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $response = Http::withoutVerifying()->withToken($token)->post($this->getApiUrl() . '/Rol', $request->all());

        if ($response->successful()) {
            return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al crear rol: ' . $response->body()]);
    }

    public function edit($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $rol = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Rol/' . $id)->json();

        return view('roles.edit', compact('rol'));
    }

    public function update(Request $request, $id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $data = $request->all();
        $data['idRol'] = $id;

        $response = Http::withoutVerifying()->withToken($token)->put($this->getApiUrl() . '/Rol/' . $id, $data);

        if ($response->successful()) {
            return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al actualizar rol: ' . $response->body()]);
    }

    public function destroy($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $response = Http::withoutVerifying()->withToken($token)->delete($this->getApiUrl() . '/Rol/' . $id);

        if ($response->successful()) {
            return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente');
        }

        return back()->withErrors(['message' => 'Error al eliminar rol: ' . $response->body()]);
    }
}
