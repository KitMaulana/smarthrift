@extends('layouts.app')

@section('title', 'Mulai Jual - SmartThrift')

@section('content')
<!-- Header Bar -->
<div class="header-bar">
    <a href="{{ route('seller.dashboard') }}" class="back-btn">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        <span style="margin-left: 5px;">Mulai Jual</span>
    </a>
    <div class="title" style="visibility: hidden;">Mulai Jual</div>
    <a href="{{ route('chat.index') }}" class="action-icon">
        <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
    </a>
</div>

<div class="app-content">

    <form action="{{ route('seller.store_product') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Photo Upload Preview area (Matches Page 5) -->
        <div class="form-group">
            <label class="form-label" style="color: var(--text-primary);">Foto</label>
            <div id="image-upload-card" style="width: 100%; height: 200px; border-radius: 12px; background-color: var(--bg-secondary); border: 2px dashed rgba(255,255,255,0.2); display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; overflow: hidden; position: relative;">
                
                <!-- Image Preview element -->
                <img id="image-preview" src="" style="width: 100%; height: 100%; object-fit: cover; display: none; position: absolute; top:0; left:0;">
                
                <div id="upload-prompt" style="text-align: center; z-index: 2;">
                    <!-- Camera SVG -->
                    <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-muted); margin-bottom: 8px;">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/>
                    </svg>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">Ketuk untuk mengunggah foto pakaian</div>
                </div>

                <input type="file" name="image" id="image-file-input" accept="image/*" style="display: none;">
            </div>
        </div>

        <!-- Nama Produk -->
        <div class="form-group">
            <label class="form-label" style="color: var(--text-primary);">Nama Produk</label>
            <input type="text" name="name" class="form-input" placeholder="Nama barang" value="{{ old('name') }}" required>
        </div>

        <!-- Kategori (Custom Circular Buttons matching Page 5 icons) -->
        <div class="form-group">
            <label class="form-label" style="color: var(--text-primary);">Kategori</label>
            <input type="hidden" name="category" id="hidden-category" value="{{ old('category') }}" required>
            
            <div style="display: flex; justify-content: space-around; margin-top: 10px;">
                <!-- Atasan circle -->
                <button type="button" class="cat-circle-btn" data-value="atasan" style="background: none; border: none; display: flex; flex-direction: column; align-items: center; cursor: pointer; color: white;">
                    <div class="circle-icon" style="width: 54px; height: 54px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.4); display: flex; align-items: center; justify-content: center; margin-bottom: 6px; transition: all 0.2s;">
                        <!-- Shirt SVG -->
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 2h-3.41a3 3 0 0 0-5.18 0H6a2 2 0 0 0-2 2v3a2 2 0 0 0 1.22 1.84L7 9.5V20a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9.5l1.78-.66A2 2 0 0 0 20 7V4a2 2 0 0 0-2-2zM9.5 4a1.5 1.5 0 0 1 3 0v1h-3z"/>
                        </svg>
                    </div>
                    <span style="font-size: 0.8rem;">Atasan</span>
                </button>

                <!-- Bawahan circle -->
                <button type="button" class="cat-circle-btn" data-value="bawahan" style="background: none; border: none; display: flex; flex-direction: column; align-items: center; cursor: pointer; color: white;">
                    <div class="circle-icon" style="width: 54px; height: 54px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.4); display: flex; align-items: center; justify-content: center; margin-bottom: 6px; transition: all 0.2s;">
                        <!-- Pants SVG -->
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M7 2h10a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-8h-2v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z"/>
                        </svg>
                    </div>
                    <span style="font-size: 0.8rem;">Bawahan</span>
                </button>

                <!-- Aksesoris circle -->
                <button type="button" class="cat-circle-btn" data-value="aksesoris" style="background: none; border: none; display: flex; flex-direction: column; align-items: center; cursor: pointer; color: white;">
                    <div class="circle-icon" style="width: 54px; height: 54px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.4); display: flex; align-items: center; justify-content: center; margin-bottom: 6px; transition: all 0.2s;">
                        <!-- Glasses SVG -->
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="6" cy="15" r="4"/><circle cx="18" cy="15" r="4"/><path d="M14 15a2 2 0 0 0-4 0M2 15h1M21 15h1"/>
                        </svg>
                    </div>
                    <span style="font-size: 0.8rem;">Aksesoris</span>
                </button>
            </div>
        </div>

        <!-- Rincian Produk -->
        <div class="form-group">
            <label class="form-label" style="color: var(--text-primary);">Rincian Produk</label>
            <textarea name="description" class="form-textarea" placeholder="Deskripsi..." required>{{ old('description') }}</textarea>
        </div>

        <!-- Harga Barang & Biaya Layanan -->
        <div class="form-group">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span class="form-label" style="color: var(--text-primary); margin-bottom: 0;">Harga barang</span>
                <span class="form-label" style="color: var(--text-primary); margin-bottom: 0;">Biaya layanan</span>
            </div>
            
            <div style="display: flex; gap: 20px; align-items: center;">
                <div style="position: relative; flex: 1;">
                    <span style="position: absolute; left: 12px; top: 14px; color: var(--text-dark); font-weight: bold;">Rp</span>
                    <input type="text" id="price_display" class="form-input" style="padding-left: 35px;" placeholder="0" required>
                    <input type="hidden" name="price" id="price" value="{{ old('price') }}">
                </div>
                <div style="flex: 1; padding: 14px 16px; background-color: rgba(255,255,255,0.05); border-radius: 12px; font-weight: bold; border: 1px solid rgba(255,255,255,0.1); text-align: right; color: var(--text-primary);">
                    Rp 6.000
                </div>
            </div>
        </div>

        <!-- Metode Pembayaran -->
        <div class="form-group" style="margin-bottom: 30px;">
            <label class="form-label" style="color: var(--text-primary);">Metode pembayaran</label>
            <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 10px;">
                
                <label style="display: flex; align-items: center; justify-content: space-between; background-color: var(--bg-secondary); padding: 12px 15px; border-radius: 8px; cursor: pointer;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="payment-badge" style="background-color: white; color: black;">QRIS</span>
                    </div>
                    <input type="radio" name="payment_method" value="qris" style="width: 18px; height: 18px; accent-color: var(--accent-red);">
                </label>

                <label style="display: flex; align-items: center; justify-content: space-between; background-color: var(--bg-secondary); padding: 12px 15px; border-radius: 8px; cursor: pointer;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="payment-badge" style="background-color: white; color: black;">COD</span>
                    </div>
                    <input type="radio" name="payment_method" value="cod" style="width: 18px; height: 18px; accent-color: var(--accent-red);">
                </label>

                <label style="display: flex; align-items: center; justify-content: space-between; background-color: var(--bg-secondary); padding: 12px 15px; border-radius: 8px; cursor: pointer;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="payment-badge" style="background-color: white; color: black;">Keduanya (QRIS & COD)</span>
                    </div>
                    <input type="radio" name="payment_method" value="both" checked style="width: 18px; height: 18px; accent-color: var(--accent-red);">
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary" style="background-color: #586361; text-transform: none; border-radius: 20px;">Jual</button>
    </form>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Price formatting with dots
        const priceDisplay = document.getElementById('price_display');
        const priceHidden = document.getElementById('price');

        function formatRupiah(value) {
            let clean = value.replace(/\D/g, '');
            if (clean === '') return '';
            return clean.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        priceDisplay.addEventListener('input', function(e) {
            let selectionStart = this.selectionStart;
            let originalLength = this.value.length;

            let formattedVal = formatRupiah(this.value);
            this.value = formattedVal;
            priceHidden.value = formattedVal.replace(/\./g, '');

            let newLength = this.value.length;
            let diff = newLength - originalLength;
            let newCursor = selectionStart + diff;
            this.setSelectionRange(newCursor, newCursor);
        });

        if (priceHidden.value) {
            priceDisplay.value = formatRupiah(priceHidden.value);
        }

        // Image preview logic
        const uploadCard = document.getElementById('image-upload-card');
        const fileInput = document.getElementById('image-file-input');
        const previewImg = document.getElementById('image-preview');
        const promptDiv = document.getElementById('upload-prompt');

        uploadCard.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    promptDiv.style.opacity = '0.3'; // dim prompt text
                }
                reader.readAsDataURL(file);
            }
        });

        // Category circle select logic
        const catButtons = document.querySelectorAll('.cat-circle-btn');
        const hiddenInput = document.getElementById('hidden-category');

        // Set initial active state if any
        if (hiddenInput.value) {
            setActiveCategory(hiddenInput.value);
        }

        catButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const val = this.getAttribute('data-value');
                hiddenInput.value = val;
                setActiveCategory(val);
            });
        });

        function setActiveCategory(value) {
            catButtons.forEach(btn => {
                const circle = btn.querySelector('.circle-icon');
                if (btn.getAttribute('data-value') === value) {
                    circle.style.backgroundColor = 'var(--accent-red)';
                    circle.style.borderColor = 'var(--accent-red)';
                    btn.style.color = 'var(--accent-red)';
                } else {
                    circle.style.backgroundColor = 'transparent';
                    circle.style.borderColor = 'rgba(255,255,255,0.4)';
                    btn.style.color = 'white';
                }
            });
        }
    });
</script>
@endsection
