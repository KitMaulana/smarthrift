@extends('layouts.app')

@section('title', 'Riwayat Pembelian - SmartThrift')

@section('styles')
<style>
.tracking-timeline {
    position: relative;
    padding-left: 20px;
    margin: 15px 0 10px 10px;
    border-left: 2px solid rgba(255,255,255,0.15);
    display: flex;
    flex-direction: column;
    gap: 15px;
}
.tracking-step {
    position: relative;
    font-size: 0.8rem;
    color: var(--text-muted);
    text-align: left;
}
.tracking-step.active {
    color: var(--text-primary);
}
.tracking-step.completed {
    color: var(--success);
}
.tracking-step::before {
    content: '';
    position: absolute;
    left: -27px;
    top: 3px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: var(--bg-secondary);
    border: 2px solid var(--bg-primary);
}
.tracking-step.active::before {
    background-color: var(--warning);
    border-color: var(--warning);
    box-shadow: 0 0 8px var(--warning);
}
.tracking-step.completed::before {
    background-color: var(--success);
    border-color: var(--success);
}
.step-title {
    font-size: 0.8rem;
    font-weight: 600;
}
.step-desc {
    font-size: 0.72rem;
    color: var(--text-muted);
    margin-top: 1px;
}
</style>
@endsection

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

                <!-- Tracking Timeline -->
                @if(in_array($order->status, ['pending_payment_confirmation', 'pending_shipping', 'shipped', 'delivered', 'completed']))
                    <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 12px; margin-top: 10px;">
                        <div style="font-size: 0.8rem; font-weight: bold; color: var(--text-muted); margin-bottom: 10px;">Lacak Pesanan:</div>
                        <div class="tracking-timeline">
                            <!-- Step 1: Dibuat -->
                            <div class="tracking-step completed">
                                <div class="step-title">Pesanan Dibuat & Bukti Pembayaran Dikirim</div>
                                <div class="step-desc">Pembayaran via DANA telah disubmit</div>
                            </div>
                            
                            <!-- Step 2: Verifikasi Pembayaran -->
                            @if($order->status === 'pending_payment_confirmation')
                                <div class="tracking-step active">
                                    <div class="step-title">Verifikasi Pembayaran</div>
                                    <div class="step-desc">Menunggu admin memeriksa bukti pembayaran</div>
                                </div>
                                <div class="tracking-step">
                                    <div class="step-title">Dalam Pengiriman</div>
                                </div>
                                <div class="tracking-step">
                                    <div class="step-title">Selesai</div>
                                </div>
                            @else
                                <div class="tracking-step completed">
                                    <div class="step-title">Pembayaran Terverifikasi</div>
                                    <div class="step-desc">Dikonfirmasi oleh Admin</div>
                                </div>
                            @endif

                            <!-- Step 3: Pengiriman -->
                            @if($order->status === 'pending_shipping')
                                <div class="tracking-step active">
                                    <div class="step-title">Menunggu Kurir</div>
                                    <div class="step-desc">Diproses untuk diserahkan ke kurir ({{ $order->courier->name ?? 'Kurir Mitra' }})</div>
                                </div>
                                <div class="tracking-step">
                                    <div class="step-title">Selesai</div>
                                </div>
                            @elseif($order->status === 'shipped')
                                <div class="tracking-step active">
                                    <div class="step-title">Dalam Pengiriman</div>
                                    <div class="step-desc">Sedang diantar oleh kurir ({{ $order->courier->name ?? 'Kurir Mitra' }})</div>
                                </div>
                                <div class="tracking-step">
                                    <div class="step-title">Selesai</div>
                                </div>
                            @elseif(in_array($order->status, ['delivered', 'completed']))
                                <div class="tracking-step completed">
                                    <div class="step-title">Barang Telah Tiba</div>
                                    <div class="step-desc">Diantar oleh kurir ({{ $order->courier->name ?? 'Kurir Mitra' }})</div>
                                    @if($order->delivery_proof)
                                        <div style="margin-top: 5px;">
                                            <a href="{{ asset($order->delivery_proof) }}" target="_blank" style="font-size: 0.72rem; color: var(--info); text-decoration: underline;">Lihat Bukti Foto Pengiriman</a>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Step 4: Selesai -->
                            @if($order->status === 'delivered')
                                <div class="tracking-step active">
                                    <div class="step-title">Menunggu Konfirmasi Akhir Admin</div>
                                    <div class="step-desc">Admin sedang memverifikasi penyelesaian pengiriman</div>
                                </div>
                            @elseif($order->status === 'completed')
                                <div class="tracking-step completed">
                                    <div class="step-title">Pesanan Selesai</div>
                                    <div class="step-desc">Transaksi selesai &bull; Terima kasih telah berbelanja!</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Action Buttons & Review / Testimony Section -->
                @if($order->status === 'completed')
                    @if($order->testimonial)
                        <!-- Display Submitted Testimonial -->
                        <div style="margin-top: 12px; background-color: var(--bg-darker); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05); font-size: 0.8rem;">
                            <div style="color: var(--warning); margin-bottom: 4px; font-weight: bold;">
                                Ulasan Anda: 
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $order->testimonial->rating ? '★' : '☆' }}
                                @endfor
                            </div>
                            <div style="color: var(--text-primary); font-style: italic; margin-top: 3px;">"{{ $order->testimonial->comment }}"</div>
                        </div>
                    @else
                        <!-- Form to Submit Testimonial -->
                        <div style="margin-top: 12px; background-color: var(--bg-darker); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                            <div style="font-size: 0.85rem; font-weight: bold; color: var(--text-muted); margin-bottom: 8px;">Berikan Testimoni & Ulasan:</div>
                            <form action="{{ route('buyer.store_testimonial', $order->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 8px;">
                                @csrf
                                <div>
                                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-bottom: 4px;">Rating (Bintang):</label>
                                    <select name="rating" class="form-select" style="padding: 6px; font-size: 0.8rem; background-color: var(--bg-primary); color: white; border: 1px solid rgba(255,255,255,0.1); width: auto;" required>
                                        <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                        <option value="4">⭐⭐⭐⭐ (4)</option>
                                        <option value="3">⭐⭐⭐ (3)</option>
                                        <option value="2">⭐⭐ (2)</option>
                                        <option value="1">⭐ (1)</option>
                                    </select>
                                </div>
                                <div>
                                    <textarea name="comment" class="form-textarea" style="padding: 8px; font-size: 0.8rem; height: 50px; background-color: var(--bg-primary); color: white; border: 1px solid rgba(255,255,255,0.1);" placeholder="Tulis ulasan Anda untuk SmartThrift..." required></textarea>
                                </div>
                                <button type="submit" class="btn-primary" style="padding: 8px 12px; font-size: 0.75rem; text-transform: none; border-radius: 6px; width: auto; align-self: flex-end; margin-bottom: 0;">
                                    Kirim Ulasan
                                </button>
                            </form>
                        </div>
                    @endif

                    <div style="margin-top: 10px;">
                        <form action="{{ route('buyer.request_return', $order->id) }}" method="POST" onsubmit="return confirm('Ajukan retur untuk pesanan ini?')" style="width: 100%;">
                            @csrf
                            <button type="submit" class="btn-secondary" style="padding: 8px; font-size: 0.85rem; text-transform: none; border-color: var(--accent-red); color: var(--accent-red); border-radius: 8px;">
                                Ajukan Retur Barang
                            </button>
                        </form>
                    </div>
                @elseif($order->status === 'delivered')
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
