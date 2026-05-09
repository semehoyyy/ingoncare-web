@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pengaturan</h1>
        <p class="text-gray-500">Kelola preferensi dan pengaturan anda</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center justify-between">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">✕</button>
        </div>
    @endif

    {{-- Settings Menu --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Profil Saya --}}
        <a href="{{ route('profile.index') }}" 
           class="flex items-center justify-between p-5 hover:bg-cyan-50 transition border-b border-gray-100 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-400 to-cyan-600 flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Profil Saya</h3>
                    <p class="text-sm text-gray-500">Kelola informasi profil anda</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-cyan-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        {{-- Notifikasi --}}
        <a href="{{ route('settings.notifications') }}" 
           class="flex items-center justify-between p-5 hover:bg-cyan-50 transition border-b border-gray-100 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Notifikasi</h3>
                    <p class="text-sm text-gray-500">Atur preferensi notifikasi</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-cyan-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        {{-- Keamanan --}}
        <a href="{{ route('settings.security') }}" 
           class="flex items-center justify-between p-5 hover:bg-cyan-50 transition border-b border-gray-100 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Keamanan</h3>
                    <p class="text-sm text-gray-500">Ubah password dan keamanan akun</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-cyan-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        {{-- Privasi --}}
        <a href="{{ route('settings.privacy') }}" 
           class="flex items-center justify-between p-5 hover:bg-cyan-50 transition border-b border-gray-100 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Privasi</h3>
                    <p class="text-sm text-gray-500">Kelola privasi dan data pribadi</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-cyan-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        {{-- Bantuan & Dukungan --}}
        <a href="{{ route('settings.help') }}" 
           class="flex items-center justify-between p-5 hover:bg-cyan-50 transition border-b border-gray-100 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Bantuan & Dukungan</h3>
                    <p class="text-sm text-gray-500">Dapatkan bantuan dan dukungan</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-cyan-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        {{-- Divider --}}
        <div class="border-t-4 border-gray-100"></div>

        {{-- Keluar --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
                    onclick="return confirm('Yakin ingin keluar?')"
                    class="flex items-center justify-between p-5 hover:bg-red-50 transition w-full text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-red-600">Keluar</h3>
                        <p class="text-sm text-gray-500">Keluar dari akun anda</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </form>

    </div>

    {{-- App Info --}}
    <div class="mt-8 text-center text-sm text-gray-500">
        <p>IngonCare v1.0.0</p>
        <p class="mt-1">© 2025 IngonCare. All rights reserved.</p>
    </div>

</div>
@endsection