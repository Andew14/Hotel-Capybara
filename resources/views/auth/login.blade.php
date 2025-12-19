@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="login-wrapper">
    <div class="login-card">
        <h1 class="login-title">
            <img src="https://img.icons8.com/color/48/capybara.png" alt="Logo" style="height: 50px; vertical-align: middle; margin-right: 10px;">
            Hotel Capybara
        </h1>
        <p class="login-subtitle">Bienvenido al paraíso natural</p>
        
        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username" class="form-label">Correo</label>
                <input type="text" name="username" id="username" class="form-control" required autofocus>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Ingresar</button>
        </form>
    </div>
</div>
@endsection
