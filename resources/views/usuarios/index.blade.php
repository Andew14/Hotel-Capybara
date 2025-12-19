@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        <span class="nav-link">|</span>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="nav-link" style="background:none; border:none; cursor:pointer; font-size:1rem;">Cerrar Sesión</button>
        </form>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span>Lista de Usuarios</span>
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary" style="width: auto;">Nuevo Usuario</a>
        </div>
        
        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-color);">
                <thead>
                    <tr style="background-color: rgba(255,255,255,0.05); text-align: left;">
                        <th style="padding: 1rem; border-bottom: 1px solid #4a5568;">ID</th>
                        <th style="padding: 1rem; border-bottom: 1px solid #4a5568;">Nombre Completo</th>
                        <th style="padding: 1rem; border-bottom: 1px solid #4a5568;">Correo</th>
                        <th style="padding: 1rem; border-bottom: 1px solid #4a5568;">Rol</th>
                        <th style="padding: 1rem; border-bottom: 1px solid #4a5568;">Estado</th>
                        <th style="padding: 1rem; border-bottom: 1px solid #4a5568;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                    <tr style="border-bottom: 1px solid #2d3748;">
                        <td style="padding: 1rem;">{{ $usuario['idUsuario'] ?? $usuario['id'] }}</td>
                        <td style="padding: 1rem;">{{ $usuario['username'] ?? $usuario['nombreCompleto'] ?? 'N/A' }}</td>
                        <td style="padding: 1rem;">{{ $usuario['correo'] }}</td>
                        <td style="padding: 1rem;">
                            <span style="background-color: var(--accent-color); color: #1a202c; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.8rem; font-weight: bold;">
                                {{ $usuario['rol']['nombre'] ?? 'Sin Rol' }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            @if($usuario['estaActivo'])
                                <span style="color: var(--success-color);">Activo</span>
                            @else
                                <span style="color: var(--error-color);">Inactivo</span>
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            <a href="{{ route('usuarios.edit', $usuario['idUsuario'] ?? $usuario['id']) }}" style="color: var(--accent-color); text-decoration: none; margin-right: 10px;">Editar</a>
                            <form action="{{ route('usuarios.destroy', $usuario['idUsuario'] ?? $usuario['id']) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color: var(--error-color); cursor:pointer; text-decoration: underline;">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 1rem; text-align: center;">No hay usuarios registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
