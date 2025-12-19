@extends('layouts.app')

@section('title', 'Nueva Reserva')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('reservas.index') }}" class="nav-link">Volver</a>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header">Registrar Nueva Reserva</div>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('reservas.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Huésped</label>
                <select name="idHuesped" class="form-control" required>
                    <option value="">Seleccione un Huésped</option>
                    @foreach($huespedes as $huesped)
                        <option value="{{ $huesped['idHuesped'] }}">{{ $huesped['nombreCompleto'] }} ({{ $huesped['documentoIdentidad'] }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Habitación</label>
                <select name="idHabitacion" class="form-control" required>
                    <option value="">Seleccione una Habitación</option>
                    @foreach($habitaciones as $habitacion)
                        <option value="{{ $habitacion['idHabitacion'] }}">
                            Habitación {{ $habitacion['numeroHabitacion'] ?? $habitacion['numero'] ?? 'N/A' }} 
                            - {{ $habitacion['categoriaHabitacion']['nombre'] ?? $habitacion['tipo'] ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Fecha Entrada</label>
                <input type="datetime-local" name="fechaEntrada" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Fecha Salida</label>
                <input type="datetime-local" name="fechaSalida" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3"></textarea>
            </div>

            <!-- Assuming IdUsuario is handled by backend or hidden input if needed. 
                 For now, we'll omit it and hope backend takes from token or we might need to inject logged user ID -->
            
            <!-- Default state usually 'Pending' or 'Confirmed'. We'll pass 1 for now or let backend decide default -->
            <input type="hidden" name="idEstadoReserva" value="1"> 

            <button type="submit" class="btn btn-primary">Crear Reserva</button>
        </form>
    </div>
</div>
@endsection
