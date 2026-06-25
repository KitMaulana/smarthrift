@extends('layouts.app')

@section('title', 'Kelola Produk - SmartThrift')

@section('content')
<div class="header-bar">
    <a href="{{ route('admin.dashboard') }}" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </a>
    <div class="title">Kelola Iklan</div>
    <div style="width: 24px;"></div>
</div>

<div class="app-content">

    <!-- Menunggu Persetujuan Section -->
    <div style="margin-top: 10px; margin-bottom: 25px;">
        <h3 style="font-size: 1.1rem; font-weight: bold; margin-bottom: 15px; color: var(--warning); border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 6px;">
            Menunggu Persetujuan ({{ $products->where('status', 'pending')->count() }})
        </h3>
        
        @forelse($products->where('status', 'pending') as $prod)
            <div class="section-card" style="margin-bottom: 12px; padding: 15px; border-left: 4px solid var(--warning);">
                <div style="display: flex; gap: 12px; align-items: center; justify-content: space-between;">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        @if($prod->image_path)
                            <img src="{{ asset($prod->image_path) }}" alt="{{ $prod->name }}" style="width: 44px; height: 44px; object-fit: cover; border-radius: 6px;" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60';">
                        @else
                            <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60" style="width: 44px; height: 44px; object-fit: cover; border-radius: 6px;">
                        @endif
                        <div>
                            <h4 style="font-size: 0.9rem; font-weight: bold; margin-bottom: 2px;">{{ $prod->name }}</h4>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                Seller: {{ $prod->seller->name }} &bull; Rp {{ number_format($prod->price, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    
                    <span class="status-badge pending" style="font-size: 0.7rem; padding: 2px 6px;">
                        {{ $prod->status }}
                    </span>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 10px; margin-top: 10px;">
                    <form action="{{ route('admin.delete_product', $prod->id) }}" method="POST" onsubmit="return confirm('Tolak iklan produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-primary" style="padding: 6px 12px; font-size: 0.75rem; text-transform: none; border-radius: 6px; width: auto; background-color: var(--accent-red); margin-bottom: 0;">
                            Tolak
                        </button>
                    </form>
                    <form action="{{ route('admin.approve_product', $prod->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary" style="padding: 6px 12px; font-size: 0.75rem; text-transform: none; border-radius: 6px; width: auto; background-color: var(--success); margin-bottom: 0;">
                            Setujui
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 20px 0; color: var(--text-muted); font-size: 0.85rem;">
                Tidak ada produk menunggu persetujuan.
            </div>
        @endforelse
    </div>

    <!-- Iklan Aktif Section -->
    <div style="margin-top: 10px;">
        <h3 style="font-size: 1.1rem; font-weight: bold; margin-bottom: 15px; color: var(--text-primary); border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 6px;">
            Iklan Aktif & Terjual ({{ $products->where('status', '!=', 'pending')->count() }})
        </h3>
        
        @forelse($products->where('status', '!=', 'pending') as $prod)
            <div class="section-card" style="margin-bottom: 12px; padding: 15px;">
                <div style="display: flex; gap: 12px; align-items: center; justify-content: space-between;">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        @if($prod->image_path)
                            <img src="{{ asset($prod->image_path) }}" alt="{{ $prod->name }}" style="width: 44px; height: 44px; object-fit: cover; border-radius: 6px;" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60';">
                        @else
                            <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60" style="width: 44px; height: 44px; object-fit: cover; border-radius: 6px;">
                        @endif
                        <div>
                            <h4 style="font-size: 0.9rem; font-weight: bold; margin-bottom: 2px;">{{ $prod->name }}</h4>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                Seller: {{ $prod->seller->name }} &bull; Rp {{ number_format($prod->price, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    
                    <span class="status-badge" style="background-color: {{ $prod->status === 'available' ? 'var(--success)' : '#6C757D' }}; color: white; font-size: 0.7rem; padding: 2px 6px;">
                        {{ $prod->status === 'available' ? 'available' : $prod->status }}
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 10px; margin-top: 10px;">
                    <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">
                        Kat: {{ $prod->category }}
                    </span>
                    
                    <form action="{{ route('admin.delete_product', $prod->id) }}" method="POST" onsubmit="return confirm('Hapus iklan produk ini dari platform?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-primary" style="padding: 6px 12px; font-size: 0.75rem; text-transform: none; border-radius: 6px; width: auto; background-color: var(--accent-red); margin-bottom: 0;">
                            Hapus Iklan
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 30px 0; color: var(--text-muted);">
                <p>Tidak ada iklan pakaian bekas.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
