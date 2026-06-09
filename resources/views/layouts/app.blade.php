<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IngonCare')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#9F86C0',
                        'primary-dark': '#5E4B8B',
                        'primary-darker': '#2D1B69',
                        'primary-light': '#CDB4DB',
                        'primary-lighter': '#EDE4F5',
                        'primary-bg': '#F5F0FA',
                        'primary-surface': '#FDFAFF',
                    }
                }
            }
        }
    </script>

    <style>
        /* Hide scrollbar for bottom nav spacing */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Bottom nav safe area */
        @media (max-width: 1023px) {
            .main-content { padding-bottom: 80px; }
        }

        /* Mobile sidebar overlay */
        .mobile-sidebar-overlay {
            transition: opacity 0.3s ease;
        }
        .mobile-sidebar-panel {
            transition: transform 0.3s ease;
        }
    </style>
</head>

<body style="background:#F5F0FA;">

    <div class="flex">

        <!-- SIDEBAR (Desktop only) -->
        <aside class="hidden lg:block w-64 h-screen fixed px-5 py-6 z-50" style="background: linear-gradient(180deg, #2D1B69 0%, #5E4B8B 100%);">

            <h1 class="text-xl font-semibold mb-10 px-2" style="color:#CDB4DB; letter-spacing:-0.02em;">
                IngonCare
            </h1>

            <nav class="space-y-1">

                <a href="{{ route('home') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm"
                   style="{{ request()->routeIs('home') || request()->routeIs('dashboard') ? 'background:#9F86C0; color:white; font-weight:500;' : 'color:#EDE4F5;' }}"
                   onmouseover="if(!this.style.background.includes('9F86C0')) this.style.background='rgba(255,255,255,0.1)'"
                   onmouseout="if(!this.style.background.includes('9F86C0')) this.style.background=''">
                    <i class="ti ti-layout-dashboard" style="font-size:18px; flex-shrink:0;" aria-hidden="true"></i>
                    Dashboard
                </a>

                <a href="{{ route('hewan-saya') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm"
                   style="{{ request()->routeIs('hewan-saya') || request()->routeIs('pets.*') ? 'background:#9F86C0; color:white; font-weight:500;' : 'color:#EDE4F5;' }}"
                   onmouseover="if(!this.style.background.includes('9F86C0')) this.style.background='rgba(255,255,255,0.1)'"
                   onmouseout="if(!this.style.background.includes('9F86C0')) this.style.background=''">
                    <i class="ti ti-paw" style="font-size:18px; flex-shrink:0;" aria-hidden="true"></i>
                    Hewan Saya
                </a>

                <a href="/riwayat"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm"
                   style="{{ request()->is('riwayat*') ? 'background:#9F86C0; color:white; font-weight:500;' : 'color:#EDE4F5;' }}"
                   onmouseover="if(!this.style.background.includes('9F86C0')) this.style.background='rgba(255,255,255,0.1)'"
                   onmouseout="if(!this.style.background.includes('9F86C0')) this.style.background=''">
                    <i class="ti ti-clipboard-list" style="font-size:18px; flex-shrink:0;" aria-hidden="true"></i>
                    Riwayat Kesehatan
                </a>

                <a href="/pengingat"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm"
                   style="{{ request()->is('pengingat*') ? 'background:#9F86C0; color:white; font-weight:500;' : 'color:#EDE4F5;' }}"
                   onmouseover="if(!this.style.background.includes('9F86C0')) this.style.background='rgba(255,255,255,0.1)'"
                   onmouseout="if(!this.style.background.includes('9F86C0')) this.style.background=''">
                    <i class="ti ti-bell" style="font-size:18px; flex-shrink:0;" aria-hidden="true"></i>
                    Pengingat
                </a>

                <a href="{{ route('profile.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm"
                   style="{{ request()->is('profil*') ? 'background:#9F86C0; color:white; font-weight:500;' : 'color:#EDE4F5;' }}"
                   onmouseover="if(!this.style.background.includes('9F86C0')) this.style.background='rgba(255,255,255,0.1)'"
                   onmouseout="if(!this.style.background.includes('9F86C0')) this.style.background=''">
                    <i class="ti ti-user-circle" style="font-size:18px; flex-shrink:0;" aria-hidden="true"></i>
                    Profil Saya
                </a>

                <a href="{{ route('chatbot.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm"
                   style="{{ request()->is('chatbot*') ? 'background:#9F86C0; color:white; font-weight:500;' : 'color:#EDE4F5;' }}"
                   onmouseover="if(!this.style.background.includes('9F86C0')) this.style.background='rgba(255,255,255,0.1)'"
                   onmouseout="if(!this.style.background.includes('9F86C0')) this.style.background=''">
                    <i class="ti ti-message-dots" style="font-size:18px; flex-shrink:0;" aria-hidden="true"></i>
                    Chatbot AI
                </a>

            </nav>

        </aside>

        <!-- MOBILE SIDEBAR OVERLAY -->
        <div id="mobileSidebarOverlay" class="mobile-sidebar-overlay fixed inset-0 bg-black/50 z-[60] lg:hidden hidden" onclick="closeMobileSidebar()"></div>

        <!-- MOBILE SIDEBAR PANEL -->
        <div id="mobileSidebarPanel" class="mobile-sidebar-panel fixed top-0 left-0 h-full w-72 z-[70] lg:hidden -translate-x-full" style="background: linear-gradient(180deg, #2D1B69 0%, #5E4B8B 100%);">
            <div class="px-5 py-6">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-xl font-semibold" style="color:#CDB4DB; letter-spacing:-0.02em;">
                        IngonCare
                    </h1>
                    <button onclick="closeMobileSidebar()" class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(255,255,255,0.1);">
                        <i class="ti ti-x text-white" style="font-size:18px;" aria-hidden="true"></i>
                    </button>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('home') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm"
                       style="{{ request()->routeIs('home') || request()->routeIs('dashboard') ? 'background:#9F86C0; color:white; font-weight:500;' : 'color:#EDE4F5;' }}">
                        <i class="ti ti-layout-dashboard" style="font-size:18px;" aria-hidden="true"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('hewan-saya') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm"
                       style="{{ request()->routeIs('hewan-saya') || request()->routeIs('pets.*') ? 'background:#9F86C0; color:white; font-weight:500;' : 'color:#EDE4F5;' }}">
                        <i class="ti ti-paw" style="font-size:18px;" aria-hidden="true"></i>
                        Hewan Saya
                    </a>
                    <a href="/riwayat"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm"
                       style="{{ request()->is('riwayat*') ? 'background:#9F86C0; color:white; font-weight:500;' : 'color:#EDE4F5;' }}">
                        <i class="ti ti-clipboard-list" style="font-size:18px;" aria-hidden="true"></i>
                        Riwayat Kesehatan
                    </a>
                    <a href="/pengingat"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm"
                       style="{{ request()->is('pengingat*') ? 'background:#9F86C0; color:white; font-weight:500;' : 'color:#EDE4F5;' }}">
                        <i class="ti ti-bell" style="font-size:18px;" aria-hidden="true"></i>
                        Pengingat
                    </a>
                    <a href="{{ route('profile.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm"
                       style="{{ request()->is('profil*') ? 'background:#9F86C0; color:white; font-weight:500;' : 'color:#EDE4F5;' }}">
                        <i class="ti ti-user-circle" style="font-size:18px;" aria-hidden="true"></i>
                        Profil Saya
                    </a>
                    <a href="{{ route('chatbot.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm"
                       style="{{ request()->is('chatbot*') ? 'background:#9F86C0; color:white; font-weight:500;' : 'color:#EDE4F5;' }}">
                        <i class="ti ti-message-dots" style="font-size:18px;" aria-hidden="true"></i>
                        Chatbot AI
                    </a>
                    <a href="{{ route('settings.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm"
                       style="{{ request()->is('settings*') ? 'background:#9F86C0; color:white; font-weight:500;' : 'color:#EDE4F5;' }}">
                        <i class="ti ti-settings" style="font-size:18px;" aria-hidden="true"></i>
                        Pengaturan
                    </a>
                </nav>

                <!-- Logout di mobile sidebar -->
                <div class="mt-8 pt-4" style="border-top:1px solid rgba(255,255,255,0.1);">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm w-full" style="color:#fca5a5;">
                            <i class="ti ti-logout" style="font-size:18px;" aria-hidden="true"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- MAIN -->
        <div class="lg:ml-64 w-full">

            <!-- HEADER -->
            <header class="flex items-center justify-between px-4 lg:px-10 py-4 lg:py-5 bg-white relative" style="border-bottom: 1.5px solid #EDE4F5;">

                <!-- Mobile: Hamburger + Logo -->
                <div class="flex items-center gap-3 lg:hidden">
                    <button onclick="openMobileSidebar()" class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#EDE4F5;">
                        <i class="ti ti-menu-2" style="font-size:20px; color:#5E4B8B;" aria-hidden="true"></i>
                    </button>
                    <h1 class="text-lg font-semibold" style="color:#5E4B8B;">IngonCare</h1>
                </div>

                <!-- Desktop: Search -->
                @if(request()->routeIs('home') || request()->routeIs('dashboard'))
                <div class="hidden lg:block w-1/3">
                    <form action="{{ route('search') }}" method="GET" class="relative">
                        <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari diskusi atau pengguna..."
                            class="w-full pl-9 pr-4 py-2 rounded-full text-sm focus:outline-none focus:ring-2"
                            style="background:#F5F0FA; border:1.5px solid #CDB4DB;">
                    </form>
                </div>
                @else
                <div class="hidden lg:block"></div>
                @endif

                <!-- RIGHT SIDE -->
                <div class="flex items-center gap-3 lg:gap-5">

                    <!-- Mobile: Search button -->
                    @if(request()->routeIs('home') || request()->routeIs('dashboard'))
                    <a href="{{ route('search') }}" class="lg:hidden w-9 h-9 rounded-xl flex items-center justify-center" style="background:#EDE4F5;">
                        <i class="ti ti-search" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
                    </a>
                    @endif

                    <!-- NOTIFIKASI -->
                    <a href="{{ route('notifications.index') }}" class="relative">
                        <i class="ti ti-bell" style="font-size:22px; color:#5E4B8B;" aria-label="Notifikasi"></i>
                        @php
                            $count = \App\Models\Notification::where('user_id', auth()->id())
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        @if($count > 0)
                            <span class="absolute -top-1 -right-1 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center leading-none" style="background:#9F86C0; font-size:10px;">
                                {{ $count }}
                            </span>
                        @endif
                    </a>

                    <!-- SETTINGS (desktop only) -->
                    <a href="{{ route('settings.index') }}" class="hidden lg:block">
                        <i class="ti ti-settings" style="font-size:22px; color:#5E4B8B;" aria-label="Pengaturan"></i>
                    </a>

                    <!-- PROFILE -->
                    <div class="flex items-center gap-2 lg:gap-3 relative">
                        <div class="text-sm hidden lg:block">
                            <span style="color:#9ca3af;">Hi, </span>
                            <span class="font-semibold" style="color:#5E4B8B;">{{ Auth::user()->name ?? 'Guest' }}</span>
                        </div>

                        @php $user = Auth::user(); @endphp

                        @if($user && $user->profile_photo)
                            <img
                                id="profileButton"
                                src="{{ asset('storage/' . $user->profile_photo) }}"
                                class="w-9 h-9 rounded-full cursor-pointer object-cover"
                                style="border:2px solid #CDB4DB;"
                                onclick="toggleDropdown()">
                        @else
                            <div
                                id="profileButton"
                                onclick="toggleDropdown()"
                                class="w-9 h-9 rounded-full cursor-pointer flex items-center justify-center"
                                style="background:#EDE4F5; border:2px solid #CDB4DB;">
                                <i class="ti ti-user" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                            </div>
                        @endif

                        <!-- DROPDOWN -->
                        <div
                            id="profileDropdown"
                            class="hidden absolute right-0 top-12 w-44 bg-white rounded-xl p-2 z-[60]"
                            style="border:1.5px solid #EDE4F5; box-shadow:0 4px 20px rgba(159,134,192,0.15);">

                            <a href="{{ route('profile.index') }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition"
                               style="color:#5E4B8B;"
                               onmouseover="this.style.background='#EDE4F5'"
                               onmouseout="this.style.background=''">
                                <i class="ti ti-user-circle" style="font-size:16px;" aria-hidden="true"></i>
                                Profil Saya
                            </a>

                            <a href="{{ route('settings.index') }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition lg:hidden"
                               style="color:#5E4B8B;"
                               onmouseover="this.style.background='#EDE4F5'"
                               onmouseout="this.style.background=''">
                                <i class="ti ti-settings" style="font-size:16px;" aria-hidden="true"></i>
                                Pengaturan
                            </a>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button
                                    class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-red-500 transition"
                                    onmouseover="this.style.background='#fef2f2'"
                                    onmouseout="this.style.background=''">
                                    <i class="ti ti-logout" style="font-size:16px;" aria-hidden="true"></i>
                                    Logout
                                </button>
                            </form>

                        </div>
                    </div>

                </div>

            </header>

            <!-- CONTENT -->
            <main class="main-content p-4 lg:p-10">
                @yield('content')
            </main>

        </div>

    </div>

    <!-- BOTTOM NAVIGATION (Mobile only) -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white z-50" style="border-top:1.5px solid #EDE4F5; box-shadow:0 -2px 12px rgba(159,134,192,0.08);">
        <div class="flex items-center justify-around py-2 px-2">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl transition {{ request()->routeIs('home') || request()->routeIs('dashboard') ? '' : '' }}"
               style="{{ request()->routeIs('home') || request()->routeIs('dashboard') ? 'color:#9F86C0;' : 'color:#9ca3af;' }}">
                <i class="ti ti-layout-dashboard" style="font-size:22px;" aria-hidden="true"></i>
                <span class="text-[10px] font-medium">Home</span>
            </a>
            <a href="{{ route('hewan-saya') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl transition"
               style="{{ request()->routeIs('hewan-saya') || request()->routeIs('pets.*') ? 'color:#9F86C0;' : 'color:#9ca3af;' }}">
                <i class="ti ti-paw" style="font-size:22px;" aria-hidden="true"></i>
                <span class="text-[10px] font-medium">Hewan</span>
            </a>
            <a href="{{ route('chatbot.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-xl transition">
                <div class="w-11 h-11 rounded-full flex items-center justify-center -mt-5 shadow-lg" style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);">
                    <i class="ti ti-message-dots text-white" style="font-size:22px;" aria-hidden="true"></i>
                </div>
                <span class="text-[10px] font-medium" style="color:#9F86C0;">AI Chat</span>
            </a>
            <a href="/pengingat" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl transition"
               style="{{ request()->is('pengingat*') ? 'color:#9F86C0;' : 'color:#9ca3af;' }}">
                <i class="ti ti-bell" style="font-size:22px;" aria-hidden="true"></i>
                <span class="text-[10px] font-medium">Pengingat</span>
            </a>
            <a href="{{ route('profile.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl transition"
               style="{{ request()->is('profil*') ? 'color:#9F86C0;' : 'color:#9ca3af;' }}">
                <i class="ti ti-user-circle" style="font-size:22px;" aria-hidden="true"></i>
                <span class="text-[10px] font-medium">Profil</span>
            </a>
        </div>
    </nav>

    <script>
        // Profile Dropdown
        function toggleDropdown() {
            document.getElementById('profileDropdown').classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profileDropdown');
            const profileBtn = document.getElementById('profileButton');
            if (profileBtn && dropdown && !profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Mobile Sidebar
        function openMobileSidebar() {
            document.getElementById('mobileSidebarOverlay').classList.remove('hidden');
            document.getElementById('mobileSidebarPanel').style.transform = 'translateX(0)';
            document.body.style.overflow = 'hidden';
        }
        function closeMobileSidebar() {
            document.getElementById('mobileSidebarOverlay').classList.add('hidden');
            document.getElementById('mobileSidebarPanel').style.transform = 'translateX(-100%)';
            document.body.style.overflow = '';
        }
    </script>

    @stack('scripts')

</body>
</html>
