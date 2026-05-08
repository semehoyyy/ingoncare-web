@extends('layouts.app')

@section('title', 'Tampilan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('settings.index') }}" 
               class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tampilan</h1>
                <p class="text-sm text-gray-500">Sesuaikan tema dan tampilan aplikasi</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center justify-between">
            <span>✓ {{ session('success') }}</span>
            <button onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    <form action="{{ route('settings.appearance.update') }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-pink-50 to-purple-50">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <span class="text-2xl">🎨</span>
                    Mode Tema
                </h3>
            </div>

            <div class="p-6 grid grid-cols-3 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" name="theme" value="light" 
                           {{ $settings->theme === 'light' ? 'checked' : '' }}
                           class="peer sr-only">
                    <div class="p-6 border-2 border-gray-200 peer-checked:border-cyan-500 peer-checked:bg-cyan-50 rounded-xl transition text-center">
                        <div class="w-16 h-16 mx-auto mb-3 bg-white border-2 border-gray-300 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold">Light Mode</h4>
                        <p class="text-xs text-gray-500 mt-1">Mode terang</p>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="theme" value="dark"
                           {{ $settings->theme === 'dark' ? 'checked' : '' }}
                           class="peer sr-only">
                    <div class="p-6 border-2 border-gray-200 peer-checked:border-cyan-500 peer-checked:bg-cyan-50 rounded-xl transition text-center">
                        <div class="w-16 h-16 mx-auto mb-3 bg-gray-900 border-2 border-gray-700 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold">Dark Mode</h4>
                        <p class="text-xs text-gray-500 mt-1">Mode gelap</p>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="theme" value="auto"
                           {{ $settings->theme === 'auto' ? 'checked' : '' }}
                           class="peer sr-only">
                    <div class="p-6 border-2 border-gray-200 peer-checked:border-cyan-500 peer-checked:bg-cyan-50 rounded-xl transition text-center">
                        <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-r from-white to-gray-900 border-2 border-gray-300 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold">Auto</h4>
                        <p class="text-xs text-gray-500 mt-1">Sistem</p>
                    </div>
                </label>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-cyan-50">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <span class="text-2xl">⚙️</span>
                    Preferensi Tampilan
                </h3>
            </div>

            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition">
                    <div>
                        <h4 class="font-semibold text-gray-900">Animasi</h4>
                        <p class="text-sm text-gray-500">Aktifkan animasi halaman</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="animations_enabled" value="1"
                               {{ $settings->animations_enabled ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-cyan-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition">
                    <div>
                        <h4 class="font-semibold text-gray-900">Mode Kompak</h4>
                        <p class="text-sm text-gray-500">Tampilan lebih ringkas</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="compact_mode" value="1"
                               {{ $settings->compact_mode ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-cyan-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-600"></div>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('settings.index') }}" 
               class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-xl font-semibold hover:from-cyan-600 hover:to-blue-600 transition shadow">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection