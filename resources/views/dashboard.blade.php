@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        Hotel Capybara
    </a>
    <div class="nav-menu">
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="nav-link" style="background:none; border:none; cursor:pointer; font-size:1rem;">Cerrar Sesión</button>
        </form>
    </div>
</nav>

<div class="container dashboard-content">
    <div class="card">
        <div class="card-header">Bienvenido</div>
        <p>Has iniciado sesión correctamente en el sistema de gestión del hotel.</p>
        <p>Aquí podrás gestionar las entidades del sistema.</p>
    </div>

    <!-- Example of content -->
    <div class="card">
        <div class="card-header">Acciones Rápidas</div>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('reservas.index') }}" class="btn btn-primary" style="width: auto; text-align: center;">Reservas</a>
            <a href="{{ route('huespedes.index') }}" class="btn btn-primary" style="width: auto; text-align: center;">Huéspedes</a>
            <a href="{{ route('facturas.index') }}" class="btn btn-primary" style="width: auto; text-align: center;">Facturas</a>
            <a href="{{ route('servicios.index') }}" class="btn btn-primary" style="width: auto; text-align: center;">Servicios</a>
            <a href="{{ route('habitaciones.index') }}" class="btn btn-primary" style="width: auto; text-align: center;">Habitaciones</a>
            
            @if(Session::get('role') === 'Administrador')
                <a href="{{ route('categorias.index') }}" class="btn btn-primary" style="width: auto; text-align: center;">Categorías</a>
                <a href="{{ route('consumos.index') }}" class="btn btn-primary" style="width: auto; text-align: center;">Consumos</a>
                <a href="{{ route('roles.index') }}" class="btn btn-primary" style="width: auto; text-align: center;">Roles</a>
                <a href="{{ route('usuarios.index') }}" class="btn btn-primary" style="width: auto; text-align: center;">Usuarios</a>
            @endif
        </div>
    </div>
</div>
@endsection
