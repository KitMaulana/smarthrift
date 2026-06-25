@extends('layouts.app')

@section('title', 'Riwayat Pembelian - SmartThrift')

@section('content')
<div class="header-bar">
    <a href="{{ route('buyer.profile') }}" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </a>
    <div class="title">Pembelian Saya</div>
    <div style="width: 24px;"></div>
</div>

<div class="app-content">

    <div style="margin-top: 10px;">
        @forelse($orders as $order)
            <div class="section-card" style="margin-bottom: 15px; border: 1px solid rgba(255,255,255,0.05);">
                <!-- Order Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 8px;">
                    <span style="font-size: 0.8rem; color: var(--text-muted);">
                        ID Transaksi: #ST-{{ $order->id }}
                    </span>
                    <span class="status-badge {{ $order->status }}">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </div>

                <!-- Product row -->
                <div style="display: flex; gap: 12px; margin-bottom: 12px;">
                    @if($order->product->image_path)
                        <img src="{{ asset($order->product->image_path) }}" alt="{{ $order->product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; background-color: var(--bg-darker);" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60';">
                    @else
                        <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                    @endif
                    <div style="flex: 1;">
                        <h4 style="font-size: 0.95rem; font-weight: 600;">{{ $order->product->name }}</h4>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">
                            Penjual: {{ $order->product->seller->name }} &bull; Metode: <span style="text-transform: uppercase;">{{ $order->payment_method }}</span>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Courier Info -->
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 8px; margin-bottom: 10px;">
                    <div>
                        @if($order->courier)
                            <span style="color: var(--text-muted);">Kurir: <strong>{{ $order->courier->name }}</strong></span>
                        @else
                            <span style="color: var(--text-muted); font-style: italic;">Menunggu Kurir...</span>
                        @endif
                    </div>
                    <div style="font-weight: bold; font-size: 0.95rem;">
                        Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Action Buttons & Status Info -->
                @if($order->status === 'delivered')
                    <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 10px;">
                        <form action="{{ route('buyer.confirm_delivery', $order->id) }}" method="POST" style="width: 100%;">
                            @csrf
                            <button type="submit" class="btn-primary" style="padding: 10px; font-size: 0.85rem; text-transform: none; margin-bottom: 0;">
                                Konfirmasi Penerimaan Pesanan
                            </button>
                        </form>
                        <form action="{{ route('buyer.request_return', $order->id) }}" method="POST" onsubmit="return confirm('Ajukan retur untuk pesanan ini?')" style="width: 100%;">
                            @csrf
                            <button type="submit" class="btn-secondary" style="padding: 8px; font-size: 0.85rem; text-transform: none; border-color: var(--accent-red); color: var(--accent-red); border-radius: 8px;">
                                Ajukan Retur Barang
                            </button>
                        </form>
                    </div>
                @elseif($order->status === 'completed')
                    <div style="margin-top: 10px;">
                        <form action="{{ route('buyer.request_return', $order->id) }}" method="POST" onsubmit="return confirm('Ajukan retur untuk pesanan ini?')" style="width: 100%;">
                            @csrf
                            <button type="submit" class="btn-secondary" style="padding: 8px; font-size: 0.85rem; text-transform: none; border-color: var(--accent-red); color: var(--accent-red); border-radius: 8px;">
                                Ajukan Retur Barang
                            </button>
                        </form>
                    </div>
                @elseif($order->status === 'pending_return')
                    <div style="text-align: center; font-size: 0.8rem; color: #E29578; font-style: italic; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 8px; margin-top: 8px;">
                        Pengajuan retur Anda sedang menunggu persetujuan admin.
                    </div>
                @elseif($order->status === 'returned')
                    <div style="text-align: center; font-size: 0.8rem; color: #83C5BE; font-style: italic; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 8px; margin-top: 8px;">
                        Retur disetujui. Dana transaksi Anda telah dikembalikan.
                    </div>
                @elseif($order->status === 'return_rejected')
                    <div style="text-align: center; font-size: 0.8rem; color: var(--accent-red); font-style: italic; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 8px; margin-top: 8px;">
                        Pengajuan retur Anda ditolak oleh admin.
                    </div>
                @endif
            </div>
        @empty
            <div style="text-align: center; padding: 50px 0; color: var(--text-muted);">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.6;">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                <p>Anda belum pernah melakukan pembelian.</p>
                <a href="{{ route('explore') }}" style="color: var(--accent-red); text-decoration: underline; margin-top: 10px; display: inline-block;">Mulai Belanja</a>
            </div>
        @endforelse
    </div>

</div>
@endsection
