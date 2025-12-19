@extends('layouts.app')

@section('title', 'Nueva Factura')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('facturas.index') }}" class="nav-link">Volver</a>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header">Generar Nueva Factura</div>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('facturas.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Reserva a Facturar</label>
                <select name="idReserva" class="form-control" required>
                    <option value="">Seleccione una Reserva</option>
                    @foreach($reservas as $reserva)
                        <option value="{{ $reserva['idReserva'] }}">
                            Reserva #{{ $reserva['idReserva'] }} - {{ $reserva['huesped']['nombreCompleto'] ?? 'Huésped' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Monto Total</label>
                <input type="number" step="0.01" name="montoTotal" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Fecha Emisión</label>
                <input type="datetime-local" name="fechaEmision" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Generar Factura</button>
        </form>
    </div>
</div>
@endsection
