<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class FacturaController extends Controller
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

        $response = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Factura');

        if ($response->successful()) {
            $facturas = $response->json();
            return view('facturas.index', compact('facturas'));
        }

        return back()->withErrors(['message' => 'Error al cargar facturas']);
    }

    public function create()
    {
        // Need to select a Reserva to invoice
        $token = $this->getToken();
        $reservas = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Reserva')->json() ?? [];
        return view('facturas.create', compact('reservas'));
    }

    public function store(Request $request)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $response = Http::withoutVerifying()->withToken($token)->post($this->getApiUrl() . '/Factura', $request->all());

        if ($response->successful()) {
            return redirect()->route('facturas.index')->with('success', 'Factura creada exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al crear factura: ' . $response->body()]);
    }
    public function edit($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $factura = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Factura/' . $id)->json();
        $reservas = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Reserva')->json() ?? [];

        return view('facturas.edit', compact('factura', 'reservas'));
    }

    public function update(Request $request, $id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $existingFactura = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Factura/' . $id)->json();

        $data = $request->except(['_token', '_method']);
        $data['idFactura'] = (int)$id;
        $data['idReserva'] = (int)$request->input('idReserva');
        $data['montoTotal'] = (float)$request->input('montoTotal');
        
        // Preserve other fields if any
        if ($existingFactura) {
            $data = array_merge($existingFactura, $data);
        }

        $response = Http::withoutVerifying()->withToken($token)->put($this->getApiUrl() . '/Factura/' . $id, $data);

        if ($response->successful()) {
            return redirect()->route('facturas.index')->with('success', 'Factura actualizada exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al actualizar factura: ' . $response->body()]);
    }

    public function destroy($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');
        if (Session::get('role') !== 'Administrador') {
            return back()->withErrors(['message' => 'Acceso denegado.']);
        }

        $response = Http::withoutVerifying()->withToken($token)->delete($this->getApiUrl() . '/Factura/' . $id);

        if ($response->successful()) {
            return redirect()->route('facturas.index')->with('success', 'Factura eliminada exitosamente');
        }

        return back()->withErrors(['message' => 'Error al eliminar factura: ' . $response->body()]);
    }
}
