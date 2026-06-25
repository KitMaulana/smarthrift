@extends('layouts.app')

@section('title', 'Kelola Pengguna - SmartThrift')

@section('content')
<div class="header-bar">
    <a href="{{ route('admin.dashboard') }}" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </a>
    <div class="title">Kelola User</div>
    <div style="width: 24px;"></div>
</div>

<div class="app-content">

    <div style="margin-top: 10px;">
        @foreach($users as $user)
            <div class="section-card" style="margin-bottom: 12px; padding: 15px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                    <div>
                        <h4 style="font-size: 0.95rem; font-weight: bold;">{{ $user->name }}</h4>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">
                            @ {{ $user->username }} &bull; {{ $user->email }}
                        </div>
                    </div>
                    <div>
                        <span class="status-badge role-{{ $user->role }}" style="font-size: 0.75rem;">
                            {{ $user->role }}
                        </span>
                    </div>
                </div>

                <!-- Update Role form and Delete User button -->
                <div style="display: flex; gap: 10px; align-items: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 10px; margin-top: 5px;">
                    <!-- Update Role Form -->
                    <form action="{{ route('admin.update_role', $user->id) }}" method="POST" style="flex: 1; display: flex; gap: 8px;">
                        @csrf
                        <select name="role" class="form-select" style="font-size: 0.8rem; height: 36px; padding: 5px 10px; border-radius: 8px; background-color: white; color: black; flex: 1;">
                            <option value="pelanggan" {{ $user->role === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                            <option value="penjual" {{ $user->role === 'penjual' ? 'selected' : '' }}>Penjual</option>
                            <option value="kurir" {{ $user->role === 'kurir' ? 'selected' : '' }}>Kurir</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <button type="submit" class="btn-primary" style="padding: 0 12px; font-size: 0.8rem; height: 36px; text-transform: none; border-radius: 8px; width: auto; background-color: var(--success);">
                            Simpan
                        </button>
                    </form>

                    <!-- Delete Form (Only if not deleting oneself) -->
                    @if($user->id !== Auth::id())
                        <form action="{{ route('admin.delete_user', $user->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini secara permanen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-primary" style="padding: 0 12px; font-size: 0.8rem; height: 36px; text-transform: none; border-radius: 8px; width: auto; background-color: var(--accent-red);">
                                Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
