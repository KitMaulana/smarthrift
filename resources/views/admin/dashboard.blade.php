@extends('layouts.app')

@section('title', 'Admin Panel - SmartThrift')

@section('content')
<!-- Header Bar -->
<div class="header-bar" style="border-bottom: none;">
    <div class="title" style="letter-spacing: 0.1em; text-align: left;">Admin Panel</div>
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

    <!-- Admin profile card -->
    <div class="profile-header-card" style="margin-bottom: 20px; background-color: var(--bg-secondary);">
        <div class="profile-large-avatar" style="background-color: #006D77;">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="profile-username">{{ Auth::user()->name }}</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: -8px;">Administrator Utama</div>
    </div>

    <!-- Admin Statistics -->
    <div class="dashboard-stats">
        <div class="stat-card" style="grid-column: span 2;">
            <div class="stat-label">Total Penjualan Platform</div>
            <div class="stat-value" style="color: var(--success);">
                Rp {{ number_format($totalSales, 0, ',', '.') }}
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Pengguna</div>
            <div class="stat-value">{{ $userCount }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pakaian Terdaftar</div>
            <div class="stat-value">{{ $productCount }}</div>
        </div>
    </div>

    <!-- Navigation Menu Buttons -->
    <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 25px;">
        <a href="{{ route('admin.users') }}" class="btn-primary" style="text-transform: none; display: flex; align-items: center; justify-content: center; gap: 10px; background-color: #4EA8DE;">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            Kelola Pengguna & Role
        </a>
        <a href="{{ route('admin.products') }}" class="btn-primary" style="text-transform: none; display: flex; align-items: center; justify-content: center; gap: 10px; background-color: #F7B538; color: black;">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="9" x2="15" y1="9" y2="9"/><line x1="9" x2="15" y1="13" y2="13"/><line x1="9" x2="13" y1="17" y2="17"/>
            </svg>
            Kelola Iklan Pakaian
        </a>
        <a href="{{ route('admin.transactions') }}" class="btn-primary" style="text-transform: none; display: flex; align-items: center; justify-content: center; gap: 10px; background-color: var(--accent-red);">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/>
            </svg>
            Seluruh Transaksi Penjualan
        </a>
    </div>

    <!-- Pengaturan DANA Admin -->
    <div class="section-card" style="margin-bottom: 20px;">
        <div class="section-title">Pengaturan Nomor DANA Admin</div>
        <form action="{{ route('admin.update_dana') }}" method="POST" style="display: flex; gap: 10px; align-items: flex-end;">
            @csrf
            <div style="flex: 1;">
                <label class="form-label" style="margin-bottom: 4px; font-size: 0.8rem; color: var(--text-muted);">Nomor DANA Admin</label>
                <input type="text" name="dana_number" value="{{ $danaNumber }}" class="form-input" style="padding: 10px; font-size: 0.9rem;" placeholder="Contoh: 08XXXXXXXX" required>
            </div>
            <button type="submit" class="btn-primary" style="width: auto; padding: 10px 20px; font-size: 0.85rem; text-transform: none; margin-bottom: 0; border-radius: 8px;">Simpan</button>
        </form>
    </div>

    <!-- Pesanan Menunggu Penyelesaian -->
    @if(count($deliveredOrders) > 0)
        <div class="section-card" style="margin-bottom: 20px; border: 1px solid var(--success);">
            <div class="section-title" style="color: var(--success); display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                <span>Pesanan Menunggu Penyelesaian</span>
                <span class="status-badge delivered" style="font-size: 0.7rem; padding: 2px 6px;">{{ count($deliveredOrders) }} Baru</span>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @foreach($deliveredOrders as $order)
                    <div style="background-color: var(--bg-darker); padding: 12px; border-radius: 8px; font-size: 0.85rem; display: flex; justify-content: space-between; align-items: center; border: 1px solid rgba(255,255,255,0.05);">
                        <div>
                            <strong>#ST-{{ $order->id }}</strong> &bull; {{ $order->product->name }}
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">
                                Kurir: {{ $order->courier->name }} &bull; Pembeli: {{ $order->buyer->name }}
                            </div>
                        </div>
                        <form action="{{ route('admin.complete_order', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary" style="background-color: var(--success); padding: 6px 12px; font-size: 0.75rem; text-transform: none; border-radius: 6px; width: auto; margin-bottom: 0;">
                                Selesaikan
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recent Transactions -->
    <div class="section-card">
        <div class="section-title">Transaksi Terbaru</div>
        <div style="display: flex; flex-direction: column; gap: 8px;">
            @forelse($latestOrders as $ord)
                <div class="list-item" style="font-size: 0.85rem;">
                    <div>
                        <strong>#{{ $ord->id }}</strong> &bull; {{ $ord->product->name }}
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Pembeli: {{ $ord->buyer->name }}</div>
                    </div>
                    <div>
                        <span class="status-badge {{ $ord->status }}" style="font-size: 0.7rem; padding: 2px 6px;">
                            {{ $ord->status }}
                        </span>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 15px 0; color: var(--text-muted); font-size: 0.85rem;">
                    Belum ada transaksi.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Users -->
    <div class="section-card">
        <div class="section-title">Registrasi Terkini</div>
        <div style="display: flex; flex-direction: column; gap: 8px;">
            @foreach($latestUsers as $usr)
                <div class="list-item" style="font-size: 0.85rem;">
                    <div>
                        <strong>{{ $usr->name }}</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">@ {{ $usr->username }} &bull; {{ $usr->email }}</div>
                    </div>
                    <div>
                        <span class="status-badge role-{{ $usr->role }}" style="font-size: 0.7rem; padding: 2px 6px;">
                            {{ $usr->role }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
