@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('usuarios.index') }}" class="nav-link">Volver</a>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header">Editar Usuario</div>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('usuarios.update', $usuario['idUsuario'] ?? $usuario['id']) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Nombre Completo</label>
                <input type="text" name="nombreCompleto" class="form-control" value="{{ $usuario['nombreCompleto'] ?? '' }}">
            </div>

            <div class="form-group">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" value="{{ $usuario['correo'] }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Rol</label>
                <select name="idRol" class="form-control" required>
                    <option value="">Seleccione un Rol</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol['idRol'] }}" {{ ($usuario['rol']['idRol'] ?? '') == $rol['idRol'] ? 'selected' : '' }}>
                            {{ $rol['nombre'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Contraseña (Dejar en blanco para no cambiar)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
        </form>
    </div>
</div>
@endsection
