<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ConsumoServicioController extends Controller
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

        $response = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/ConsumoServicio');

        if ($response->successful()) {
            $consumos = $response->json();
            return view('consumos.index', compact('consumos'));
        }

        return back()->withErrors(['message' => 'Error al cargar consumos']);
    }

    public function create()
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $reservas = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Reserva')->json() ?? [];
        $servicios = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Servicio')->json() ?? [];

        return view('consumos.create', compact('reservas', 'servicios'));
    }

    public function store(Request $request)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        // We need to fetch the service price to send it, as the model requires PrecioUnitario
        // Or maybe the backend handles it? The model says "Precio al momento de la compra".
        // Usually backend should handle this, but let's see if we can send it.
        // I'll fetch the service details to get the price.
        $servicioId = $request->idServicio;
        $servicio = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Servicio/' . $servicioId)->json();
        
        $data = $request->all();
        if ($servicio) {
            $data['precioUnitario'] = $servicio['precio'];
        }

        $response = Http::withoutVerifying()->withToken($token)->post($this->getApiUrl() . '/ConsumoServicio', $data);

        if ($response->successful()) {
            return redirect()->route('consumos.index')->with('success', 'Consumo registrado exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al registrar consumo: ' . $response->body()]);
    }

    public function edit($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $consumo = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/ConsumoServicio/' . $id)->json();
        $reservas = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Reserva')->json() ?? [];
        $servicios = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Servicio')->json() ?? [];

        return view('consumos.edit', compact('consumo', 'reservas', 'servicios'));
    }

    public function update(Request $request, $id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $servicioId = $request->idServicio;
        $servicio = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Servicio/' . $servicioId)->json();
        
        $data = $request->all();
        $data['idConsumo'] = $id;
        if ($servicio) {
            $data['precioUnitario'] = $servicio['precio'];
        }

        $response = Http::withoutVerifying()->withToken($token)->put($this->getApiUrl() . '/ConsumoServicio/' . $id, $data);

        if ($response->successful()) {
            return redirect()->route('consumos.index')->with('success', 'Consumo actualizado exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al actualizar consumo: ' . $response->body()]);
    }

    public function destroy($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $response = Http::withoutVerifying()->withToken($token)->delete($this->getApiUrl() . '/ConsumoServicio/' . $id);

        if ($response->successful()) {
            return redirect()->route('consumos.index')->with('success', 'Consumo eliminado exitosamente');
        }

        return back()->withErrors(['message' => 'Error al eliminar consumo: ' . $response->body()]);
    }
}
