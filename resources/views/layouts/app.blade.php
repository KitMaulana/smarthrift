<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartThrift')</title>
    <!-- Google Fonts Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Main Style -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>

    <div class="app-container">
        
        <!-- Flash Messages -->
        @if(session('success'))
            <div style="padding: 10px 20px 0 20px; z-index: 100;">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session('error'))
            <div style="padding: 10px 20px 0 20px; z-index: 100;">
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            </div>
        @endif
        @if($errors->any())
            <div style="padding: 10px 20px 0 20px; z-index: 100;">
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 15px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Main Content Renders here -->
        @yield('content')

        <!-- Dynamic Bottom Navigation - Always Visible -->
            <nav class="bottom-nav">
                <!-- Beranda -->
                <a href="{{ route('home') }}" class="nav-item {{ Route::is('home') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span>Beranda</span>
                </a>

                <!-- Jelajah -->
                <a href="{{ route('explore') }}" class="nav-item {{ Route::is('explore') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                    <span>jelajah</span>
                </a>

                <!-- Jual -->
                @guest
                    <a href="{{ route('login') }}" class="nav-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/>
                        </svg>
                        <span>Jual</span>
                    </a>
                @else
                    @if(Auth::user()->isPenjual())
                        <a href="{{ route('seller.create_product') }}" class="nav-item {{ Route::is('seller.create_product') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/>
                            </svg>
                            <span>Jual</span>
                        </a>
                    @else
                        <!-- If customer clicks sell, we redirect to home with explanatory message -->
                        <a href="{{ route('home') }}?prompt_seller=1" class="nav-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/>
                            </svg>
                            <span>Jual</span>
                        </a>
                    @endif
                @endguest

                <!-- Notifikasi -->
                @guest
                    <a href="{{ route('login') }}" class="nav-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                        <span>Notifikasi</span>
                    </a>
                @else
                    @if(Auth::user()->isPelanggan())
                        <a href="{{ route('buyer.notifications') }}" class="nav-item {{ Route::is('buyer.notifications') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                            </svg>
                            <span>Notifikasi</span>
                        </a>
                    @else
                        <!-- Non-buyer roles see notification via their dashboard -->
                        <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : (Auth::user()->isKurir() ? route('courier.dashboard') : route('seller.dashboard')) }}" class="nav-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                            </svg>
                            <span>Notifikasi</span>
                        </a>
                    @endif
                @endguest

                <!-- Saya (Profile / Dashboard) -->
                @guest
                    <a href="{{ route('welcome') }}" class="nav-item {{ Route::is('welcome') || Route::is('login') || Route::is('register') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span>Saya</span>
                    </a>
                @else
                    @php
                        $profileRoute = route('buyer.profile');
                        if (Auth::user()->isAdmin()) $profileRoute = route('admin.dashboard');
                        elseif (Auth::user()->isKurir()) $profileRoute = route('courier.dashboard');
                        elseif (Auth::user()->isPenjual()) $profileRoute = route('seller.dashboard');
                    @endphp
                    <a href="{{ $profileRoute }}" class="nav-item {{ Route::is('buyer.profile') || Route::is('admin.dashboard') || Route::is('courier.dashboard') || Route::is('seller.dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span>Saya</span>
                    </a>
                @endguest
            </nav>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const headerBars = document.querySelectorAll('.header-bar');
        headerBars.forEach(function(headerBar) {
            const titleEl = headerBar.querySelector('.title');
            if (titleEl) {
                // If it already has a logo, do nothing
                if (titleEl.querySelector('.header-logo')) {
                    return;
                }
                
                // Check if the title text is "beranda" (just in case there's any fallback)
                const rawText = titleEl.textContent.trim().toLowerCase().replace(/\s+/g, '');
                if (rawText === 'beranda') {
                    titleEl.innerHTML = `<img src="{{ asset('images/logo.png') }}" class="header-logo" alt="SmartThrift">`;
                    titleEl.style.display = 'flex';
                    titleEl.style.justifyContent = 'center';
                    titleEl.style.alignItems = 'center';
                    return;
                }
                
                // Create logo image element
                const logoImg = document.createElement('img');
                logoImg.src = "{{ asset('images/logo.png') }}";
                logoImg.className = 'header-logo';
                logoImg.style.height = '65px';
                logoImg.style.marginRight = '8px';
                logoImg.style.width = 'auto';
                logoImg.alt = "SmartThrift";
                
                titleEl.style.display = 'flex';
                titleEl.style.alignItems = 'center';
                
                const computedStyle = window.getComputedStyle(titleEl);
                if (computedStyle.visibility === 'hidden' || titleEl.style.visibility === 'hidden') {
                    titleEl.innerHTML = '';
                    titleEl.appendChild(logoImg);
                    titleEl.style.visibility = 'visible';
                    titleEl.style.justifyContent = 'center';
                } else {
                    const textSpan = document.createElement('span');
                    textSpan.innerHTML = titleEl.innerHTML;
                    
                    titleEl.innerHTML = '';
                    titleEl.appendChild(logoImg);
                    titleEl.appendChild(textSpan);
                    
                    if (computedStyle.textAlign === 'left' || titleEl.style.textAlign === 'left') {
                        titleEl.style.justifyContent = 'flex-start';
                    } else {
                        titleEl.style.justifyContent = 'center';
                    }
                }
            }
        });
    });
    </script>
    @yield('scripts')
</body>
</html>
