@extends('layouts.app')

@section('title', 'Transaksi Penjualan - SmartThrift')

@section('content')
<div class="header-bar">
    <a href="{{ route('seller.dashboard') }}" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </a>
    <div class="title">Penjualan Toko</div>
    <div style="width: 24px;"></div>
</div>

<div class="app-content">

    <div style="margin-top: 10px;">
        @forelse($orders as $order)
            <div class="section-card" style="margin-bottom: 15px; border: 1px solid rgba(255,255,255,0.05);">
                <!-- Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 8px;">
                    <span style="font-size: 0.8rem; color: var(--text-muted);">
                        Order #ST-{{ $order->id }}
                    </span>
                    <span class="status-badge {{ $order->status }}">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </div>

                <!-- Product -->
                <div style="display: flex; gap: 12px; margin-bottom: 12px;">
                    @if($order->product->image_path)
                        <img src="{{ asset($order->product->image_path) }}" alt="{{ $order->product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60';">
                    @else
                        <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                    @endif
                    <div style="flex: 1;">
                        <h4 style="font-size: 0.95rem; font-weight: 600;">{{ $order->product->name }}</h4>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">
                            Harga: Rp {{ number_format($order->price, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Buyer info -->
                <div style="background-color: var(--bg-primary); padding: 10px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 12px;">
                    <div><strong>Pembeli:</strong> {{ $order->buyer->name }} (@ {{ $order->buyer->username }})</div>
                    <div style="margin-top: 4px;"><strong>Telepon:</strong> {{ $order->shipping_phone }}</div>
                    <div style="margin-top: 4px;"><strong>Alamat:</strong> {{ $order->shipping_address }}</div>
                </div>

                <!-- Actions -->
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('chat.show', ['userId' => $order->buyer_id, 'product_id' => $order->product_id]) }}" class="btn-secondary" style="font-size: 0.8rem; padding: 8px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        Hubungi Pembeli
                    </a>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 50px 0; color: var(--text-muted);">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.6;">
                    <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                </svg>
                <p>Belum ada produk Anda yang terjual.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
