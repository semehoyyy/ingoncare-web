@extends('layouts.app')

@section('title', 'Keamanan')

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
            <h1 class="text-2xl font-bold" style="color:#2D1B69;">Keamanan</h1>
            <p class="text-sm mt-0.5" style="color:#9ca3af;">Kelola password dan keamanan akun Anda</p>
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

        {{-- Ubah Password --}}
        <div class="bg-white rounded-2xl overflow-hidden"
             style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="px-5 py-4 flex items-center gap-2"
                 style="background:linear-gradient(135deg,#EDE4F5,#CDB4DB); border-bottom:1.5px solid #CDB4DB;">
                <i class="ti ti-lock" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
                <h2 class="font-bold" style="color:#5E4B8B;">Ubah Password</h2>
            </div>

            <form action="{{ route('settings.security.password') }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase"
                           style="color:#5E4B8B;">
                        Password Saat Ini <span style="color:#ef4444;">*</span>
                    </label>
                    <div class="relative">
                        <i class="ti ti-lock absolute left-4 top-1/2 -translate-y-1/2"
                           style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="password" name="current_password" required
                               placeholder="Masukkan password saat ini"
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                               style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                               onfocus="this.style.borderColor='#9F86C0'"
                               onblur="this.style.borderColor='#CDB4DB'">
                    </div>
                    @error('current_password')
                        <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase"
                           style="color:#5E4B8B;">
                        Password Baru <span style="color:#ef4444;">*</span>
                    </label>
                    <div class="relative">
                        <i class="ti ti-key absolute left-4 top-1/2 -translate-y-1/2"
                           style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="password" name="password" required
                               placeholder="Masukkan password baru"
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                               style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                               onfocus="this.style.borderColor='#9F86C0'"
                               onblur="this.style.borderColor='#CDB4DB'">
                    </div>
                    @error('password')
                        <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                    @enderror
                    <p class="text-xs mt-1" style="color:#9ca3af;">Minimal 8 karakter</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase"
                           style="color:#5E4B8B;">
                        Konfirmasi Password Baru <span style="color:#ef4444;">*</span>
                    </label>
                    <div class="relative">
                        <i class="ti ti-key absolute left-4 top-1/2 -translate-y-1/2"
                           style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="password" name="password_confirmation" required
                               placeholder="Konfirmasi password baru"
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                               style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                               onfocus="this.style.borderColor='#9F86C0'"
                               onblur="this.style.borderColor='#CDB4DB'">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
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
                        <i class="ti ti-shield-check" style="font-size:16px;" aria-hidden="true"></i>
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>

        {{-- Info Keamanan --}}
        <div class="bg-white rounded-2xl overflow-hidden"
             style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="px-5 py-4 flex items-center gap-2"
                 style="background:linear-gradient(135deg,#EDE4F5,#CDB4DB); border-bottom:1.5px solid #CDB4DB;">
                <i class="ti ti-shield" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
                <h2 class="font-bold" style="color:#5E4B8B;">Informasi Keamanan</h2>
            </div>

            <div class="p-5 space-y-4">
                <div class="p-4 rounded-xl" style="background:#EDE4F5; border:1.5px solid #CDB4DB;">
                    <div class="flex items-start gap-3">
                        <i class="ti ti-info-circle flex-shrink-0 mt-0.5"
                           style="font-size:18px; color:#9F86C0;" aria-hidden="true"></i>
                        <div>
                            <h4 class="font-semibold text-sm mb-2" style="color:#2D1B69;">Tips Password Aman</h4>
                            <ul class="text-sm space-y-1" style="color:#5E4B8B;">
                                <li class="flex items-center gap-1.5">
                                    <i class="ti ti-point-filled" style="font-size:10px; color:#9F86C0;"></i>
                                    Gunakan minimal 8 karakter
                                </li>
                                <li class="flex items-center gap-1.5">
                                    <i class="ti ti-point-filled" style="font-size:10px; color:#9F86C0;"></i>
                                    Kombinasikan huruf besar dan kecil
                                </li>
                                <li class="flex items-center gap-1.5">
                                    <i class="ti ti-point-filled" style="font-size:10px; color:#9F86C0;"></i>
                                    Tambahkan angka dan simbol
                                </li>
                                <li class="flex items-center gap-1.5">
                                    <i class="ti ti-point-filled" style="font-size:10px; color:#9F86C0;"></i>
                                    Jangan gunakan informasi pribadi
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 rounded-xl"
                     style="background:#FDFAFF; border:1.5px solid #EDE4F5;">
                    <div>
                        <p class="text-sm font-semibold" style="color:#2D1B69;">Terakhir Diubah</p>
                        <p class="text-xs mt-0.5" style="color:#9ca3af;">
                            {{ \Carbon\Carbon::parse($user->updated_at)->format('d F Y') }}
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                         style="background:#EDE4F5;">
                        <i class="ti ti-circle-check" style="font-size:20px; color:#9F86C0;" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2FA Coming Soon --}}
        <div class="bg-white rounded-2xl overflow-hidden"
             style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="px-5 py-4 flex items-center gap-2"
                 style="background:linear-gradient(135deg,#EDE4F5,#CDB4DB); border-bottom:1.5px solid #CDB4DB;">
                <i class="ti ti-device-mobile" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
                <h2 class="font-bold" style="color:#5E4B8B;">Autentikasi Dua Faktor</h2>
                <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-medium"
                      style="background:white; color:#9F86C0; border:1px solid #CDB4DB;">
                    Segera Hadir
                </span>
            </div>
            <div class="p-5">
                <p class="text-sm mb-4" style="color:#9ca3af;">
                    Tingkatkan keamanan akun Anda dengan autentikasi dua faktor. Fitur ini akan segera tersedia.
                </p>
                <button disabled
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold cursor-not-allowed"
                        style="background:#EDE4F5; color:#CDB4DB;">
                    Aktifkan (Segera Hadir)
                </button>
            </div>
        </div>

    </div>
</div>
@endsection