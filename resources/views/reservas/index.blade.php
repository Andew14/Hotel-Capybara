@extends('layouts.app')

@section('title', 'Reservas')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        <a href="{{ route('huespedes.index') }}" class="nav-link">Huéspedes</a>
        <a href="{{ route('facturas.index') }}" class="nav-link">Facturas</a>
        <a href="{{ route('servicios.index') }}" class="nav-link">Servicios</a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="nav-link" style="background:none; border:none; cursor:pointer; font-size:1rem;">Salir</button>
        </form>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span>Lista de Reservas</span>
            <a href="{{ route('reservas.create') }}" class="btn btn-primary" style="width: auto;">Nueva Reserva</a>
        </div>

        @if(session('success'))
            <div class="alert" style="background-color: var(--success-color); color: #fff;">{{ session('success') }}</div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-color);">
                <thead>
                    <tr style="background-color: rgba(255,255,255,0.05); text-align: left;">
                        <th style="padding: 1rem;">ID</th>
                        <th style="padding: 1rem;">Huésped</th>
                        <th style="padding: 1rem;">Habitación</th>
                        <th style="padding: 1rem;">Entrada</th>
                        <th style="padding: 1rem;">Salida</th>
                        <th style="padding: 1rem;">Estado</th>
                        <th style="padding: 1rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservas as $reserva)
                    <tr style="border-bottom: 1px solid #2d3748;">
                        <td style="padding: 1rem;">{{ $reserva['idReserva'] }}</td>
                        <td style="padding: 1rem;">{{ $reserva['huesped']['nombreCompleto'] ?? 'N/A' }}</td>
                        <td style="padding: 1rem;">{{ $reserva['habitacion']['numeroHabitacion'] ?? $reserva['habitacion']['numero'] ?? 'N/A' }}</td>
                        <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($reserva['fechaEntrada'])->format('d/m/Y') }}</td>
                        <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($reserva['fechaSalida'])->format('d/m/Y') }}</td>
                        <td style="padding: 1rem;">
                            <span style="background-color: var(--accent-color); color: #1a202c; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.8rem; font-weight: bold;">
                                {{ $reserva['estadoReserva']['nombre'] ?? 'Desconocido' }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <a href="{{ route('reservas.edit', $reserva['idReserva']) }}" style="color: var(--accent-color); text-decoration: none; margin-right: 10px;">Editar</a>
                            @if(Session::get('role') === 'Administrador')
                            <form action="{{ route('reservas.destroy', $reserva['idReserva']) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta reserva?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color: var(--error-color); cursor:pointer; text-decoration: underline;">Eliminar</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 1rem; text-align: center;">No hay reservas registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
