@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div>

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold" style="color:#2D1B69;">Pengaturan</h1>
        <p class="text-sm mt-1" style="color:#9ca3af;">Kelola preferensi dan pengaturan akun Anda</p>
    </div>

    @if(session('success'))
    <div class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium mb-6"
        style="background:#f0fdf4; border:1.5px solid #bbf7d0; color:#15803d;">
        <span class="flex items-center gap-2">
            <i class="ti ti-circle-check" style="font-size:18px;"></i>
            {{ session('success') }}
        </span>
        <button onclick="this.parentElement.remove()" style="color:#15803d;">
            <i class="ti ti-x" style="font-size:16px;"></i>
        </button>
    </div>
    @endif

    {{-- Settings Menu --}}
    <div class="bg-white rounded-2xl overflow-hidden"
        style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">

        @php
        $menus = [
        ['href' => route('profile.index'), 'icon' => 'ti-user-circle', 'bg' => '#EDE4F5', 'color' => '#9F86C0', 'title' => 'Profil Saya', 'desc' => 'Kelola informasi profil Anda'],
        ['href' => route('settings.notifications'), 'icon' => 'ti-bell', 'bg' => '#EDE4F5', 'color' => '#9F86C0', 'title' => 'Notifikasi', 'desc' => 'Atur preferensi notifikasi'],
        ['href' => route('settings.security'), 'icon' => 'ti-lock', 'bg' => '#EDE4F5', 'color' => '#9F86C0', 'title' => 'Keamanan', 'desc' => 'Ubah password dan keamanan akun'],
        ['href' => route('settings.privacy'), 'icon' => 'ti-shield-lock', 'bg' => '#EDE4F5', 'color' => '#9F86C0', 'title' => 'Privasi', 'desc' => 'Kelola privasi dan data pribadi'],
        ['href' => route('settings.help'), 'icon' => 'ti-help-circle', 'bg' => '#EDE4F5', 'color' => '#9F86C0', 'title' => 'Bantuan & Dukungan', 'desc' => 'Dapatkan bantuan dan dukungan'],
        ];
        @endphp

        @foreach($menus as $i => $menu)
        <a href="{{ $menu['href'] }}"
            class="flex items-center justify-between p-5 transition"
            @style(['border-bottom:1.5px solid #EDE4F5' => !$loop->last])
            onmouseover="this.style.background='#FDFAFF'"
            onmouseout="this.style.background=''">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center flex-shrink-0"
                    style="background:#EDE4F5;">
                    <i class="ti {{ $menu['icon'] }}" style="font-size:20px; color:#9F86C0;" aria-hidden="true"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-sm" style="color:#2D1B69;">{{ $menu['title'] }}</h3>
                    <p class="text-xs mt-0.5" style="color:#9ca3af;">{{ $menu['desc'] }}</p>
                </div>
            </div>
            <i class="ti ti-chevron-right" style="font-size:18px; color:#CDB4DB;" aria-hidden="true"></i>
        </a>
        @endforeach

        {{-- Divider --}}
        <div style="border-top:4px solid #EDE4F5;"></div>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                onclick="return confirm('Yakin ingin keluar?')"
                class="flex items-center justify-between p-5 w-full text-left transition"
                onmouseover="this.style.background='#fef2f2'"
                onmouseout="this.style.background=''">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center flex-shrink-0"
                        style="background:#fef2f2;">
                        <i class="ti ti-logout" style="font-size:20px; color:#ef4444;" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-sm" style="color:#ef4444;">Keluar</h3>
                        <p class="text-xs mt-0.5" style="color:#9ca3af;">Keluar dari akun Anda</p>
                    </div>
                </div>
                <i class="ti ti-chevron-right" style="font-size:18px; color:#CDB4DB;" aria-hidden="true"></i>
            </button>
        </form>

    </div>

    {{-- App Info --}}
    <div class="mt-8 text-center">
        <p class="text-xs" style="color:#CDB4DB;">IngonCare v1.0.0 · © 2025 IngonCare</p>
    </div>

</div>
@endsection