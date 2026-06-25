@extends('layouts.app')

@section('title', 'Notifikasi - SmartThrift')

@section('content')
<!-- Header Bar -->
<div class="header-bar">
    <a href="javascript:history.back()" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </a>
    <div class="title" style="letter-spacing: 0.1em; text-align: left; padding-left: 20px;">Notifikasi</div>
    <a href="{{ route('chat.index') }}" class="action-icon">
        <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
    </a>
</div>

<div class="app-content">

    <div class="notif-section-title" style="margin-top: 10px;">Stasus Pesanan</div>

    <div style="margin-top: 10px;">
        @forelse($notifications as $notif)
            <div class="notif-list-item {{ $notif->is_read ? 'read' : '' }}">
                <div class="notif-icon-box">
                    <!-- If notification has order, use product thumbnail, else use system bell icon -->
                    @if($notif->order && $notif->order->product)
                        @if($notif->order->product->image_path)
                            <img src="{{ asset($notif->order->product->image_path) }}" alt="product thumbnail" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60';">
                        @else
                            <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&auto=format&fit=crop&q=60" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                        @endif
                    @else
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent-red);">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                    @endif
                </div>
                
                <div class="notif-content-box">
                    <div class="notif-title">{{ $notif->title }}</div>
                    <div class="notif-message">{{ $notif->message }}</div>
                    
                    @if($notif->order)
                        <div style="margin-top: 8px;">
                            <a href="{{ route('buyer.purchases') }}" style="font-size: 0.75rem; color: var(--accent-red); text-decoration: underline; font-weight: bold;">
                                Lihat Detail Pesanan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 50px 0; color: var(--text-muted);">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.6;">
                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                </svg>
                <p>Tidak ada notifikasi pesanan saat ini.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
