@extends('layouts.app')

@section('title', 'Jelajah Pakaian Bekas - SmartThrift')

@section('content')
<!-- Header Bar -->
<div class="header-bar">
    <a href="{{ route('home') }}" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </a>
    <div class="title" style="visibility: hidden;">Jelajah</div>
    <a href="{{ route('chat.index') }}" class="action-icon">
        <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
    </a>
</div>

<div class="app-content">

    <!-- Search box -->
    <div class="search-container">
        <form action="{{ route('explore') }}" method="GET" class="search-box">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-muted);">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
            <input type="text" name="search" class="search-input" placeholder="Cari" value="{{ request('search') }}">
        </form>
    </div>

    <!-- Category selector row -->
    <div class="category-row">
        <!-- Atasan -->
        <a href="{{ route('explore', array_merge(request()->query(), ['category' => request('category') === 'atasan' ? '' : 'atasan'])) }}" class="category-btn {{ request('category') === 'atasan' ? 'active' : '' }}">
            <div class="category-icon-wrapper">
                <!-- Shirt SVG -->
                <svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18 2h-3.41a3 3 0 0 0-5.18 0H6a2 2 0 0 0-2 2v3a2 2 0 0 0 1.22 1.84L7 9.5V20a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9.5l1.78-.66A2 2 0 0 0 20 7V4a2 2 0 0 0-2-2zM9.5 4a1.5 1.5 0 0 1 3 0v1h-3zm6.5 15.5H8v-9.7l3.24-1.21a2 2 0 0 0 .76-.8l.1-.21h.75v1.27a2 2 0 0 0 1.15 1.82L16 10.7z"/>
                </svg>
            </div>
            <span class="category-name">Atasan</span>
        </a>

        <!-- Bawahan -->
        <a href="{{ route('explore', array_merge(request()->query(), ['category' => request('category') === 'bawahan' ? '' : 'bawahan'])) }}" class="category-btn {{ request('category') === 'bawahan' ? 'active' : '' }}">
            <div class="category-icon-wrapper">
                <!-- Pants SVG -->
                <svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M7 2h10a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-8h-2v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm5 8h2V4h-2zm-6 0h2V4H6zm10 0h2V4h-2z"/>
                </svg>
            </div>
            <span class="category-name">Bawahan</span>
        </a>

        <!-- Aksesoris -->
        <a href="{{ route('explore', array_merge(request()->query(), ['category' => request('category') === 'aksesoris' ? '' : 'aksesoris'])) }}" class="category-btn {{ request('category') === 'aksesoris' ? 'active' : '' }}">
            <div class="category-icon-wrapper">
                <!-- Glasses SVG (Matches Page 5 icon) -->
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="6" cy="15" r="4"/>
                    <circle cx="18" cy="15" r="4"/>
                    <path d="M14 15a2 2 0 0 0-4 0M2 15h1M21 15h1M4 11V7a2 2 0 0 1 2-2h1M20 11V7a2 2 0 0 0-2-2h-1"/>
                </svg>
            </div>
            <span class="category-name">Aksesoris</span>
        </a>
    </div>

    <!-- Promo Banner Card -->
    <div class="promo-banner">
        <div class="promo-text-content">
            <span class="promo-tag">Diskon Eksklusif</span>
            <div class="promo-title">Dapatkan akses eksklusif</div>
            <div class="promo-description">untuk pelanggan setia, temukan fashion terbaik lebih dulu di SMART THRIFT</div>
        </div>
        <!-- Unsplash image of woman holding phone -->
        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80" alt="SmartThrift Banner" class="promo-image">
    </div>

    <!-- Recommendations / Rekomendasi Section -->
    <div class="notif-section-title" style="margin-top: 10px;">
        @if(request('category'))
            Hasil Kategori: <span style="text-transform: capitalize;">{{ request('category') }}</span>
        @elseif(request('search'))
            Hasil Pencarian: "{{ request('search') }}"
        @else
            Rekomendasi
        @endif
    </div>

    <!-- Grid items -->
    <div class="product-grid">
        @forelse($products as $product)
            <a href="{{ route('product.show', $product->id) }}" class="product-card">
                <div class="product-image-wrapper">
                    @if($product->image_path)
                        @if(Str::startsWith($product->image_path, 'uploads/') || Str::startsWith($product->image_path, 'images/'))
                            <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="product-image" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=300&auto=format&fit=crop&q=60';">
                        @else
                            <img src="{{ asset('uploads/' . $product->image_path) }}" alt="{{ $product->name }}" class="product-image" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=300&auto=format&fit=crop&q=60';">
                        @endif
                    @else
                        @php
                            $fallbackUrl = 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=300&auto=format&fit=crop&q=60';
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
                <p>Tidak ada pakaian yang cocok dengan kriteria Anda.</p>
                <a href="{{ route('explore') }}" style="color: var(--accent-red); text-decoration: underline; margin-top: 10px; display: inline-block;">Reset Filter</a>
            </div>
        @endforelse
    </div>

</div>
@endsection
