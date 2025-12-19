@extends('layouts.app')

@section('title', 'Habitaciones')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        <a href="{{ route('categorias.index') }}" class="nav-link">Categorías</a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="nav-link" style="background:none; border:none; cursor:pointer; font-size:1rem;">Salir</button>
        </form>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span>Lista de Habitaciones</span>
            @if(Session::get('role') === 'Administrador')
            <a href="{{ route('habitaciones.create') }}" class="btn btn-primary" style="width: auto;">Nueva Habitación</a>
            @endif
        </div>

        @if(session('success'))
            <div class="alert" style="background-color: var(--success-color); color: #fff;">{{ session('success') }}</div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-color);">
                <thead>
                    <tr style="background-color: rgba(255,255,255,0.05); text-align: left;">
                        <th style="padding: 1rem;">ID</th>
                        <th style="padding: 1rem;">Número</th>
                        <th style="padding: 1rem;">Piso</th>
                        <th style="padding: 1rem;">Categoría</th>
                        <th style="padding: 1rem;">Estado</th>
                        <th style="padding: 1rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($habitaciones as $habitacion)
                    <tr style="border-bottom: 1px solid #2d3748;">
                        <td style="padding: 1rem;">{{ $habitacion['idHabitacion'] }}</td>
                        <td style="padding: 1rem;">{{ $habitacion['numeroHabitacion'] }}</td>
                        <td style="padding: 1rem;">{{ $habitacion['piso'] }}</td>
                        <td style="padding: 1rem;">{{ $habitacion['categoriaHabitacion']['nombre'] ?? 'N/A' }}</td>
                        <td style="padding: 1rem;">
                            @if($habitacion['estaDisponible'])
                                <span style="color: var(--success-color);">Disponible</span>
                            @else
                                <span style="color: var(--error-color);">Ocupada</span>
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            <a href="{{ route('habitaciones.edit', $habitacion['idHabitacion']) }}" style="color: var(--accent-color); text-decoration: none; margin-right: 10px;">Editar</a>
                            @if(Session::get('role') === 'Administrador')
                            <form action="{{ route('habitaciones.destroy', $habitacion['idHabitacion']) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta habitación?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color: var(--error-color); cursor:pointer; text-decoration: underline;">Eliminar</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 1rem; text-align: center;">No hay habitaciones registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
