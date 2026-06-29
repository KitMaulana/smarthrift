@extends('layouts.app')

@section('title', 'Transaksi Platform - SmartThrift')

@section('content')
<div class="header-bar">
    <a href="{{ route('admin.dashboard') }}" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </a>
    <div class="title">Transaksi</div>
    <div style="width: 24px;"></div>
</div>

<div class="app-content">

    <div style="margin-top: 10px;">
        @forelse($orders as $order)
            <div class="section-card" style="margin-bottom: 12px; padding: 15px; border: 1px solid rgba(255,255,255,0.05);">
                <!-- Title & Status -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 6px;">
                    <span style="font-size: 0.8rem; font-weight: bold; color: var(--text-muted);">#ST-{{ $order->id }}</span>
                    <span class="status-badge {{ $order->status }}" style="font-size: 0.7rem; padding: 2px 6px;">{{ $order->status }}</span>
                </div>

                <!-- Product -->
                <div style="font-size: 0.9rem; margin-bottom: 6px;">
                    <strong>Pakaian:</strong> {{ $order->product->name }}
                </div>

                <!-- Buyer, Seller & Courier Info -->
                <div style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.4; margin-bottom: 8px;">
                    <div><strong>Pembeli:</strong> {{ $order->buyer->name }}</div>
                    <div><strong>Penjual:</strong> {{ $order->product->seller->name }}</div>
                    <div><strong>Kurir:</strong> {{ $order->courier ? $order->courier->name : 'Unassigned' }}</div>
                </div>

                <!-- Payment Details -->
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 8px; font-size: 0.85rem;">
                    <div>Metode: <strong style="text-transform: uppercase;">{{ $order->payment_method }}</strong></div>
                    <div>Total: <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></div>
                </div>

                <!-- Payment Receipt Proof -->
                @if($order->payment_receipt)
                    <div style="margin-top: 10px; padding: 10px; background-color: var(--bg-darker); border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 6px;"><strong>Bukti Transfer DANA Pembeli:</strong></div>
                        <a href="{{ asset($order->payment_receipt) }}" target="_blank">
                            <img src="{{ asset($order->payment_receipt) }}" alt="Bukti Transfer" style="max-width: 100%; max-height: 120px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1); object-fit: contain;">
                        </a>
                    </div>
                @endif

                <!-- Delivery Proof -->
                @if($order->delivery_proof)
                    <div style="margin-top: 10px; padding: 10px; background-color: var(--bg-darker); border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 6px;"><strong>Bukti Kirim Kurir:</strong></div>
                        <a href="{{ asset($order->delivery_proof) }}" target="_blank">
                            <img src="{{ asset($order->delivery_proof) }}" alt="Bukti Kirim" style="max-width: 100%; max-height: 120px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1); object-fit: contain;">
                        </a>
                    </div>
                @endif

                <!-- Admin Approvals Actions -->
                @if($order->status === 'pending_payment_confirmation')
                    <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 10px; margin-top: 10px;">
                        <form action="{{ route('admin.confirm_payment', $order->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 8px;">
                            @csrf
                            <div>
                                <label class="form-label" style="font-size: 0.75rem; margin-bottom: 4px; color: var(--text-muted);">Pilih Kurir Pengirim</label>
                                <select name="courier_id" class="form-select" style="padding: 8px; font-size: 0.8rem; height: auto; background-color: var(--bg-primary); color: white; border: 1px solid rgba(255,255,255,0.1);" required>
                                    <option value="">-- Pilih Kurir --</option>
                                    @foreach($couriers as $courier)
                                        <option value="{{ $courier->id }}">{{ $courier->name }} ({{ $courier->phone }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="display: flex; justify-content: flex-end;">
                                <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 0.8rem; text-transform: none; border-radius: 6px; width: auto; background-color: var(--success); margin-bottom: 0;">
                                    Konfirmasi & Teruskan ke Kurir
                                </button>
                            </div>
                        </form>
                    </div>
                @elseif($order->status === 'delivered')
                    <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 10px; margin-top: 10px; display: flex; justify-content: flex-end;">
                        <form action="{{ route('admin.complete_order', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 0.8rem; text-transform: none; border-radius: 6px; width: auto; background-color: var(--success); margin-bottom: 0;">
                                Selesaikan Pesanan
                            </button>
                        </form>
                    </div>
                @elseif($order->status === 'pending_shipping_approval')
                    <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 10px; margin-top: 10px; display: flex; justify-content: flex-end;">
                        <form action="{{ route('admin.approve_shipping', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary" style="padding: 6px 12px; font-size: 0.75rem; text-transform: none; border-radius: 6px; width: auto; background-color: var(--success); margin-bottom: 0;">
                                Setujui Pengiriman
                            </button>
                        </form>
                    </div>
                @elseif($order->status === 'pending_return')
                    <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 10px; margin-top: 10px; display: flex; gap: 10px; justify-content: flex-end;">
                        <form action="{{ route('admin.reject_return', $order->id) }}" method="POST" onsubmit="return confirm('Tolak pengajuan retur ini?')">
                            @csrf
                            <button type="submit" class="btn-primary" style="padding: 6px 12px; font-size: 0.75rem; text-transform: none; border-radius: 6px; width: auto; background-color: var(--accent-red); margin-bottom: 0;">
                                Tolak Retur
                            </button>
                        </form>
                        <form action="{{ route('admin.approve_return', $order->id) }}" method="POST" onsubmit="return confirm('Setujui pengajuan retur ini? Dana akan dikembalikan ke pembeli.')">
                            @csrf
                            <button type="submit" class="btn-primary" style="padding: 6px 12px; font-size: 0.75rem; text-transform: none; border-radius: 6px; width: auto; background-color: var(--success); margin-bottom: 0;">
                                Setujui Retur
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div style="text-align: center; padding: 40px 0; color: var(--text-muted);">
                <p>Belum ada transaksi di platform.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
