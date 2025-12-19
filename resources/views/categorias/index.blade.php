@extends('layouts.app')

@section('title', 'Categorías de Habitación')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        <a href="{{ route('habitaciones.index') }}" class="nav-link">Habitaciones</a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="nav-link" style="background:none; border:none; cursor:pointer; font-size:1rem;">Salir</button>
        </form>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span>Lista de Categorías</span>
            <a href="{{ route('categorias.create') }}" class="btn btn-primary" style="width: auto;">Nueva Categoría</a>
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
                        <th style="padding: 1rem;">Precio Base</th>
                        <th style="padding: 1rem;">Descripción</th>
                        <th style="padding: 1rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                    <tr style="border-bottom: 1px solid #2d3748;">
                        <td style="padding: 1rem;">{{ $categoria['idCategoria'] }}</td>
                        <td style="padding: 1rem;">{{ $categoria['nombre'] }}</td>
                        <td style="padding: 1rem;">${{ number_format($categoria['precioBase'], 2) }}</td>
                        <td style="padding: 1rem;">{{ $categoria['descripcion'] }}</td>
                        <td style="padding: 1rem;">
                            <a href="{{ route('categorias.edit', $categoria['idCategoria']) }}" style="color: var(--accent-color); text-decoration: none; margin-right: 10px;">Editar</a>
                            <form action="{{ route('categorias.destroy', $categoria['idCategoria']) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color: var(--error-color); cursor:pointer; text-decoration: underline;">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 1rem; text-align: center;">No hay categorías registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
