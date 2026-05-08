@extends('layouts.app')

@section('title', 'Bahasa')

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
                <h1 class="text-2xl font-bold text-gray-900">Bahasa</h1>
                <p class="text-sm text-gray-500">Pilih bahasa aplikasi</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center justify-between">
            <span>✓ {{ session('success') }}</span>
            <button onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    <form action="{{ route('settings.language.update') }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-yellow-50">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <span class="text-2xl">🌐</span>
                    Pilih Bahasa
                </h3>
            </div>

            <div class="p-6 space-y-3">
                <label class="flex items-center justify-between p-4 hover:bg-cyan-50 rounded-xl transition border-2 cursor-pointer
                              {{ $settings->language == 'id' ? 'border-cyan-500 bg-cyan-50' : 'border-gray-200' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🇮🇩</span>
                        <div>
                            <h4 class="font-semibold text-gray-900">Bahasa Indonesia</h4>
                            <p class="text-sm text-gray-500">Indonesian</p>
                        </div>
                    </div>
                    <input type="radio" name="language" value="id" 
                           {{ $settings->language == 'id' ? 'checked' : '' }}
                           class="w-5 h-5 text-cyan-600">
                </label>

                <label class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition border-2 cursor-pointer
                              {{ $settings->language == 'en' ? 'border-cyan-500 bg-cyan-50' : 'border-gray-200' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🇺🇸</span>
                        <div>
                            <h4 class="font-semibold text-gray-900">English</h4>
                            <p class="text-sm text-gray-500">English (United States)</p>
                        </div>
                    </div>
                    <input type="radio" name="language" value="en"
                           {{ $settings->language == 'en' ? 'checked' : '' }}
                           class="w-5 h-5 text-cyan-600">
                </label>

                <label class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition border-2 cursor-pointer border-gray-200 opacity-50">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🇯🇵</span>
                        <div>
                            <h4 class="font-semibold text-gray-900">日本語</h4>
                            <p class="text-sm text-gray-500">Japanese (Coming Soon)</p>
                        </div>
                    </div>
                    <input type="radio" name="language" value="jp" disabled class="w-5 h-5 text-gray-400">
                </label>
            </div>

            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('settings.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-xl font-semibold hover:from-cyan-600 hover:to-blue-600 transition shadow">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection