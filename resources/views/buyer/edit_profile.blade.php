@extends('layouts.app')

@section('title', 'Ubah Profil - SmartThrift')

@section('content')
<div class="header-bar">
    <a href="{{ route('buyer.profile') }}" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </a>
    <div class="title">Ubah Profil</div>
    <div style="width: 24px;"></div>
</div>

<div class="app-content">

    <form action="{{ route('buyer.update_profile') }}" method="POST" style="margin-top: 15px;">
        @csrf
        
        <!-- Name -->
        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
        </div>

        <!-- Alamat Saya -->
        <div class="form-group">
            <label class="form-label">Alamat Saya</label>
            <textarea name="address" class="form-textarea" placeholder="Tulis alamat lengkap pengiriman...">{{ old('address', $user->address) }}</textarea>
        </div>

        <!-- Nomor Telepon -->
        <div class="form-group">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="phone" class="form-input" placeholder="Contoh: 0812XXXXXXXX" value="{{ old('phone', $user->phone) }}">
        </div>

        <!-- Jenis Kelamin -->
        <div class="form-group">
            <label class="form-label">Jenis Kelamin</label>
            <select name="gender" class="form-select" style="background-color: white; color: black; border-radius: 12px; height: 50px;">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="Laki-laki" {{ old('gender', $user->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('gender', $user->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <!-- Tanggal Lahir -->
        <div class="form-group" style="margin-bottom: 30px;">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="dob" class="form-input" value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary">Simpan Perubahan</button>
    </form>

</div>
@endsection
