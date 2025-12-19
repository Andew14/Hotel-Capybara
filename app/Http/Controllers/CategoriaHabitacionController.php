<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CategoriaHabitacionController extends Controller
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

        $response = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/CategoriaHabitacion');

        if ($response->successful()) {
            $categorias = $response->json();
            return view('categorias.index', compact('categorias'));
        }

        return back()->withErrors(['message' => 'Error al cargar categorías']);
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $response = Http::withoutVerifying()->withToken($token)->post($this->getApiUrl() . '/CategoriaHabitacion', $request->all());

        if ($response->successful()) {
            return redirect()->route('categorias.index')->with('success', 'Categoría creada exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al crear categoría: ' . $response->body()]);
    }

    public function edit($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $categoria = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/CategoriaHabitacion/' . $id)->json();

        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $data = $request->all();
        $data['idCategoria'] = $id;

        $response = Http::withoutVerifying()->withToken($token)->put($this->getApiUrl() . '/CategoriaHabitacion/' . $id, $data);

        if ($response->successful()) {
            return redirect()->route('categorias.index')->with('success', 'Categoría actualizada exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al actualizar categoría: ' . $response->body()]);
    }

    public function destroy($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $response = Http::withoutVerifying()->withToken($token)->delete($this->getApiUrl() . '/CategoriaHabitacion/' . $id);

        if ($response->successful()) {
            return redirect()->route('categorias.index')->with('success', 'Categoría eliminada exitosamente');
        }

        return back()->withErrors(['message' => 'Error al eliminar categoría: ' . $response->body()]);
    }
}
