@extends('layouts.app')

@section('title', 'Daftar Akun Baru - SmartThrift')

@section('content')


<div class="header-bar">
    <a href="{{ route('welcome') }}" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        <span style="margin-left: 5px;">Kembali</span>
    </a>
    <div class="title" style="visibility: hidden;">Daftar</div>
    <div style="width: 24px;"></div>
</div>

<div class="app-content" style="padding-bottom: 110px;">
    
    <div class="welcome-logo-container" style="margin-top: 10px; margin-bottom: 20px;">
        <img src="{{ asset('images/logo.png') }}" class="welcome-logo-img" alt="SmartThrift">
    </div>

    <h3 style="text-align: center; margin-bottom: 20px; font-weight: 600;">Daftar Akun Baru</h3>

    <form action="{{ route('register') }}" method="POST" style="width: 100%; max-width: 320px; margin: 0 auto;">
        @csrf
        
        <div class="form-group">
            <input type="text" name="name" class="form-input" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <input type="text" name="username" class="form-input" placeholder="Nama Pengguna (username)" value="{{ old('username') }}" required>
        </div>

        <div class="form-group">
            <input type="email" name="email" class="form-input" placeholder="Alamat Email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <input type="password" name="password" class="form-input" placeholder="Kata Sandi" required>
        </div>

        <div class="form-group" style="margin-bottom: 30px;">
            <label class="form-label" style="color: var(--text-primary);">Daftar Sebagai:</label>
            <select name="role" class="form-select" style="background-color: white; color: black; border-radius: 12px; height: 50px;" required>
                <option value="pelanggan">Pelanggan (Pembeli)</option>
                <option value="penjual">Penjual (Toko)</option>
            </select>
        </div>

        <button type="submit" class="btn-primary">DAFTAR</button>
    </form>

</div>
@endsection
