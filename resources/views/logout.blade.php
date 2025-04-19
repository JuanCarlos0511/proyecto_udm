@extends('layouts.auth')

@section('title', 'Cerrar Sesión')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/logout.css') }}">
@endsection

@section('content')
<div class="logout-container">
    <div class="logout-modal">
        <h2>¿Deseas cerrar sesión?</h2>
        <p>Tu sesión actual se cerrará y tendrás que iniciar sesión nuevamente para acceder a tu cuenta.</p>
        
        <div class="logout-buttons">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Cerrar Sesión</button>
            </form>
            <a href="javascript:history.back()" class="cancel-btn">Cancelar</a>
        </div>
    </div>
</div>
@endsection
