<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ServicioController extends Controller
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

        $response = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Servicio');

        if ($response->successful()) {
            $servicios = $response->json();
            return view('servicios.index', compact('servicios'));
        }

        return back()->withErrors(['message' => 'Error al cargar servicios']);
    }
    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $response = Http::withoutVerifying()->withToken($token)->post($this->getApiUrl() . '/Servicio', $request->all());

        if ($response->successful()) {
            return redirect()->route('servicios.index')->with('success', 'Servicio creado exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al crear servicio: ' . $response->body()]);
    }

    public function edit($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $servicio = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Servicio/' . $id)->json();

        return view('servicios.edit', compact('servicio'));
    }

    public function update(Request $request, $id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $data = $request->all();
        $data['idServicio'] = $id;

        $response = Http::withoutVerifying()->withToken($token)->put($this->getApiUrl() . '/Servicio/' . $id, $data);

        if ($response->successful()) {
            return redirect()->route('servicios.index')->with('success', 'Servicio actualizado exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al actualizar servicio: ' . $response->body()]);
    }

    public function destroy($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $response = Http::withoutVerifying()->withToken($token)->delete($this->getApiUrl() . '/Servicio/' . $id);

        if ($response->successful()) {
            return redirect()->route('servicios.index')->with('success', 'Servicio eliminado exitosamente');
        }

        return back()->withErrors(['message' => 'Error al eliminar servicio: ' . $response->body()]);
    }
}
