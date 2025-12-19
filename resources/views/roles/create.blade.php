@extends('layouts.app')

@section('title', 'Nuevo Rol')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('roles.index') }}" class="nav-link">Volver</a>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header">Crear Nuevo Rol</div>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Nombre del Rol</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Rol</button>
        </form>
    </div>
</div>
@endsection
