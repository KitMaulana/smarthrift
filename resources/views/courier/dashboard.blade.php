@extends('layouts.app')

@section('title', 'Dashboard Kurir - SmartThrift')

@section('content')
<!-- Header Bar -->
<div class="header-bar" style="border-bottom: none;">
    <div class="title" style="letter-spacing: 0.1em; text-align: left;">Pengiriman Kurir</div>
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

    <!-- Store Info Header -->
    <div class="profile-header-card" style="margin-bottom: 20px; background-color: var(--bg-secondary);">
        <div class="profile-large-avatar" style="background-color: #FFDDD2; color: #333333;">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="profile-username">{{ Auth::user()->name }}</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: -8px;">@ {{ Auth::user()->username }} &bull; Role: Kurir Mitra</div>
    </div>

    <!-- Statistics -->
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-label">Pending / Kirim</div>
            <div class="stat-value" style="color: var(--warning);">{{ $pendingCount }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Selesai Antar</div>
            <div class="stat-value" style="color: var(--success);">{{ $completedCount }}</div>
        </div>
    </div>

    <!-- Deliveries List -->
    <div class="section-card">
        <div class="section-title">Daftar Tugas Pengantaran</div>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            @forelse($deliveries as $order)
                <div style="background-color: var(--bg-primary); padding: 15px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 6px;">
                        <span style="font-size: 0.8rem; font-weight: bold; color: var(--text-muted);">#ST-{{ $order->id }}</span>
                        <span class="status-badge {{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</span>
                    </div>

                    <!-- Item Detail -->
                    <div style="font-size: 0.9rem; margin-bottom: 8px;">
                        <strong>Barang:</strong> {{ $order->product->name }} (Rp {{ number_format($order->price, 0, ',', '.') }})
                    </div>
                    
                    <!-- Destination Address -->
                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 4px;">
                        <strong>Penerima:</strong> {{ $order->buyer->name }}
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 4px;">
                        <strong>Telepon:</strong> {{ $order->shipping_phone }}
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px; line-height: 1.4;">
                        <strong>Alamat Kirim:</strong> {{ $order->shipping_address }}
                    </div>

                    <!-- Action buttons based on status -->
                    @if($order->status === 'pending_shipping_approval')
                        <div style="font-size: 0.85rem; color: var(--warning); font-style: italic; text-align: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 8px;">
                            Menunggu Persetujuan Admin untuk Pengiriman
                        </div>
                    @elseif($order->status === 'pending_shipping')
                        <form action="{{ route('courier.update_status', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="shipped">
                            <button type="submit" class="btn-primary" style="background-color: var(--info); font-size: 0.85rem; padding: 10px; text-transform: none; margin-bottom: 0;">
                                Ambil Barang & Mulai Kirim
                            </button>
                        </form>
                    @elseif($order->status === 'shipped')
                        <form action="{{ route('courier.update_status', $order->id) }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 10px;">
                            @csrf
                            <input type="hidden" name="status" value="delivered">
                            <div>
                                <label class="form-label" style="font-size: 0.8rem; margin-bottom: 4px; color: var(--text-muted);">Bukti Pengiriman (Foto/Screenshot)</label>
                                <input type="file" name="delivery_proof" class="form-input" style="background-color: var(--bg-darker); color: white; border: 1px solid rgba(255,255,255,0.1); padding: 8px; font-size: 0.85rem;" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn-primary" style="background-color: var(--success); font-size: 0.85rem; padding: 10px; text-transform: none; margin-bottom: 0;">
                                Kirim Bukti & Tandai Tiba
                            </button>
                        </form>
                    @else
                        @if($order->delivery_proof)
                            <div style="margin-top: 8px; margin-bottom: 8px; text-align: center;">
                                <img src="{{ asset($order->delivery_proof) }}" alt="Bukti Kirim" style="max-width: 100%; max-height: 100px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1); object-fit: contain;">
                            </div>
                        @endif
                        <div style="font-size: 0.8rem; color: var(--success); font-style: italic; text-align: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 8px;">
                            Selesai &bull; Barang telah diantarkan.
                        </div>
                    @endif
                </div>
            @empty
                <div style="text-align: center; padding: 30px 0; color: var(--text-muted); font-size: 0.9rem;">
                    Belum ada tugas pengiriman yang ditugaskan kepada Anda.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
