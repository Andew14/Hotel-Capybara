<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class HabitacionController extends Controller
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

        $response = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Habitacion');

        if ($response->successful()) {
            $habitaciones = $response->json();
            return view('habitaciones.index', compact('habitaciones'));
        }

        return back()->withErrors(['message' => 'Error al cargar habitaciones']);
    }

    public function create()
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');
        if (Session::get('role') !== 'Administrador') {
            return redirect()->route('habitaciones.index')->withErrors(['message' => 'Acceso denegado.']);
        }

        $categorias = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/CategoriaHabitacion')->json() ?? [];
        return view('habitaciones.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');
        if (Session::get('role') !== 'Administrador') {
            return back()->withErrors(['message' => 'Acceso denegado.']);
        }

        $response = Http::withoutVerifying()->withToken($token)->post($this->getApiUrl() . '/Habitacion', $request->all());

        if ($response->successful()) {
            return redirect()->route('habitaciones.index')->with('success', 'Habitación creada exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al crear habitación: ' . $response->body()]);
    }

    public function edit($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $habitacion = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Habitacion/' . $id)->json();
        $categorias = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/CategoriaHabitacion')->json() ?? [];

        return view('habitaciones.edit', compact('habitacion', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $data = $request->all();
        $data['idHabitacion'] = $id;
        $data['estaDisponible'] = $request->has('estaDisponible');

        $response = Http::withoutVerifying()->withToken($token)->put($this->getApiUrl() . '/Habitacion/' . $id, $data);

        if ($response->successful()) {
            return redirect()->route('habitaciones.index')->with('success', 'Habitación actualizada exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al actualizar habitación: ' . $response->body()]);
    }

    public function destroy($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');
        if (!$token) return redirect()->route('login');
        if (Session::get('role') !== 'Administrador') {
            return back()->withErrors(['message' => 'Acceso denegado.']);
        }

        $response = Http::withoutVerifying()->withToken($token)->delete($this->getApiUrl() . '/Habitacion/' . $id);

        if ($response->successful()) {
            return redirect()->route('habitaciones.index')->with('success', 'Habitación eliminada exitosamente');
        }

        return back()->withErrors(['message' => 'Error al eliminar habitación: ' . $response->body()]);
    }
}
