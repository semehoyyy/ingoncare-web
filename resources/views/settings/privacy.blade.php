@extends('layouts.app')

@section('title', 'Privasi')

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
            <h1 class="text-2xl font-bold" style="color:#2D1B69;">Privasi</h1>
            <p class="text-sm mt-0.5" style="color:#9ca3af;">Kelola privasi dan data pribadi Anda</p>
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

    <div class="space-y-6">

        {{-- Pengaturan Privasi --}}
        <form action="{{ route('settings.privacy.update') }}" method="POST">
            @csrf

            <div class="bg-white rounded-2xl overflow-hidden"
                 style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
                <div class="px-5 py-4 flex items-center gap-2"
                     style="background:linear-gradient(135deg,#EDE4F5,#CDB4DB); border-bottom:1.5px solid #CDB4DB;">
                    <i class="ti ti-shield-lock" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
                    <h2 class="font-bold" style="color:#5E4B8B;">Pengaturan Privasi</h2>
                </div>

                <div class="divide-y" style="border-color:#EDE4F5;">

                    <div class="flex items-center justify-between px-5 py-4 transition"
                         onmouseover="this.style.background='#FDFAFF'"
                         onmouseout="this.style.background=''">
                        <div>
                            <h4 class="font-semibold text-sm" style="color:#2D1B69;">Profil Publik</h4>
                            <p class="text-xs mt-0.5" style="color:#9ca3af;">Izinkan orang lain melihat profil Anda</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                            <input type="checkbox" name="profile_public" value="1"
                                   {{ $settings->profile_public ? 'checked' : '' }} class="sr-only peer">
                            <div class="{{ $settings->profile_public ? 'bg-[#9F86C0]' : 'bg-gray-300' }} w-11 h-6 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#9F86C0]"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between px-5 py-4 transition"
                         onmouseover="this.style.background='#FDFAFF'"
                         onmouseout="this.style.background=''">
                        <div>
                            <h4 class="font-semibold text-sm" style="color:#2D1B69;">Tampilkan Email</h4>
                            <p class="text-xs mt-0.5" style="color:#9ca3af;">Perlihatkan email di profil publik</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                            <input type="checkbox" name="show_email" value="1"
                                   {{ $settings->show_email ? 'checked' : '' }} class="sr-only peer">
                            <div class="{{ $settings->show_email ? 'bg-[#9F86C0]' : 'bg-gray-300' }} w-11 h-6 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#9F86C0]"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between px-5 py-4 transition"
                         onmouseover="this.style.background='#FDFAFF'"
                         onmouseout="this.style.background=''">
                        <div>
                            <h4 class="font-semibold text-sm" style="color:#2D1B69;">Aktivitas Online</h4>
                            <p class="text-xs mt-0.5" style="color:#9ca3af;">Tampilkan status online Anda</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                            <input type="checkbox" name="show_online_status" value="1"
                                   {{ $settings->show_online_status ? 'checked' : '' }} class="sr-only peer">
                            <div class="{{ $settings->show_online_status ? 'bg-[#9F86C0]' : 'bg-gray-300' }} w-11 h-6 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#9F86C0]"></div>
                        </label>
                    </div>

                </div>

                <div class="px-5 py-4 flex justify-end gap-3"
                     style="border-top:1.5px solid #EDE4F5; background:#FDFAFF;">
                    <a href="{{ route('settings.index') }}"
                       class="px-6 py-2.5 rounded-xl text-sm font-semibold transition"
                       style="border:1.5px solid #CDB4DB; color:#9F86C0;"
                       onmouseover="this.style.background='#EDE4F5'"
                       onmouseout="this.style.background=''">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white transition"
                            style="background:#9F86C0;"
                            onmouseover="this.style.background='#5E4B8B'"
                            onmouseout="this.style.background='#9F86C0'">
                        <i class="ti ti-device-floppy" style="font-size:15px;" aria-hidden="true"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

        {{-- Data & Akun --}}
        <div class="bg-white rounded-2xl overflow-hidden"
             style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="px-5 py-4 flex items-center gap-2"
                 style="background:linear-gradient(135deg,#EDE4F5,#CDB4DB); border-bottom:1.5px solid #CDB4DB;">
                <i class="ti ti-database" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
                <h2 class="font-bold" style="color:#5E4B8B;">Data & Akun</h2>
            </div>

            <div class="p-4 space-y-3">

                <a href="{{ route('settings.download.data') }}"
                   class="flex items-center justify-between p-4 rounded-xl transition"
                   style="border:1.5px solid #EDE4F5;"
                   onmouseover="this.style.background='#FDFAFF'; this.style.borderColor='#CDB4DB'"
                   onmouseout="this.style.background=''; this.style.borderColor='#EDE4F5'">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                             style="background:#EDE4F5;">
                            <i class="ti ti-download" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm" style="color:#2D1B69;">Download Data Saya</h4>
                            <p class="text-xs mt-0.5" style="color:#9ca3af;">Unduh semua data yang Anda miliki</p>
                        </div>
                    </div>
                    <i class="ti ti-chevron-right" style="font-size:16px; color:#CDB4DB;"></i>
                </a>

                <form action="{{ route('profile.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Yakin ingin menghapus akun? Semua data akan dihapus permanen.')"
                            class="w-full flex items-center justify-between p-4 rounded-xl transition text-left"
                            style="border:1.5px solid #fecaca;"
                            onmouseover="this.style.background='#fef2f2'; this.style.borderColor='#fca5a5'"
                            onmouseout="this.style.background=''; this.style.borderColor='#fecaca'">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                                 style="background:#fef2f2;">
                                <i class="ti ti-trash" style="font-size:16px; color:#ef4444;" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm" style="color:#ef4444;">Hapus Akun</h4>
                                <p class="text-xs mt-0.5" style="color:#9ca3af;">Hapus akun dan semua data secara permanen</p>
                            </div>
                        </div>
                        <i class="ti ti-chevron-right" style="font-size:16px; color:#fca5a5;"></i>
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection