@extends('layouts.app')

@section('title', 'Beli ' . $product->name . ' - SmartThrift')

@section('content')

<div class="header-bar">
    <a href="javascript:history.back()" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </a>
    <div class="title">Beli Barang</div>
    <div style="width: 24px;"></div>
</div>

<div class="app-content">

    <!-- Product Summary Card -->
    <div class="chat-product-card" style="margin-top: 10px;">
        @if($product->image_path)
            <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="chat-product-image" style="width: 60px; height: 60px;" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60';">
        @else
            <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60" class="chat-product-image" style="width: 60px; height: 60px;">
        @endif
        <div class="chat-product-details">
            <div class="chat-product-name" style="font-size: 1rem;">{{ $product->name }}</div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 4px;">Penjual: {{ $product->seller->name }}</div>
            <div class="chat-product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        </div>
    </div>

    <form action="{{ route('buyer.checkout', $product->id) }}" method="POST">
        @csrf

        <!-- Rincian Biaya -->
        <div class="section-card">
            <div class="section-title">Rincian Biaya</div>
            <div class="list-item">
                <span>Harga Barang</span>
                <span>Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            </div>
            <div class="list-item">
                <span>Biaya Layanan</span>
                <span>Rp {{ number_format($product->service_fee, 0, ',', '.') }}</span>
            </div>
            <div class="list-item" style="border-top: 1px dashed rgba(255,255,255,0.2); margin-top: 10px; padding-top: 15px; font-weight: bold; font-size: 1.1rem;">
                <span>Total Pembayaran</span>
                <span>Rp {{ number_format($product->price + $product->service_fee, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Alamat Pengiriman -->
        <div class="section-card">
            <div class="section-title">Informasi Pengiriman</div>
            
            <div class="form-group">
                <label class="form-label">Nomor Telepon Penerima</label>
                <input type="text" name="shipping_phone" class="form-input" value="{{ old('shipping_phone', $user->phone) }}" placeholder="Contoh: 08XXXXXXXX" required>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Alamat Pengiriman Lengkap</label>
                <textarea name="shipping_address" class="form-textarea" placeholder="Tulis jalan, nomor rumah, RT/RW, kecamatan, kota..." required>{{ old('shipping_address', $user->address) }}</textarea>
            </div>
        </div>

        <!-- Metode Pembayaran -->
        <div class="section-card">
            <div class="section-title">Metode Pembayaran</div>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                
                @if($product->payment_method === 'qris' || $product->payment_method === 'both')
                    <label style="display: flex; align-items: center; justify-content: space-between; background-color: var(--card-bg); padding: 15px; border-radius: 12px; cursor: pointer; border: 1px solid rgba(255,255,255,0.1);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="payment-badge" style="background-color: white; color: black;">QRIS</div>
                            <span style="font-weight: 500;">QRIS SmartThrift</span>
                        </div>
                        <input type="radio" name="payment_method" value="qris" checked style="width: 20px; height: 20px; accent-color: var(--accent-red);">
                    </label>
                @endif

                @if($product->payment_method === 'cod' || $product->payment_method === 'both')
                    <label style="display: flex; align-items: center; justify-content: space-between; background-color: var(--card-bg); padding: 15px; border-radius: 12px; cursor: pointer; border: 1px solid rgba(255,255,255,0.1);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="payment-badge" style="background-color: white; color: black;">COD</div>
                            <span style="font-weight: 500;">Bayar di Tempat (COD)</span>
                        </div>
                        <input type="radio" name="payment_method" value="cod" {{ ($product->payment_method === 'cod') ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: var(--accent-red);">
                    </label>
                @endif

            </div>
        </div>

        <!-- Checkout Button -->
        <div style="margin-top: 25px; margin-bottom: 10px;">
            <button type="submit" class="btn-primary">Bayar Sekarang</button>
        </div>
    </form>

</div>
@endsection
