@extends('layouts.app')

@section('title', 'Keamanan')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header dengan Back Button --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('settings.index') }}" 
               class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Keamanan</h1>
                <p class="text-sm text-gray-500">Kelola password dan keamanan akun anda</p>
            </div>
        </div>
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

    {{-- Change Password --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <span class="text-2xl">🔒</span>
                Ubah Password
            </h3>
            <p class="text-sm text-gray-600 mt-1">Pastikan password anda kuat dan aman</p>
        </div>

        <form action="{{ route('settings.security.password') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                {{-- Current Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Password Saat Ini <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="current_password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                           placeholder="Masukkan password saat ini"
                           required>
                    @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                           placeholder="Masukkan password baru"
                           required>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                           placeholder="Konfirmasi password baru"
                           required>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                <a href="{{ route('settings.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl font-semibold hover:from-green-600 hover:to-emerald-600 transition shadow">
                    Ubah Password
                </button>
            </div>
        </form>
    </div>

    {{-- Security Info --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-cyan-50">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <span class="text-2xl">🛡️</span>
                Informasi Keamanan
            </h3>
        </div>

        <div class="p-6 space-y-4">
            <div class="flex items-start gap-3 p-4 bg-blue-50 rounded-xl">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-1">Tips Password Aman</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Gunakan minimal 8 karakter</li>
                        <li>• Kombinasikan huruf besar dan kecil</li>
                        <li>• Tambahkan angka dan simbol</li>
                        <li>• Jangan gunakan informasi pribadi</li>
                    </ul>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                <div>
                    <h4 class="font-semibold text-gray-900">Terakhir Diubah</h4>
                    <p class="text-sm text-gray-500">Password terakhir diubah: <span class="font-medium">{{ \Carbon\Carbon::parse($user->updated_at)->format('d F Y') }}</span></p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Two Factor Authentication (Coming Soon) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <span class="text-2xl">🔐</span>
                Autentikasi Dua Faktor
                <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full font-medium">Segera Hadir</span>
            </h3>
        </div>

        <div class="p-6">
            <p class="text-gray-600 mb-4">
                Tingkatkan keamanan akun anda dengan autentikasi dua faktor. Fitur ini akan segera tersedia.
            </p>
            <button class="px-6 py-3 bg-gray-200 text-gray-500 rounded-xl font-semibold cursor-not-allowed" disabled>
                Aktifkan (Coming Soon)
            </button>
        </div>
    </div>

</div>
@endsection