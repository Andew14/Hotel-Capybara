@extends('layouts.app')

@section('title', 'Facturas')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        <a href="{{ route('reservas.index') }}" class="nav-link">Reservas</a>
        <a href="{{ route('huespedes.index') }}" class="nav-link">Huéspedes</a>
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
            <span>Lista de Facturas</span>
            <a href="{{ route('facturas.create') }}" class="btn btn-primary" style="width: auto;">Nueva Factura</a>
        </div>

        @if(session('success'))
            <div class="alert" style="background-color: var(--success-color); color: #fff;">{{ session('success') }}</div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-color);">
                <thead>
                    <tr style="background-color: rgba(255,255,255,0.05); text-align: left;">
                        <th style="padding: 1rem;">ID</th>
                        <th style="padding: 1rem;">Reserva #</th>
                        <th style="padding: 1rem;">Fecha Emisión</th>
                        <th style="padding: 1rem;">Monto Total</th>
                        @if(Session::get('role') === 'Administrador')
                        <th style="padding: 1rem;">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturas as $factura)
                    <tr style="border-bottom: 1px solid #2d3748;">
                        <td style="padding: 1rem;">{{ $factura['idFactura'] }}</td>
                        <td style="padding: 1rem;">{{ $factura['idReserva'] }}</td>
                        <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($factura['fechaEmision'])->format('d/m/Y') }}</td>
                        <td style="padding: 1rem;">${{ number_format($factura['montoTotal'], 2) }}</td>
                        @if(Session::get('role') === 'Administrador')
                        <td style="padding: 1rem;">
                            <!-- View details or print could go here -->
                            <!-- View details or print could go here -->
                                <a href="{{ route('facturas.edit', $factura['idFactura']) }}" style="color: var(--accent-color); text-decoration: none; margin-right: 10px;">Editar</a>
                                <form action="{{ route('facturas.destroy', $factura['idFactura']) }}" method="POST" style="display:inline;" onsubmit="return alertDelete(event, '¿Estás seguro de eliminar esta factura?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none; border:none; color: var(--error-color); cursor:pointer; text-decoration: underline;">Eliminar</button>
                                </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Session::get('role') === 'Administrador' ? 5 : 4 }}" style="padding: 1rem; text-align: center;">No hay facturas registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
