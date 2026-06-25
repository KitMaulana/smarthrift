@extends('layouts.app')

@section('title', 'Selamat Datang di SmartThrift')

@section('content')
<div class="app-content" style="padding-bottom: 90px; display: flex; flex-direction: column; justify-content: space-between; align-items: center; min-height: 100%;">
    
    <!-- Header Logo -->
    <div class="welcome-logo-container">
        <img src="{{ asset('images/logo.png') }}" class="welcome-logo-img" alt="SmartThrift">
    </div>

    <!-- Middle content -->
    <div style="width: 100%; max-width: 320px;">
        <h2 class="welcome-title">Welcome to<br>SmartThrift</h2>

        <!-- Masuk dengan Email -->
        <a href="{{ route('login') }}" class="oauth-button" style="margin-bottom: 15px;">
            <svg class="oauth-icon" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
            </svg>
            <span class="oauth-text">Masuk dengan Email</span>
        </a>

        <!-- Daftar Akun Baru -->
        <a href="{{ route('register') }}" class="btn-primary" style="margin-bottom: 20px; text-align: center; display: block; text-decoration: none;">DAFTAR AKUN BARU</a>

        <!-- Divider -->
        <div style="text-align: center; color: var(--text-muted); font-size: 0.85rem; margin-bottom: 15px;">
            <span>Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--text-primary); text-decoration: underline; font-weight: 600;">Masuk di sini</a></span>
        </div>
    </div>

    <!-- Footer -->
    <div class="welcome-footer" style="position: relative; bottom: auto; margin-top: 20px;">
        By Twin Threads
    </div>

</div>
@endsection
