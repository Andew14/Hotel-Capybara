@extends('layouts.app')

@section('title', 'Consumos de Servicios')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        <a href="{{ route('servicios.index') }}" class="nav-link">Servicios</a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="nav-link" style="background:none; border:none; cursor:pointer; font-size:1rem;">Salir</button>
        </form>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span>Historial de Consumos</span>
            <a href="{{ route('consumos.create') }}" class="btn btn-primary" style="width: auto;">Registrar Consumo</a>
        </div>

        @if(session('success'))
            <div class="alert" style="background-color: var(--success-color); color: #fff;">{{ session('success') }}</div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-color);">
                <thead>
                    <tr style="background-color: rgba(255,255,255,0.05); text-align: left;">
                        <th style="padding: 1rem;">ID</th>
                        <th style="padding: 1rem;">Reserva</th>
                        <th style="padding: 1rem;">Servicio</th>
                        <th style="padding: 1rem;">Cantidad</th>
                        <th style="padding: 1rem;">Precio Unit.</th>
                        <th style="padding: 1rem;">Total</th>
                        <th style="padding: 1rem;">Fecha</th>
                        <th style="padding: 1rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consumos as $consumo)
                    <tr style="border-bottom: 1px solid #2d3748;">
                        <td style="padding: 1rem;">{{ $consumo['idConsumo'] }}</td>
                        <td style="padding: 1rem;">#{{ $consumo['idReserva'] }}</td>
                        <td style="padding: 1rem;">{{ $consumo['servicio']['nombre'] ?? 'N/A' }}</td>
                        <td style="padding: 1rem;">{{ $consumo['cantidad'] }}</td>
                        <td style="padding: 1rem;">${{ number_format($consumo['precioUnitario'], 2) }}</td>
                        <td style="padding: 1rem;">${{ number_format($consumo['cantidad'] * $consumo['precioUnitario'], 2) }}</td>
                        <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($consumo['fechaConsumo'])->format('d/m/Y H:i') }}</td>
                        <td style="padding: 1rem;">
                            <a href="{{ route('consumos.edit', $consumo['idConsumo']) }}" style="color: var(--accent-color); text-decoration: none; margin-right: 10px;">Editar</a>
                            <form action="{{ route('consumos.destroy', $consumo['idConsumo']) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este consumo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color: var(--error-color); cursor:pointer; text-decoration: underline;">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 1rem; text-align: center;">No hay consumos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
