@extends('layouts.app')

@section('title', 'Dashboard Penjual - SmartThrift')

@section('content')
<!-- Header Bar -->
<div class="header-bar" style="border-bottom: none;">
    <div class="title" style="letter-spacing: 0.1em; text-align: left;">Toko Saya</div>
    <div style="display: flex; align-items: center; gap: 15px;">
        <a href="{{ route('chat.index') }}" class="action-icon" style="color: var(--text-primary);">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
        </a>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="action-icon" style="background: none; border: none; cursor: pointer; color: var(--text-primary);">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>
                </svg>
            </button>
        </form>
    </div>
</div>

<div class="app-content">

    <!-- Store Info Header -->
    <div class="profile-header-card" style="margin-bottom: 20px; background-color: var(--bg-secondary);">
        <div class="profile-large-avatar" style="background-color: #83C5BE;">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="profile-username">{{ Auth::user()->name }}</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: -8px;">@ {{ Auth::user()->username }} &bull; Role: Penjual</div>
    </div>

    <!-- Seller Statistics -->
    <div class="dashboard-stats">
        <div class="stat-card" style="grid-column: span 2;">
            <div class="stat-label">Pendapatan Bersih</div>
            <div class="stat-value" style="color: var(--success); font-size: 1.6rem; margin-top: 5px;">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Produk Aktif</div>
            <div class="stat-value">{{ $activeCount }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Terjual</div>
            <div class="stat-value">{{ $soldCount }}</div>
        </div>
    </div>

    <!-- Navigation Menu Buttons -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 25px;">
        <a href="{{ route('seller.create_product') }}" class="btn-primary" style="text-transform: none; font-size: 0.9rem; padding: 12px 10px; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/>
            </svg>
            Mulai Jual
        </a>
        <a href="{{ route('seller.sales') }}" class="btn-secondary" style="font-size: 0.9rem; padding: 10px 10px; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
            </svg>
            Transaksi Penjualan
        </a>
    </div>

    <!-- Seller Listings -->
    <div class="section-card">
        <div class="section-title">Daftar Pakaian Saya</div>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            @forelse($products as $prod)
                <div class="list-item">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        @if($prod->image_path)
                            <img src="{{ asset($prod->image_path) }}" alt="{{ $prod->name }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px; background-color: var(--bg-darker);" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60';">
                        @else
                            <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                        @endif
                        <div>
                            <div style="font-weight: 500; font-size: 0.9rem;">{{ $prod->name }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">Rp {{ number_format($prod->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div>
                        @if($prod->status === 'available')
                            <span class="status-badge" style="background-color: var(--success); color: white;">
                                Tersedia
                            </span>
                        @elseif($prod->status === 'pending')
                            <span class="status-badge" style="background-color: #D68C45; color: white;">
                                Menunggu Persetujuan
                            </span>
                        @else
                            <span class="status-badge" style="background-color: #6C757D; color: white;">
                                Terjual
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 25px 0; color: var(--text-muted); font-size: 0.9rem;">
                    Anda belum mengunggah pakaian untuk dijual.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
