@extends('layouts.app')

@section('title', 'Editar Factura')

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
        <div class="card-header">Editar Factura</div>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('facturas.update', $factura['idFactura']) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Reserva Asociada</label>
                <select name="idReserva" class="form-control" required>
                    <option value="">Seleccione una Reserva</option>
                    @foreach($reservas as $reserva)
                        <option value="{{ $reserva['idReserva'] }}" {{ $factura['idReserva'] == $reserva['idReserva'] ? 'selected' : '' }}>
                            Reserva #{{ $reserva['idReserva'] }} - {{ $reserva['huesped']['nombreCompleto'] ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Fecha de Emisión</label>
                <input type="datetime-local" name="fechaEmision" class="form-control" value="{{ \Carbon\Carbon::parse($factura['fechaEmision'])->format('Y-m-d\TH:i') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Monto Total</label>
                <input type="number" step="0.01" name="montoTotal" class="form-control" value="{{ $factura['montoTotal'] }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Factura</button>
        </form>
    </div>
</div>
@endsection
