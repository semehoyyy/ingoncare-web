@extends('layouts.app')

@section('title', 'Edit Riwayat Kesehatan')

@section('content')
<div>

    {{-- BANNER --}}
    <div class="text-white px-6 py-5 rounded-2xl flex items-center justify-between mb-6"
         style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                 style="background:rgba(255,255,255,0.2);">
                <i class="ti ti-clipboard-heart text-white" style="font-size:22px;" aria-hidden="true"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold">Edit Riwayat Kesehatan</h2>
                <p class="text-sm" style="color:#EDE4F5;">Perbarui informasi kesehatan hewan peliharaan Anda</p>
            </div>
        </div>
        <a href="{{ route('riwayat') }}"
           class="w-9 h-9 rounded-full flex items-center justify-center transition"
           style="background:rgba(255,255,255,0.2);"
           onmouseover="this.style.background='rgba(255,255,255,0.35)'"
           onmouseout="this.style.background='rgba(255,255,255,0.2)'">
            <i class="ti ti-x text-white" style="font-size:18px;" aria-hidden="true"></i>
        </a>
    </div>

    <form action="{{ route('riwayat.update', $riwayat->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- INFORMASI HEWAN (READ ONLY) --}}
        <div class="bg-white rounded-2xl p-6" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <h3 class="font-bold text-base mb-5 flex items-center gap-2" style="color:#2D1B69;">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#EDE4F5;">
                    <span class="text-xs font-bold" style="color:#9F86C0;">1</span>
                </div>
                Informasi Hewan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Nama Hewan</label>
                    <div class="relative">
                        <i class="ti ti-paw absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="text"
                               value="{{ $riwayat->pet->name ?? '-' }}"
                               readonly
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm cursor-not-allowed"
                               style="border:1.5px solid #EDE4F5; background:#F5F0FA; color:#9ca3af;">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Spesies / Ras</label>
                    <div class="relative">
                        <i class="ti ti-category absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="text"
                               value="{{ $riwayat->pet->species ?? '-' }}{{ $riwayat->pet->breed ? ' (' . $riwayat->pet->breed . ')' : '' }}"
                               readonly
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm cursor-not-allowed"
                               style="border:1.5px solid #EDE4F5; background:#F5F0FA; color:#9ca3af;">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Jenis Kelamin</label>
                    <div class="relative">
                        <i class="ti ti-gender-bigender absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="text"
                               value="{{ $riwayat->pet->gender ?? '-' }}"
                               readonly
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm cursor-not-allowed"
                               style="border:1.5px solid #EDE4F5; background:#F5F0FA; color:#9ca3af;">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Umur Hewan</label>
                    <div class="relative">
                        <i class="ti ti-clock absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="text"
                               value="{{ $riwayat->pet->age ?? '-' }}"
                               readonly
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm cursor-not-allowed"
                               style="border:1.5px solid #EDE4F5; background:#F5F0FA; color:#9ca3af;">
                    </div>
                </div>

            </div>
        </div>

        {{-- DETAIL PEMERIKSAAN --}}
        <div class="bg-white rounded-2xl p-6" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <h3 class="font-bold text-base mb-5 flex items-center gap-2" style="color:#2D1B69;">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#EDE4F5;">
                    <span class="text-xs font-bold" style="color:#9F86C0;">2</span>
                </div>
                Detail Pemeriksaan
            </h3>

            <div class="space-y-5">

                {{-- Tanggal Pemeriksaan --}}
                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                        Tanggal Pemeriksaan <span style="color:#ef4444;">*</span>
                    </label>
                    <div class="relative">
                        <i class="ti ti-calendar absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="date"
                               name="tanggal_pemeriksaan"
                               value="{{ old('tanggal_pemeriksaan', $riwayat->tanggal_pemeriksaan?->format('Y-m-d')) }}"
                               required
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                               style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                               onfocus="this.style.borderColor='#9F86C0'"
                               onblur="this.style.borderColor='#CDB4DB'">
                    </div>
                    @error('tanggal_pemeriksaan')
                        <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Diagnosis --}}
                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                        Diagnosis <span style="color:#ef4444;">*</span>
                    </label>
                    <div class="relative">
                        <i class="ti ti-stethoscope absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="text"
                               name="diagnosis"
                               value="{{ old('diagnosis', $riwayat->diagnosis) }}"
                               required
                               placeholder="Contoh: Cacingan, Flu Kucing, dll"
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                               style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                               onfocus="this.style.borderColor='#9F86C0'"
                               onblur="this.style.borderColor='#CDB4DB'">
                    </div>
                    @error('diagnosis')
                        <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tindakan --}}
                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                        Tindakan yang Dilakukan <span style="color:#ef4444;">*</span>
                    </label>
                    <div class="relative">
                        <i class="ti ti-activity absolute left-4 top-4" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <textarea name="tindakan"
                                  rows="3"
                                  required
                                  placeholder="Contoh: Pemberian obat cacing, Vaksinasi, dll"
                                  class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none resize-none"
                                  style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                                  onfocus="this.style.borderColor='#9F86C0'"
                                  onblur="this.style.borderColor='#CDB4DB'">{{ old('tindakan', $riwayat->tindakan) }}</textarea>
                    </div>
                    @error('tindakan')
                        <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Dokter --}}
                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                        Nama Dokter <span style="color:#ef4444;">*</span>
                    </label>
                    <div class="relative">
                        <i class="ti ti-user-heart absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="text"
                               name="dokter"
                               value="{{ old('dokter', $riwayat->dokter) }}"
                               required
                               placeholder="Contoh: Dr. Denis"
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                               style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                               onfocus="this.style.borderColor='#9F86C0'"
                               onblur="this.style.borderColor='#CDB4DB'">
                    </div>
                    @error('dokter')
                        <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- CATATAN & JADWAL --}}
        <div class="bg-white rounded-2xl p-6" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <h3 class="font-bold text-base mb-5 flex items-center gap-2" style="color:#2D1B69;">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#EDE4F5;">
                    <span class="text-xs font-bold" style="color:#9F86C0;">3</span>
                </div>
                Catatan &amp; Jadwal
            </h3>

            <div class="space-y-5">

                {{-- Catatan --}}
                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Catatan Tambahan</label>
                    <div class="relative">
                        <i class="ti ti-notes absolute left-4 top-4" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <textarea name="catatan"
                                  rows="3"
                                  placeholder="Catatan khusus atau instruksi dari dokter"
                                  class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none resize-none"
                                  style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                                  onfocus="this.style.borderColor='#9F86C0'"
                                  onblur="this.style.borderColor='#CDB4DB'">{{ old('catatan', $riwayat->catatan) }}</textarea>
                    </div>
                    @error('catatan')
                        <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jadwal Berikutnya --}}
                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Jadwal Pemeriksaan Berikutnya</label>
                    <div class="relative">
                        <i class="ti ti-calendar-plus absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="date"
                               name="jadwal_berikutnya"
                               value="{{ old('jadwal_berikutnya', $riwayat->jadwal_berikutnya?->format('Y-m-d')) }}"
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                               style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                               onfocus="this.style.borderColor='#9F86C0'"
                               onblur="this.style.borderColor='#CDB4DB'">
                    </div>
                    <p class="text-xs mt-1" style="color:#9ca3af;">Opsional — isi jika ada jadwal kontrol berikutnya</p>
                    @error('jadwal_berikutnya')
                        <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- BUTTONS --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('riwayat') }}"
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
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>
@endsection