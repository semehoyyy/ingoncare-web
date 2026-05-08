<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IngonCare')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-[#F8FAFC]">

    <div class="flex">

        <!-- SIDEBAR -->
        <aside class="w-64 h-screen bg-white border-r fixed px-6 py-6 z-50">

            <h1 class="text-2xl font-bold text-[#13CAD6] mb-10">
                IngonCare
            </h1>

            <nav class="space-y-5">

                <a href="{{ route('home') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition
                   {{ request()->is('home')
                     ? 'bg-[#E5FAF7] text-[#13CAD6] font-semibold border-l-4 border-[#13CAD6]'
                     : 'text-gray-700 hover:bg-gray-100 hover:text-[#1A9C8C]' }}">

                    🏠 Dashboard

                </a>

                <a href="{{ route('hewan-saya') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition
                   {{ request()->is('hewan-saya')
                     ? 'bg-[#E5FAF7] text-[#13CAD6] font-semibold border-l-4 border-[#13CAD6]'
                     : 'text-gray-700 hover:bg-gray-100 hover:text-[#1A9C8C]' }}">

                    🐾 Hewan Saya

                </a>

                <a href="/riwayat"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition
                   {{ request()->is('riwayat')
                     ? 'bg-[#E5FAF7] text-[#13CAD6] font-semibold border-l-4 border-[#13CAD6]'
                     : 'text-gray-700 hover:bg-gray-100 hover:text-[#1A9C8C]' }}">

                    📈 Riwayat Kesehatan

                </a>

                <a href="/pengingat"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition
                   {{ request()->is('pengingat')
                     ? 'bg-[#E5FAF7] text-[#13CAD6] font-semibold border-l-4 border-[#13CAD6]'
                     : 'text-gray-700 hover:bg-gray-100 hover:text-[#1A9C8C]' }}">

                    ⏰ Pengingat

                </a>

                {{-- PROFILE --}}
                <a href="{{ route('profile.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition
                   {{ request()->is('profil*')
                     ? 'bg-[#E5FAF7] text-[#13CAD6] font-semibold border-l-4 border-[#13CAD6]'
                     : 'text-gray-700 hover:bg-gray-100 hover:text-[#1A9C8C]' }}">

                    👤 Profil Saya

                </a>

                {{-- CHATBOT --}}
                <a href="{{ route('chatbot.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition
                   {{ request()->is('chatbot*')
                     ? 'bg-[#E5FAF7] text-[#13CAD6] font-semibold border-l-4 border-[#13CAD6]'
                     : 'text-gray-700 hover:bg-gray-100 hover:text-[#1A9C8C]' }}">

                    🤖 Chatbot AI

                </a>

            </nav>

        </aside>

        <!-- MAIN -->
        <div class="ml-64 w-full">

            <!-- HEADER -->
            <header class="flex items-center justify-between px-10 py-6 bg-white shadow-sm relative">

                {{-- SEARCH HANYA DI DASHBOARD --}}
                @if(request()->routeIs('home'))

                    <div class="w-1/3">

                        <form action="{{ route('search') }}"
                              method="GET"
                              class="relative">

                            <input
                                type="text"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Cari diskusi atau nama pengguna..."
                                class="w-full px-4 py-2 pr-10 bg-[#F1F5F9] rounded-full border focus:ring-[#1A9C8C] focus:border-[#1A9C8C] focus:outline-none">

                            <button
                                type="submit"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#1A9C8C] transition">

                                <svg class="w-5 h-5"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                                </svg>

                            </button>

                        </form>

                    </div>

                @else

                    {{-- SPACER --}}
                    <div></div>

                @endif

                <!-- RIGHT SIDE -->
                <div class="flex items-center gap-6">

                    {{-- NOTIFICATION --}}
                    <a href="{{ route('notifications.index') }}"
                       class="relative text-xl">

                        🔔

                        @php
                            $count = \App\Models\Notification::where('user_id', auth()->id())
                                ->where('is_read', false)
                                ->count();
                        @endphp

                        @if($count > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1 rounded-full">
                                {{ $count }}
                            </span>
                        @endif

                    </a>

                    {{-- SETTINGS --}}
                    <a href="{{ route('settings.index') }}"
                       class="text-xl cursor-pointer hover:opacity-70 transition">

                        ⚙️

                    </a>

                    {{-- PROFILE --}}
                    <div class="flex items-center gap-3 relative">

                        <p>
                            Hi, Welcome
                            <span class="font-semibold">
                                {{ Auth::user()->name ?? 'Guest' }}
                            </span>
                        </p>

                        @php
                            $user = Auth::user();
                        @endphp

                        @if($user && $user->profile_photo)

                            <img
                                id="profileButton"
                                src="{{ asset('storage/' . $user->profile_photo) }}"
                                class="w-10 h-10 rounded-full cursor-pointer object-cover border-2 border-gray-200"
                                onclick="toggleDropdown()">

                        @else

                            <div
                                id="profileButton"
                                onclick="toggleDropdown()"
                                class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center cursor-pointer text-gray-600">

                                👤

                            </div>

                        @endif

                        <!-- DROPDOWN -->
                        <div
                            id="profileDropdown"
                            class="hidden absolute right-0 top-14 w-40 bg-white shadow-lg rounded-xl p-2 z-[60]">

                            <a href="{{ route('profile.index') }}"
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">

                                Profil Saya

                            </a>

                            <form action="{{ route('logout') }}" method="POST">

                                @csrf

                                <button
                                    class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-100 rounded-lg">

                                    Logout

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </header>

            <!-- CONTENT -->
            <main class="p-10">
                @yield('content')
            </main>

        </div>

    </div>

    <!-- SCRIPT -->
    <script>

        function toggleDropdown() {
            const menu = document.getElementById('profileDropdown');
            menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {

            const dropdown = document.getElementById('profileDropdown');
            const profileBtn = document.getElementById('profileButton');

            if (
                profileBtn &&
                dropdown &&
                !profileBtn.contains(e.target) &&
                !dropdown.contains(e.target)
            ) {
                dropdown.classList.add('hidden');
            }

        });

    </script>

    @stack('scripts')

</body>

</html>