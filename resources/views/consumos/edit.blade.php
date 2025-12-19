@extends('layouts.app')

@section('title', 'Editar Consumo')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('consumos.index') }}" class="nav-link">Volver</a>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header">Editar Consumo</div>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('consumos.update', $consumo['idConsumo']) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Reserva</label>
                <select name="idReserva" class="form-control" required>
                    <option value="">Seleccione una Reserva</option>
                    @foreach($reservas as $reserva)
                        <option value="{{ $reserva['idReserva'] }}" {{ $consumo['idReserva'] == $reserva['idReserva'] ? 'selected' : '' }}>
                            Reserva #{{ $reserva['idReserva'] }} - {{ $reserva['huesped']['nombreCompleto'] ?? 'Huésped' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Servicio</label>
                <select name="idServicio" class="form-control" required>
                    <option value="">Seleccione un Servicio</option>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio['idServicio'] }}" {{ $consumo['idServicio'] == $servicio['idServicio'] ? 'selected' : '' }}>
                            {{ $servicio['nombre'] }} - ${{ number_format($servicio['precio'], 2) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Cantidad</label>
                <input type="number" name="cantidad" class="form-control" value="{{ $consumo['cantidad'] }}" min="1" required>
            </div>

            <div class="form-group">
                <label class="form-label">Fecha Consumo</label>
                <input type="datetime-local" name="fechaConsumo" class="form-control" value="{{ \Carbon\Carbon::parse($consumo['fechaConsumo'])->format('Y-m-d\TH:i') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Consumo</button>
        </form>
    </div>
</div>
@endsection
