@extends('layouts.app')

@section('title', 'Chat - SmartThrift')

@section('content')
<!-- Header Bar -->
<div class="header-bar">
    <a href="javascript:history.back()" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </a>
    <div class="title" style="letter-spacing: 0.1em; text-align: left; padding-left: 20px;">Chat</div>
    <div style="width: 24px;"></div>
</div>

<div class="app-content">
    
    <div style="margin-top: 10px;">
        @forelse($rooms as $otherUserId => $room)
            <a href="{{ route('chat.show', $otherUserId) }}" class="chat-list-item">
                <div class="chat-item-left">
                    <div class="profile-avatar" style="background-color: {{ $room['user']->role === 'penjual' ? '#83C5BE' : '#E29578' }}; color: white; width: 44px; height: 44px; font-size: 1.1rem;">
                        {{ strtoupper(substr($room['user']->name, 0, 1)) }}
                    </div>
                    <div class="chat-item-text">
                        <div class="chat-item-name">{{ $room['user']->name }}</div>
                        <div class="chat-item-message">{{ $room['last_message'] }}</div>
                    </div>
                </div>
                <div class="chat-item-time">
                    <!-- Format time/date -->
                    @if($room['time']->isToday())
                        {{ $room['time']->format('H.i') }}
                    @elseif($room['time']->isYesterday())
                        Kemarin
                    @else
                        {{ $room['time']->isoFormat('dddd') }}
                    @endif
                </div>
            </a>
        @empty
            <div style="text-align: center; padding: 50px 0; color: var(--text-muted);">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.6;">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <p>Belum ada percakapan chat.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
