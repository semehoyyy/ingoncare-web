@extends('layouts.app')

@section('title', 'Pengaturan Notifikasi')

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
                <h1 class="text-2xl font-bold text-gray-900">Pengaturan Notifikasi</h1>
                <p class="text-sm text-gray-500">Kelola bagaimana anda menerima notifikasi</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center justify-between">
            <span>✓ {{ session('success') }}</span>
            <button onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    <form action="{{ route('settings.notifications.update') }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-cyan-50">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <span class="text-2xl">🔔</span>
                    Push Notifications
                </h3>
            </div>

            <div class="p-5 space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div>
                        <h4 class="font-semibold text-gray-900">Aktifkan Notifikasi</h4>
                        <p class="text-sm text-gray-500">Terima notifikasi dari IngonCare</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="push_enabled" value="1" 
                               {{ $settings->push_enabled ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-cyan-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition">
                    <div>
                        <h4 class="font-semibold text-gray-900">Likes & Reaksi</h4>
                        <p class="text-sm text-gray-500">Notifikasi saat ada yang menyukai postingan anda</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="notif_likes" value="1"
                               {{ $settings->notif_likes ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-cyan-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition">
                    <div>
                        <h4 class="font-semibold text-gray-900">Komentar & Balasan</h4>
                        <p class="text-sm text-gray-500">Notifikasi saat ada yang membalas postingan anda</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="notif_comments" value="1"
                               {{ $settings->notif_comments ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-cyan-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition">
                    <div>
                        <h4 class="font-semibold text-gray-900">Pengingat Perawatan</h4>
                        <p class="text-sm text-gray-500">Notifikasi pengingat vaksinasi dan perawatan hewan</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="notif_reminders" value="1"
                               {{ $settings->notif_reminders ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-cyan-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-600"></div>
                    </label>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <span class="text-2xl">📧</span>
                    Email Notifications
                </h3>
            </div>

            <div class="p-5 space-y-4">
                <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition">
                    <div>
                        <h4 class="font-semibold text-gray-900">Ringkasan Mingguan</h4>
                        <p class="text-sm text-gray-500">Terima ringkasan aktivitas setiap minggu via email</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_weekly" value="1"
                               {{ $settings->email_weekly ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-cyan-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition">
                    <div>
                        <h4 class="font-semibold text-gray-900">Tips & Update</h4>
                        <p class="text-sm text-gray-500">Terima tips perawatan hewan dan update fitur</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_tips" value="1"
                               {{ $settings->email_tips ? 'checked' : '' }}
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
                Simpan Pengaturan
            </button>
        </div>

    </form>

</div>
@endsection