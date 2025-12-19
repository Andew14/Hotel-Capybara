@extends('layouts.app')

@section('title', 'Editar Huésped')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('huespedes.index') }}" class="nav-link">Volver</a>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header">Editar Huésped</div>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('huespedes.update', $huesped['idHuesped']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Nombre Completo</label>
                <input type="text" name="nombreCompleto" class="form-control" value="{{ $huesped['nombreCompleto'] }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Documento de Identidad</label>
                <input type="text" name="documentoIdentidad" class="form-control" value="{{ $huesped['documentoIdentidad'] }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ $huesped['telefono'] }}">
            </div>
            <div class="form-group">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" value="{{ $huesped['correo'] }}">
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Huésped</button>
        </form>
    </div>
</div>
@endsection
