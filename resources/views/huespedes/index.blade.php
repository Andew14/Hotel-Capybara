@extends('layouts.app')

@section('title', 'Huéspedes')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        <a href="{{ route('reservas.index') }}" class="nav-link">Reservas</a>
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
            <span>Lista de Huéspedes</span>
            <a href="{{ route('huespedes.create') }}" class="btn btn-primary" style="width: auto;">Nuevo Huésped</a>
        </div>

        @if(session('success'))
            <div class="alert" style="background-color: var(--success-color); color: #fff;">{{ session('success') }}</div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-color);">
                <thead>
                    <tr style="background-color: rgba(255,255,255,0.05); text-align: left;">
                        <th style="padding: 1rem;">ID</th>
                        <th style="padding: 1rem;">Nombre</th>
                        <th style="padding: 1rem;">Documento</th>
                        <th style="padding: 1rem;">Teléfono</th>
                        <th style="padding: 1rem;">Correo</th>
                        <th style="padding: 1rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($huespedes as $huesped)
                    <tr style="border-bottom: 1px solid #2d3748;">
                        <td style="padding: 1rem;">{{ $huesped['idHuesped'] }}</td>
                        <td style="padding: 1rem;">{{ $huesped['nombreCompleto'] }}</td>
                        <td style="padding: 1rem;">{{ $huesped['documentoIdentidad'] }}</td>
                        <td style="padding: 1rem;">{{ $huesped['telefono'] }}</td>
                        <td style="padding: 1rem;">{{ $huesped['correo'] }}</td>
                        <td style="padding: 1rem;">
                            <a href="{{ route('huespedes.edit', $huesped['idHuesped']) }}" style="color: var(--accent-color); text-decoration: none; margin-right: 10px;">Editar</a>
                            @if(Session::get('role') === 'Administrador')
                            <form action="{{ route('huespedes.destroy', $huesped['idHuesped']) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este huésped?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color: var(--error-color); cursor:pointer; text-decoration: underline;">Eliminar</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 1rem; text-align: center;">No hay huéspedes registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
