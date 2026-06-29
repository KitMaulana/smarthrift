@extends('layouts.app')

@section('title', 'Beranda - SmartThrift')

@section('content')
<!-- Header Bar -->
<div class="header-bar">
    <a href="{{ route('welcome') }}" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </a>
    <div class="title" style="display: flex; justify-content: center; align-items: center;"><img src="{{ asset('images/logo.png') }}" class="header-logo" alt="SmartThrift"></div>
    <a href="{{ route('chat.index') }}" class="action-icon">
        <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
    </a>
</div>

<div class="app-content">
    
    <!-- Customer selling warning prompt -->
    @if(request()->has('prompt_seller'))
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            <strong>Akses Terbatas:</strong> Akun Anda terdaftar sebagai <strong>Pelanggan (Pembeli)</strong>. Hanya akun <strong>Penjual</strong> yang dapat mempublikasikan pakaian untuk dijual. Silakan buat akun baru sebagai Penjual atau hubungi Admin.
        </div>
    @endif

    <!-- Product Grid Section -->
    <div class="product-grid">
        @forelse($products as $product)
            <a href="{{ route('product.show', $product->id) }}" class="product-card">
                <div class="product-image-wrapper">
                    @if($product->image_path)
                        <!-- Check if path has uploads or images -->
                        @if(Str::startsWith($product->image_path, 'uploads/') || Str::startsWith($product->image_path, 'images/'))
                            <!-- Since we don't have node/npm compilation, we'll write a clean SVG fallback in case image file is not on disk, otherwise load it -->
                            <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="product-image" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=300&auto=format&fit=crop&q=60';">
                        @else
                            <img src="{{ asset('uploads/' . $product->image_path) }}" alt="{{ $product->name }}" class="product-image" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=300&auto=format&fit=crop&q=60';">
                        @endif
                    @else
                        <!-- Unsplash fallback based on name or category -->
                        @php
                            $fallbackUrl = 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=300&auto=format&fit=crop&q=60'; // Default clothing
                            if($product->category == 'aksesoris') {
                                if(Str::contains(strtolower($product->name), 'sepatu')) {
                                    $fallbackUrl = 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=300&auto=format&fit=crop&q=60';
                                } else {
                                    $fallbackUrl = 'https://images.unsplash.com/photo-1525507119028-ed4c629a60a3?w=300&auto=format&fit=crop&q=60';
                                }
                            } elseif($product->category == 'bawahan') {
                                $fallbackUrl = 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=300&auto=format&fit=crop&q=60';
                            }
                        @endphp
                        <img src="{{ $fallbackUrl }}" alt="{{ $product->name }}" class="product-image">
                    @endif
                </div>
                <div class="product-info">
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                </div>
            </a>
        @empty
            <div style="grid-column: span 2; text-align: center; padding: 40px 0; color: var(--text-muted);">
                <p>Belum ada produk bekas yang dijual saat ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Testimoni Section -->
    <div class="section-card" style="margin-top: 30px; margin-bottom: 20px; background-color: var(--bg-secondary);">
        <div class="section-title" style="display: flex; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--warning);">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            <span>Testimoni SmartThrift</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 10px;">
            @forelse($testimonials as $t)
                <div style="background-color: var(--bg-darker); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="font-weight: bold; font-size: 0.85rem; color: var(--text-primary);">{{ $t->user->name }}</span>
                        <span style="color: var(--warning); font-size: 0.8rem;">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $t->rating ? '★' : '☆' }}
                            @endfor
                        </span>
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); font-style: italic; line-height: 1.4;">
                        "{{ $t->comment }}"
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 15px 0; color: var(--text-muted); font-size: 0.85rem;">
                    Belum ada testimoni. Jadilah yang pertama memberikan ulasan!
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
