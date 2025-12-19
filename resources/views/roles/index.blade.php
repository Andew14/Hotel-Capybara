@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        <a href="{{ route('usuarios.index') }}" class="nav-link">Usuarios</a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="nav-link" style="background:none; border:none; cursor:pointer; font-size:1rem;">Salir</button>
        </form>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span>Lista de Roles</span>
            @if(Session::get('role') === 'Administrador')
            <a href="{{ route('roles.create') }}" class="btn btn-primary" style="width: auto;">Nuevo Rol</a>
            @endif
        </div>

        @if(session('success'))
            <div class="alert" style="background-color: var(--success-color); color: #fff;">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-color);">
                <thead>
                    <tr style="background-color: rgba(255,255,255,0.05); text-align: left;">
                        <th style="padding: 1rem;">ID</th>
                        <th style="padding: 1rem;">Nombre</th>
                        @if(Session::get('role') === 'Administrador')
                        <th style="padding: 1rem;">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $rol)
                    <tr style="border-bottom: 1px solid #2d3748;">
                        <td style="padding: 1rem;">{{ $rol['idRol'] }}</td>
                        <td style="padding: 1rem;">{{ $rol['nombre'] }}</td>
                        @if(Session::get('role') === 'Administrador')
                        <td style="padding: 1rem;">
                            <a href="{{ route('roles.edit', $rol['idRol']) }}" style="color: var(--accent-color); text-decoration: none; margin-right: 10px;">Editar</a>
                            <form action="{{ route('roles.destroy', $rol['idRol']) }}" method="POST" style="display:inline;" onsubmit="return alertDelete(event, '¿Estás seguro de eliminar este rol?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color: var(--error-color); cursor:pointer; text-decoration: underline;">Eliminar</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="padding: 1rem; text-align: center;">No hay roles registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
