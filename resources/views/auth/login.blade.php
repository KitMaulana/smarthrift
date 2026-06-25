@extends('layouts.app')

@section('title', 'Masuk - SmartThrift')

@section('content')

<!-- Header Bar -->
<div class="header-bar">
    <a href="{{ route('welcome') }}" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        <span style="margin-left: 5px;">Kembali</span>
    </a>
    <div class="title" style="visibility: hidden;">Masuk</div>
    <div style="width: 24px;"></div>
</div>

<div class="app-content" style="padding-bottom: 90px; display: flex; flex-direction: column; justify-content: space-between; min-height: calc(100% - 60px);">
    
    <!-- Logo area -->
    <div class="welcome-logo-container" style="margin-top: 20px; margin-bottom: 20px;">
        <img src="{{ asset('images/logo.png') }}" class="welcome-logo-img" alt="SmartThrift">
    </div>

    <!-- Login Form -->
    <form action="{{ route('login') }}" method="POST" style="width: 100%; max-width: 320px; margin: 0 auto;">
        @csrf
        
        <!-- Username/Email input -->
        <div class="form-group">
            <input type="text" name="login" class="form-input" placeholder="Username atau Email" value="{{ old('login') }}" required autofocus>
            @if($errors->has('login'))
                <div style="color: #FFADAD; font-size: 0.8rem; margin-top: 6px;">{{ $errors->first('login') }}</div>
            @endif
        </div>

        <!-- Password input -->
        <div class="form-group" style="margin-bottom: 30px;">
            <input type="password" name="password" class="form-input" placeholder="Kata Sandi" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary" style="margin-bottom: 20px;">MASUK</button>
    </form>

    <!-- Bottom links -->
    <div style="text-align: center; margin-bottom: 20px;">
        <span style="color: var(--text-muted); font-size: 0.9rem;">Belum punya akun? </span>
        <a href="{{ route('register') }}" style="color: var(--text-primary); text-decoration: underline; font-size: 0.9rem; font-weight: 600;">Daftar di sini</a>
    </div>

</div>
@endsection
