@extends('layouts.app')

@section('title', 'Profil Saya - SmartThrift')

@section('content')
<!-- Header Bar -->
<div class="header-bar" style="border-bottom: none;">
    <div class="title" style="letter-spacing: 0.1em; text-align: left;">Saya</div>
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="action-icon" style="background: none; border: none; cursor: pointer; color: var(--text-primary);">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>
            </svg>
        </button>
    </form>
</div>

<div class="app-content">

    <!-- Top User Card (Matches Screenshot Page 7) -->
    <div class="profile-header-card">
        <div class="profile-large-avatar">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="profile-username">{{ $user->name }}</div>
        
        <a href="{{ route('buyer.edit_profile') }}" class="btn-secondary" style="padding: 8px 16px; font-size: 0.85rem; width: auto; border-radius: 20px; display: inline-flex; align-items: center; justify-content: center; border-color: rgba(255,255,255,0.4);">
            <!-- Pencil SVG -->
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                <path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>
            </svg>
            Ubah Profil
        </a>
    </div>

    <!-- Purchases Button Link -->
    <div style="margin-bottom: 25px;">
        <a href="{{ route('buyer.purchases') }}" class="btn-primary" style="text-transform: none; display: flex; align-items: center; justify-content: center; gap: 10px; background-color: #52B788;">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
            Riwayat Pembelian Saya
        </a>
    </div>

    <!-- Profile Fields List (Matches Screenshot Page 7 colors and titles) -->
    <div class="profile-info-box">
        
        <!-- Alamat Saya -->
        <div class="profile-info-field">
            <div class="profile-info-label">Alamat Saya</div>
            <div class="profile-info-value {{ empty($user->address) ? 'empty' : '' }}">
                {{ $user->address ?: 'Belum diisi' }}
            </div>
        </div>

        <!-- Nomor Telepon -->
        <div class="profile-info-field">
            <div class="profile-info-label">Nomor Telepon</div>
            <div class="profile-info-value {{ empty($user->phone) ? 'empty' : '' }}">
                {{ $user->phone ?: 'Belum diisi' }}
            </div>
        </div>

        <!-- Jenis Kelamin -->
        <div class="profile-info-field">
            <div class="profile-info-label">Jenis Kelamin</div>
            <div class="profile-info-value {{ empty($user->gender) ? 'empty' : '' }}">
                {{ $user->gender ?: 'Belum diisi' }}
            </div>
        </div>

        <!-- Tanggal Lahir -->
        <div class="profile-info-field">
            <div class="profile-info-label">Tanggal Lahir</div>
            <div class="profile-info-value {{ empty($user->dob) ? 'empty' : '' }}">
                {{ $user->dob ? $user->dob->format('d-m-Y') : 'Belum diisi' }}
            </div>
        </div>

    </div>

</div>
@endsection
