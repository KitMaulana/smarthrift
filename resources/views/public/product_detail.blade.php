@extends('layouts.app')

@section('title', $product->name . ' - SmartThrift')

@section('content')
<!-- Header Bar -->
<div class="header-bar">
    <a href="javascript:history.back()" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </a>
    <div class="title" style="visibility: hidden;">Detail</div>
    <div style="width: 24px;"></div>
</div>

<div class="app-content" style="padding-bottom: 95px;">
    
    <!-- Large Product Image -->
    <div class="detail-image-container">
        @if($product->image_path)
            @if(Str::startsWith($product->image_path, 'uploads/') || Str::startsWith($product->image_path, 'images/'))
                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="detail-image" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=500&auto=format&fit=crop&q=80';">
            @else
                <img src="{{ asset('uploads/' . $product->image_path) }}" alt="{{ $product->name }}" class="detail-image" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=500&auto=format&fit=crop&80';">
            @endif
        @else
            @php
                $fallbackUrl = 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=500&auto=format&fit=crop&q=80';
                if($product->category == 'aksesoris') {
                    if(Str::contains(strtolower($product->name), 'sepatu')) {
                        $fallbackUrl = 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&auto=format&fit=crop&q=80';
                    } else {
                        $fallbackUrl = 'https://images.unsplash.com/photo-1525507119028-ed4c629a60a3?w=500&auto=format&fit=crop&q=80';
                    }
                } elseif($product->category == 'bawahan') {
                    $fallbackUrl = 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=500&auto=format&fit=crop&q=80';
                }
            @endphp
            <img src="{{ $fallbackUrl }}" alt="{{ $product->name }}" class="detail-image">
        @endif
    </div>

    <!-- Product Title & Price -->
    <div class="detail-info-box">
        <h1 class="detail-title">{{ $product->name }}</h1>
        <div class="detail-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
    </div>

    <!-- Kategori Section (Glasses icon for Aksesoris) -->
    <div class="detail-section">
        <div class="detail-section-title">Kategori</div>
        <div class="category-badge">
            @if($product->category == 'aksesoris')
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px; vertical-align: middle;">
                    <circle cx="6" cy="15" r="4"/><circle cx="18" cy="15" r="4"/><path d="M14 15a2 2 0 0 0-4 0M2 15h1M21 15h1"/>
                </svg>
            @elseif($product->category == 'atasan')
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 6px; vertical-align: middle;">
                    <path d="M18 2h-3.41a3 3 0 0 0-5.18 0H6a2 2 0 0 0-2 2v3a2 2 0 0 0 1.22 1.84L7 9.5V20a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9.5l1.78-.66A2 2 0 0 0 20 7V4a2 2 0 0 0-2-2zM9.5 4a1.5 1.5 0 0 1 3 0v1h-3z"/>
                </svg>
            @else
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 6px; vertical-align: middle;">
                    <path d="M7 2h10a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-8h-2v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z"/>
                </svg>
            @endif
            <span style="text-transform: capitalize;">{{ $product->category }}</span>
        </div>
    </div>

    <!-- Deskripsi Section -->
    <div class="detail-section">
        <div class="detail-section-title">Deskripsi</div>
        <p style="font-size: 0.95rem; line-height: 1.5; color: var(--text-muted);">
            {{ $product->description }}
        </p>
    </div>

    <!-- Penjual Section -->
    <div class="detail-section">
        <div class="detail-section-title">Penjual</div>
        <div class="seller-profile-card">
            <div class="profile-avatar">
                {{ strtoupper(substr($product->seller->name, 0, 1)) }}
            </div>
            <div>
                <div class="seller-name">{{ $product->seller->name }}</div>
                <div class="seller-subtitle">@ {{$product->seller->username}} &bull; Penjual Terpercaya</div>
            </div>
        </div>
    </div>

    <!-- Metode Pembayaran Section -->
    <div class="detail-section" style="border-bottom: none;">
        <div class="detail-section-title">Metode Pembayaran</div>
        <div class="payment-methods-row" style="margin-bottom: 15px;">
            @if($product->payment_method === 'qris' || $product->payment_method === 'both')
                <div class="payment-badge">QRIS</div>
            @endif
            @if($product->payment_method === 'cod' || $product->payment_method === 'both')
                <div class="payment-badge">COD</div>
            @endif
        </div>

        <!-- Actions Row -->
        <div style="display: flex; gap: 10px; width: 100%; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 15px; margin-top: 15px;">
            @guest
                <a href="{{ route('login') }}" class="btn-secondary" style="font-size: 0.8rem; padding: 12px 5px; flex: 1; display: flex; align-items: center; justify-content: center; border-radius: 8px;">Chat Penjual</a>
                <a href="{{ route('login') }}" class="btn-secondary" style="font-size: 0.8rem; padding: 12px 5px; flex: 1; display: flex; align-items: center; justify-content: center; border-radius: 8px;">Chat Admin</a>
                <a href="{{ route('login') }}" class="btn-primary" style="font-size: 0.8rem; padding: 12px 5px; flex: 1; border-radius: 8px;">Beli</a>
            @else
                @if(Auth::id() === $product->seller_id)
                    @if($admin)
                        <a href="{{ route('chat.show', ['userId' => $admin->id, 'product_id' => $product->id]) }}" class="btn-secondary" style="font-size: 0.85rem; padding: 12px 15px; display: flex; align-items: center; justify-content: center; border-radius: 8px; width: 100%;">Chat Admin</a>
                    @endif
                    <div style="width: 100%; text-align: center; color: var(--text-muted); font-style: italic; font-size: 0.85rem; margin-top: 10px;">
                        Ini adalah pakaian Anda.
                    </div>
                @else
                    <a href="{{ route('chat.show', ['userId' => $product->seller_id, 'product_id' => $product->id]) }}" class="btn-secondary" style="font-size: 0.8rem; padding: 12px 5px; flex: 1; display: flex; align-items: center; justify-content: center; border-radius: 8px;">Chat Penjual</a>
                    @if($admin)
                        <a href="{{ route('chat.show', ['userId' => $admin->id, 'product_id' => $product->id]) }}" class="btn-secondary" style="font-size: 0.8rem; padding: 12px 5px; flex: 1; display: flex; align-items: center; justify-content: center; border-radius: 8px;">Chat Admin</a>
                    @else
                        <button onclick="alert('Admin tidak tersedia saat ini.')" class="btn-secondary" style="font-size: 0.8rem; padding: 12px 5px; flex: 1; border-radius: 8px;">Chat Admin</button>
                    @endif
                    
                    @if(Auth::user()->isPelanggan())
                        <a href="{{ route('buyer.checkout', $product->id) }}" class="btn-primary" style="font-size: 0.8rem; padding: 12px 5px; flex: 1; border-radius: 8px;">Beli</a>
                    @else
                        <!-- Non-customers are prompted to register as customer to buy -->
                        <button onclick="alert('Anda harus masuk dengan akun Pelanggan untuk melakukan pembelian. Akun Kurir/Seller/Admin tidak dapat melakukan pembelian.')" class="btn-primary" style="font-size: 0.8rem; padding: 12px 5px; flex: 1; border-radius: 8px;">Beli</button>
                    @endif
                @endif
            @endguest
        </div>
    </div>
</div>
@endsection
