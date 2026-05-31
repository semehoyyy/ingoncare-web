@extends('layouts.app')

@section('title', 'Pengaturan Notifikasi')

@section('content')
<div>

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('settings.index') }}"
           class="w-10 h-10 rounded-full flex items-center justify-center transition"
           style="background:#EDE4F5;"
           onmouseover="this.style.background='#CDB4DB'"
           onmouseout="this.style.background='#EDE4F5'">
            <i class="ti ti-arrow-left" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold" style="color:#2D1B69;">Notifikasi</h1>
            <p class="text-sm mt-0.5" style="color:#9ca3af;">Kelola bagaimana Anda menerima notifikasi</p>
        </div>
    </div>

    @if(session('success'))
    <div class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium mb-6"
         style="background:#f0fdf4; border:1.5px solid #bbf7d0; color:#15803d;">
        <span class="flex items-center gap-2">
            <i class="ti ti-circle-check" style="font-size:18px;"></i>
            {{ session('success') }}
        </span>
        <button onclick="this.parentElement.remove()">
            <i class="ti ti-x" style="font-size:16px;"></i>
        </button>
    </div>
    @endif

    <form action="{{ route('settings.notifications.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Push Notifications --}}
        <div class="bg-white rounded-2xl overflow-hidden"
             style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="px-5 py-4 flex items-center gap-2"
                 style="background:linear-gradient(135deg,#EDE4F5,#CDB4DB); border-bottom:1.5px solid #CDB4DB;">
                <i class="ti ti-bell-ringing" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
                <h2 class="font-bold" style="color:#5E4B8B;">Push Notifications</h2>
            </div>

            <div class="divide-y" style="border-color:#EDE4F5;">

                <div class="flex items-center justify-between px-5 py-4 transition"
                     onmouseover="this.style.background='#FDFAFF'"
                     onmouseout="this.style.background=''">
                    <div>
                        <h4 class="font-semibold text-sm" style="color:#2D1B69;">Aktifkan Notifikasi</h4>
                        <p class="text-xs mt-0.5" style="color:#9ca3af;">Terima notifikasi dari IngonCare</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                        <input type="checkbox" name="push_enabled" value="1"
                               {{ $settings->push_enabled ? 'checked' : '' }} class="sr-only peer">
                        <div class="{{ $settings->push_enabled ? 'bg-[#9F86C0]' : 'bg-gray-300' }} w-11 h-6 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#9F86C0]"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between px-5 py-4 transition"
                     onmouseover="this.style.background='#FDFAFF'"
                     onmouseout="this.style.background=''">
                    <div>
                        <h4 class="font-semibold text-sm" style="color:#2D1B69;">Likes & Reaksi</h4>
                        <p class="text-xs mt-0.5" style="color:#9ca3af;">Notifikasi saat ada yang menyukai postingan Anda</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                        <input type="checkbox" name="notif_likes" value="1"
                               {{ $settings->notif_likes ? 'checked' : '' }} class="sr-only peer">
                        <div class="{{ $settings->notif_likes ? 'bg-[#9F86C0]' : 'bg-gray-300' }} w-11 h-6 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#9F86C0]"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between px-5 py-4 transition"
                     onmouseover="this.style.background='#FDFAFF'"
                     onmouseout="this.style.background=''">
                    <div>
                        <h4 class="font-semibold text-sm" style="color:#2D1B69;">Komentar & Balasan</h4>
                        <p class="text-xs mt-0.5" style="color:#9ca3af;">Notifikasi saat ada yang membalas postingan Anda</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                        <input type="checkbox" name="notif_comments" value="1"
                               {{ $settings->notif_comments ? 'checked' : '' }} class="sr-only peer">
                        <div class="{{ $settings->notif_comments ? 'bg-[#9F86C0]' : 'bg-gray-300' }} w-11 h-6 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#9F86C0]"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between px-5 py-4 transition"
                     onmouseover="this.style.background='#FDFAFF'"
                     onmouseout="this.style.background=''">
                    <div>
                        <h4 class="font-semibold text-sm" style="color:#2D1B69;">Pengingat Perawatan</h4>
                        <p class="text-xs mt-0.5" style="color:#9ca3af;">Notifikasi pengingat vaksinasi dan perawatan hewan</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                        <input type="checkbox" name="notif_reminders" value="1"
                               {{ $settings->notif_reminders ? 'checked' : '' }} class="sr-only peer">
                        <div class="{{ $settings->notif_reminders ? 'bg-[#9F86C0]' : 'bg-gray-300' }} w-11 h-6 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#9F86C0]"></div>
                    </label>
                </div>

            </div>
        </div>

        {{-- Email Notifications --}}
        <div class="bg-white rounded-2xl overflow-hidden"
             style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="px-5 py-4 flex items-center gap-2"
                 style="background:linear-gradient(135deg,#EDE4F5,#CDB4DB); border-bottom:1.5px solid #CDB4DB;">
                <i class="ti ti-mail" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
                <h2 class="font-bold" style="color:#5E4B8B;">Email Notifications</h2>
            </div>

            <div class="divide-y" style="border-color:#EDE4F5;">

                <div class="flex items-center justify-between px-5 py-4 transition"
                     onmouseover="this.style.background='#FDFAFF'"
                     onmouseout="this.style.background=''">
                    <div>
                        <h4 class="font-semibold text-sm" style="color:#2D1B69;">Ringkasan Mingguan</h4>
                        <p class="text-xs mt-0.5" style="color:#9ca3af;">Terima ringkasan aktivitas setiap minggu via email</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                        <input type="checkbox" name="email_weekly" value="1"
                               {{ $settings->email_weekly ? 'checked' : '' }} class="sr-only peer">
                        <div class="{{ $settings->email_weekly ? 'bg-[#9F86C0]' : 'bg-gray-300' }} w-11 h-6 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#9F86C0]"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between px-5 py-4 transition"
                     onmouseover="this.style.background='#FDFAFF'"
                     onmouseout="this.style.background=''">
                    <div>
                        <h4 class="font-semibold text-sm" style="color:#2D1B69;">Tips & Update</h4>
                        <p class="text-xs mt-0.5" style="color:#9ca3af;">Terima tips perawatan hewan dan update fitur</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                        <input type="checkbox" name="email_tips" value="1"
                               {{ $settings->email_tips ? 'checked' : '' }} class="sr-only peer">
                        <div class="{{ $settings->email_tips ? 'bg-[#9F86C0]' : 'bg-gray-300' }} w-11 h-6 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#9F86C0]"></div>
                    </label>
                </div>

            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('settings.index') }}"
               class="px-6 py-3 rounded-xl text-sm font-semibold transition"
               style="border:1.5px solid #CDB4DB; color:#9F86C0;"
               onmouseover="this.style.background='#EDE4F5'"
               onmouseout="this.style.background=''">
                Batal
            </a>
            <button type="submit"
                    class="flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white transition"
                    style="background:#9F86C0;"
                    onmouseover="this.style.background='#5E4B8B'"
                    onmouseout="this.style.background='#9F86C0'">
                <i class="ti ti-device-floppy" style="font-size:16px;" aria-hidden="true"></i>
                Simpan Pengaturan
            </button>
        </div>

    </form>
</div>
@endsection