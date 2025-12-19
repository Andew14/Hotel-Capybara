@extends('layouts.app')

@section('title', 'Editar Habitación')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('habitaciones.index') }}" class="nav-link">Volver</a>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header">Editar Habitación</div>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('habitaciones.update', $habitacion['idHabitacion']) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Número de Habitación</label>
                <input type="text" name="numeroHabitacion" class="form-control" value="{{ $habitacion['numeroHabitacion'] }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Piso</label>
                <input type="number" name="piso" class="form-control" value="{{ $habitacion['piso'] }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Categoría</label>
                <select name="idCategoria" class="form-control" required>
                    <option value="">Seleccione una Categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria['idCategoria'] }}" {{ $habitacion['idCategoria'] == $categoria['idCategoria'] ? 'selected' : '' }}>
                            {{ $categoria['nombre'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="estaDisponible" value="1" {{ $habitacion['estaDisponible'] ? 'checked' : '' }}> Disponible
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Habitación</button>
        </form>
    </div>
</div>
@endsection
