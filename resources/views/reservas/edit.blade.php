@extends('layouts.app')

@section('title', 'Editar Reserva')

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
        <div class="card-header">Editar Reserva #{{ $reserva['idReserva'] }}</div>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('reservas.update', $reserva['idReserva']) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Huésped</label>
                <select name="idHuesped" class="form-control" required>
                    <option value="">Seleccione un Huésped</option>
                    @foreach($huespedes as $huesped)
                        <option value="{{ $huesped['idHuesped'] }}" {{ $reserva['idHuesped'] == $huesped['idHuesped'] ? 'selected' : '' }}>
                            {{ $huesped['nombreCompleto'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Habitación</label>
                <select name="idHabitacion" class="form-control" required>
                    <option value="">Seleccione una Habitación</option>
                    @foreach($habitaciones as $habitacion)
                        <option value="{{ $habitacion['idHabitacion'] }}" {{ $reserva['idHabitacion'] == $habitacion['idHabitacion'] ? 'selected' : '' }}>
                            Habitación {{ $habitacion['numeroHabitacion'] ?? $habitacion['numero'] ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Fecha Entrada</label>
                <input type="datetime-local" name="fechaEntrada" class="form-control" value="{{ \Carbon\Carbon::parse($reserva['fechaEntrada'])->format('Y-m-d\TH:i') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Fecha Salida</label>
                <input type="datetime-local" name="fechaSalida" class="form-control" value="{{ \Carbon\Carbon::parse($reserva['fechaSalida'])->format('Y-m-d\TH:i') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Estado</label>
                <select name="idEstadoReserva" class="form-control">
                    <!-- Hardcoded states for example -->
                    <option value="1" {{ $reserva['idEstadoReserva'] == 1 ? 'selected' : '' }}>Pendiente</option>
                    <option value="2" {{ $reserva['idEstadoReserva'] == 2 ? 'selected' : '' }}>Confirmada</option>
                    <option value="3" {{ $reserva['idEstadoReserva'] == 3 ? 'selected' : '' }}>Cancelada</option>
                    <option value="4" {{ $reserva['idEstadoReserva'] == 4 ? 'selected' : '' }}>Finalizada</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3">{{ $reserva['observaciones'] }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Reserva</button>
        </form>
    </div>
</div>
@endsection
