<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class HuespedController extends Controller
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

        $response = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Huesped');

        if ($response->successful()) {
            $huespedes = $response->json();
            return view('huespedes.index', compact('huespedes'));
        }

        return back()->withErrors(['message' => 'Error al cargar huéspedes']);
    }

    public function create()
    {
        return view('huespedes.create');
    }

    public function store(Request $request)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $response = Http::withoutVerifying()->withToken($token)->post($this->getApiUrl() . '/Huesped', $request->all());

        if ($response->successful()) {
            return redirect()->route('huespedes.index')->with('success', 'Huésped creado exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al crear huésped: ' . $response->body()]);
    }

    public function edit($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $response = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Huesped/' . $id);

        if ($response->successful()) {
            $huesped = $response->json();
            return view('huespedes.edit', compact('huesped'));
        }

        return back()->withErrors(['message' => 'Error al cargar huésped']);
    }

    public function update(Request $request, $id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        // Ensure ID is in the body as well if required by API, though usually it's in URL
        $data = $request->all();
        $data['idHuesped'] = $id;

        $response = Http::withoutVerifying()->withToken($token)->put($this->getApiUrl() . '/Huesped/' . $id, $data);

        if ($response->successful()) {
            return redirect()->route('huespedes.index')->with('success', 'Huésped actualizado exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al actualizar huésped: ' . $response->body()]);
    }
    public function destroy($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');
        if (!$token) return redirect()->route('login');
        if (Session::get('role') !== 'Administrador') {
            return back()->withErrors(['message' => 'Acceso denegado.']);
        }

        $response = Http::withoutVerifying()->withToken($token)->delete($this->getApiUrl() . '/Huesped/' . $id);

        if ($response->successful()) {
            return redirect()->route('huespedes.index')->with('success', 'Huésped eliminado exitosamente');
        }

        return back()->withErrors(['message' => 'Error al eliminar huésped: ' . $response->body()]);
    }
}
