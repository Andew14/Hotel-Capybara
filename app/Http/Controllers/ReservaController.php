<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ReservaController extends Controller
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

        $response = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Reserva');

        if ($response->successful()) {
            $reservas = $response->json();
            return view('reservas.index', compact('reservas'));
        }

        return back()->withErrors(['message' => 'Error al cargar reservas']);
    }

    public function create()
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        // Fetch dependencies for dropdowns
        $huespedes = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Huesped')->json() ?? [];
        
        // Assuming endpoints for Habitacion and EstadoReserva exist or we handle them gracefully
        // If they don't exist, we might need to input IDs manually or mock them.
        // I'll try to fetch them, if fail, pass empty array.
        $habitaciones = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Habitacion')->json() ?? [];
        
        // Hardcoding states if API not available or just for simplicity as they are usually static
        // But better to try fetch.
        // If the user didn't provide Habitacion/EstadoReserva controllers, I can't be sure they exist.
        // I'll assume they might not and provide a fallback or text input in view if empty.
        
        return view('reservas.create', compact('huespedes', 'habitaciones'));
    }

    public function store(Request $request)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        // Fetch current user to get ID
        $userId = Session::get('user_id');

        if (!$userId) {
             // Fallback: try to find by username if ID not in token
             $username = Session::get('username');
             $users = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Usuario')->json();
             
             if (is_array($users)) {
                foreach ($users as $u) {
                    if (($u['correo'] ?? '') === $username) {
                        $userId = $u['idUsuario'] ?? $u['id'];
                        break;
                    }
                }
             }
        }

        if (!$userId) {
             return back()->withInput()->withErrors(['message' => 'No se pudo identificar al usuario actual para asignar la reserva.']);
        }

        $data = $request->all();
        $data['idUsuario'] = $userId;

        $response = Http::withoutVerifying()->withToken($token)->post($this->getApiUrl() . '/Reserva', $data);

        if ($response->successful()) {
            return redirect()->route('reservas.index')->with('success', 'Reserva creada exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al crear reserva: ' . $response->body()]);
    }

    public function edit($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        $reserva = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Reserva/' . $id)->json();
        $huespedes = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Huesped')->json() ?? [];
        $habitaciones = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Habitacion')->json() ?? [];

        return view('reservas.edit', compact('reserva', 'huespedes', 'habitaciones'));
    }

    public function update(Request $request, $id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');

        // Fetch existing reservation to get its current idUsuario
        $existingReserva = Http::withoutVerifying()->withToken($token)->get($this->getApiUrl() . '/Reserva/' . $id)->json();
        
        $data = $request->all();
        $data['idReserva'] = $id;
        
        // Preserve existing idUsuario or fallback to current user
        if (isset($existingReserva['idUsuario'])) {
            $data['idUsuario'] = $existingReserva['idUsuario'];
        } else {
             // Fallback logic similar to store if needed, or just send what we have
             $userId = Session::get('user_id');
             if ($userId) $data['idUsuario'] = $userId;
        }

        $response = Http::withoutVerifying()->withToken($token)->put($this->getApiUrl() . '/Reserva/' . $id, $data);

        if ($response->successful()) {
            return redirect()->route('reservas.index')->with('success', 'Reserva actualizada exitosamente');
        }

        return back()->withInput()->withErrors(['message' => 'Error al actualizar reserva: ' . $response->body()]);
    }
    public function destroy($id)
    {
        $token = $this->getToken();
        if (!$token) return redirect()->route('login');
        if (!$token) return redirect()->route('login');
        if (Session::get('role') !== 'Administrador') {
            return back()->withErrors(['message' => 'Acceso denegado.']);
        }

        $response = Http::withoutVerifying()->withToken($token)->delete($this->getApiUrl() . '/Reserva/' . $id);

        if ($response->successful()) {
            return redirect()->route('reservas.index')->with('success', 'Reserva eliminada exitosamente');
        }

        return back()->withErrors(['message' => 'Error al eliminar reserva: ' . $response->body()]);
    }
}
