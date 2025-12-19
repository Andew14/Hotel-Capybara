@extends('layouts.app')

@section('title', 'Nuevo Usuario')

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
        <div class="card-header">Registrar Nuevo Usuario</div>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Correo Electrónico (Usuario)</label>
                <input type="email" name="correo" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Rol</label>
                <select name="rolNombre" class="form-control" required>
                    <option value="">Seleccione un Rol</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol['nombre'] }}">{{ $rol['nombre'] }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Registrar Usuario</button>
        </form>
    </div>
</div>
@endsection
