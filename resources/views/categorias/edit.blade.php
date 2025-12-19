@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('categorias.index') }}" class="nav-link">Volver</a>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header">Editar Categoría</div>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('categorias.update', $categoria['idCategoria']) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ $categoria['nombre'] }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Precio Base</label>
                <input type="number" step="0.01" name="precioBase" class="form-control" value="{{ $categoria['precioBase'] }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3">{{ $categoria['descripcion'] }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Categoría</button>
        </form>
    </div>
</div>
@endsection
