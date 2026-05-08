@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md p-6 space-y-6">
        <h1 class="text-3xl font-bold text-teal-600">IngonCare</h1>

        <nav class="space-y-4 mt-10">
            <a href="#" class="flex items-center space-x-3 text-teal-600 font-semibold">
                <span>🏠</span>
                <span>Dashboard</span>
            </a>
            <a href="#" class="flex items-center space-x-3 text-gray-600 hover:text-teal-600">
                <span>🐾</span>
                <span>Hewan Saya</span>
            </a>
            <a href="#" class="flex items-center space-x-3 text-gray-600 hover:text-teal-600">
                <span>📈</span>
                <span>Riwayat Kesehatan</span>
            </a>
            <a href="#" class="flex items-center space-x-3 text-gray-600 hover:text-teal-600">
                <span>⏰</span>
                <span>Pengingat</span>
            </a>
            <a href="#" class="flex items-center space-x-3 text-gray-600 hover:text-teal-600">
                <span>👤</span>
                <span>Profil Saya</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10 space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div class="w-1/2">
                <input type="text" placeholder="Search" class="w-full px-4 py-2 rounded-full border border-gray-300" />
            </div>
            <div class="flex items-center space-x-4">
                <span>🔔</span>
                <div class="flex items-center space-x-2">
                    <span>Hi, Welcome</span>
                    <strong>Jisoo</strong>
                    @php $user = Auth::user(); @endphp

                    @if($user && $user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}"
                        class="w-10 h-10 rounded-full object-cover">
                    @else
                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                        👤
                    </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Add Discussion -->
        <div class="bg-white p-6 rounded-xl shadow">
            <div class="flex items-center space-x-4">
                @if(Auth::user()->profile_photo)
                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                    class="w-12 h-12 rounded-full object-cover">
                @else
                <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center">
                    👤
                </div>
                @endif

                <input type="text" placeholder="Tambahkan diskusi" class="flex-1 border rounded-xl px-4 py-2" />
                <button class="px-4 py-2 bg-teal-500 text-white rounded-xl">Posting</button>
            </div>
        </div>

        <!-- Posts List -->
        @foreach (range(1,2) as $i)
        <div class="flex items-center space-x-3">
            @if(Auth::user()->profile_photo)
                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                    class="w-10 h-10 rounded-full object-cover">
            @else
                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                    👤
                </div>
            @endif

            <div>
                <h3 class="font-semibold">Rizka Amalia</h3>
                <p class="text-sm text-gray-500">30 November 2025 — 2 Jam lalu</p>
            </div>
        </div>

        <h2 class="mt-4 font-bold text-lg">Pengalaman Vaksinasi Anjing Pertama Kali</h2>

        <p class="mt-2 text-gray-700">Ada yang punya pengalaman anjingnya sakit gara-gara enggak vaksin? Milo baru saja saya vaksinasi dan dia terlihat lemas. Apakah ini normal?</p>

        <div class="flex space-x-2 mt-3 text-sm">
            <span class="px-3 py-1 bg-gray-200 rounded-full">#VaksinasiAnjing</span>
            <span class="px-3 py-1 bg-gray-200 rounded-full">#TipsKesehatan</span>
            <span class="px-3 py-1 bg-gray-200 rounded-full">#GoldenRetriever</span>
        </div>

        <div class="flex justify-between mt-4 text-gray-600">
            <div class="flex space-x-4">
                <span>❤️ 67</span>
                <span>💬 34</span>
            </div>
            <button class="text-sm">🔗 Bagikan</button>
        </div>
        @endforeach
    </main>

    <!-- Right Sidebar -->
    <aside class="w-80 p-6 space-y-6">
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="font-bold text-lg mb-4">Hewan Peliharaan Saya</h2>
            <div class="space-y-4">
                <div class="p-4 bg-gray-100 rounded-xl">
                    <p class="font-semibold">Meng</p>
                    <p class="text-sm text-gray-600">Kucing - Persia</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-xl">
                    <p class="font-semibold">Selow</p>
                    <p class="text-sm text-gray-600">Kura-kura - Sulcata</p>
                </div>
                <button class="w-full py-2 bg-white border rounded-xl text-teal-500">+ Tambah Hewan</button>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="font-bold text-lg mb-4">Tren Diskusi</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span>Vaksinasi Anjing</span>
                    <span class="text-teal-500">12 Komentar</span>
                </div>
                <div class="flex justify-between">
                    <span>Diet Kucing Gemuk</span>
                    <span class="text-teal-500">8 Komentar</span>
                </div>
                <div class="flex justify-between">
                    <span>Perawatan Anjing Senior</span>
                    <span class="text-teal-500">3 Komentar</span>
                </div>
            </div>
            <a href="#" class="block mt-4 text-teal-600 text-sm">Lihat Semua Forum →</a>
        </div>
    </aside>
</div>
@endsection