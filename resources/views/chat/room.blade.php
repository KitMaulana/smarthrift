@extends('layouts.app')

@section('title', 'Chat dengan ' . $otherUser->name)

@section('styles')
<style>
    /* Hide bottom nav on specific chat conversation room page */
    .bottom-nav {
        display: none !important;
    }
</style>
@endsection

@section('content')
<!-- Header Bar -->
<div class="header-bar" style="justify-content: flex-start; gap: 15px;">
    <a href="javascript:history.back()" class="back-btn" style="flex: none;">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </a>
    
    <!-- User avatar & info -->
    <div style="display: flex; align-items: center; gap: 10px; flex: 1;">
        <div class="profile-avatar" style="background-color: {{ $otherUser->role === 'admin' ? 'var(--accent-red)' : ($otherUser->role === 'penjual' ? '#83C5BE' : '#E29578') }}; color: white; width: 38px; height: 38px; font-size: 0.95rem; margin-right: 0; position: relative;">
            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
            <!-- Online status green dot -->
            <span style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background-color: #2ec4b6; border-radius: 50%; border: 2px solid var(--bg-primary);"></span>
        </div>
        <div>
            <div style="font-weight: bold; font-size: 0.95rem;">{{ $otherUser->name }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">
                Online {{ $otherUser->role === 'admin' ? 'Admin' : ($otherUser->role === 'pelanggan' ? 'Pembeli' : 'Penjual') }}
            </div>
        </div>
    </div>
</div>

<div class="app-content" style="padding-bottom: calc(75px + env(safe-area-inset-bottom));">
    
    <div class="chat-room-container">
        <!-- Message stream -->
        <div class="messages-list" id="messages-stream">
            
            <!-- Context card if product is specified (Matches Screenshot Page 10) -->
            @if($product)
                <div class="chat-product-card">
                    @if($product->image_path)
                        <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="chat-product-image" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60';">
                    @else
                        <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60" class="chat-product-image">
                    @endif
                    <div class="chat-product-details">
                        <div class="chat-product-title">Pembeli menanyakan barang ini</div>
                        <div class="chat-product-name" style="font-size: 0.85rem; color: var(--text-muted);">Nama produk: <strong style="color: white;">{{ $product->name }}</strong></div>
                        <div class="chat-product-name" style="font-size: 0.85rem; color: var(--text-muted);">Total produk: <span style="color: white;">1</span></div>
                        <div class="chat-product-price" style="font-size: 0.85rem; color: var(--text-muted);">Harga produk: <span style="color: white;">Rp {{ number_format($product->price, 0, ',', '.') }}</span></div>
                    </div>
                </div>
            @endif

            <!-- Chat messages -->
            @foreach($messages as $msg)
                @php
                    $isOutgoing = ($msg->sender_id === Auth::id());
                @endphp
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 3px; font-weight: 600;">
                        {{ $isOutgoing ? 'Anda' : ($otherUser->role === 'admin' ? 'Admin' : ($otherUser->role === 'pelanggan' ? 'Pembeli' : 'Penjual')) }}
                    </div>
                    <div>{{ $msg->message }}</div>
                    <div style="font-size: 0.65rem; color: var(--text-muted); text-align: right; margin-top: 3px;">
                        {{ $msg->created_at->format('H.i') }}
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>

<!-- Chat Input Bar outside app-content -->
<form action="{{ route('chat.send', $otherUser->id) }}" method="POST" class="chat-input-bar">
    @csrf
    @if($product)
        <input type="hidden" name="product_id" value="{{ $product->id }}">
    @endif
    <input type="text" name="message" class="chat-input" placeholder="Ketik pesan..." required autocomplete="off">
    <button type="submit" class="chat-send-btn">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
        </svg>
    </button>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto scroll chat to the bottom
        const stream = document.getElementById('messages-stream');
        stream.scrollTop = stream.scrollHeight;
    });
</script>
@endsection
