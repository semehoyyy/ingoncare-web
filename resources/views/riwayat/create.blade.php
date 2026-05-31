@extends('layouts.app')

@section('title', 'Tambah Riwayat Kesehatan')

@section('content')
<div>

    {{-- BANNER --}}
    <div class="text-white px-6 py-5 rounded-2xl mb-6"
         style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                 style="background:rgba(255,255,255,0.2);">
                <i class="ti ti-clipboard-plus text-white" style="font-size:22px;" aria-hidden="true"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold">Tambah Riwayat Kesehatan</h1>
                <p class="text-sm" style="color:#EDE4F5;">Catat hasil pemeriksaan kesehatan hewan peliharaan Anda</p>
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <div class="bg-white rounded-2xl p-6 md:p-8"
         style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">

        <form action="{{ route('riwayat.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- PILIH HEWAN --}}
            <div>
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                    Pilih Hewan Peliharaan <span style="color:#ef4444;">*</span>
                </label>

                @if($pets->isEmpty())
                    <div class="p-4 rounded-xl flex items-center gap-3"
                         style="background:#EDE4F5; border:1.5px solid #CDB4DB;">
                        <i class="ti ti-alert-triangle" style="font-size:18px; color:#9F86C0;" aria-hidden="true"></i>
                        <p class="text-sm" style="color:#5E4B8B;">
                            Belum ada hewan terdaftar.
                            <a href="{{ route('pets.create') }}" class="font-bold underline" style="color:#9F86C0;">Tambah hewan dulu</a>
                        </p>
                    </div>
                @else
                    <div class="relative">
                        <i class="ti ti-paw absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <select name="pet_id" id="pet_id" required
                                class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none appearance-none @error('pet_id') border-red-400 @enderror"
                                style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                                onfocus="this.style.borderColor='#9F86C0'"
                                onblur="this.style.borderColor='#CDB4DB'"
                                onchange="fillPetData()">
                            <option value="">-- Pilih Hewan --</option>
                            @foreach($pets as $pet)
                                <option value="{{ $pet->id }}"
                                        data-name="{{ $pet->name }}"
                                        data-species="{{ $pet->species }}"
                                        data-breed="{{ $pet->breed }}"
                                        data-gender="{{ $pet->gender }}"
                                        data-age="{{ $pet->age }}">
                                    {{ $pet->name }} — {{ $pet->species }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('pet_id')
                        <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            {{-- INFO HEWAN (Auto-fill) --}}
            <div id="pet-info" class="hidden p-4 rounded-xl"
                 style="background:#EDE4F5; border:1.5px solid #CDB4DB;">
                <div class="flex items-center gap-2 mb-3">
                    <i class="ti ti-info-circle" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                    <h3 class="font-semibold text-sm" style="color:#5E4B8B;">Informasi Hewan</h3>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p style="color:#9ca3af;">Nama</p>
                        <p id="info-name" class="font-semibold" style="color:#2D1B69;">-</p>
                    </div>
                    <div>
                        <p style="color:#9ca3af;">Spesies / Ras</p>
                        <p id="info-species" class="font-semibold" style="color:#2D1B69;">-</p>
                    </div>
                    <div>
                        <p style="color:#9ca3af;">Jenis Kelamin</p>
                        <p id="info-gender" class="font-semibold" style="color:#2D1B69;">-</p>
                    </div>
                    <div>
                        <p style="color:#9ca3af;">Umur</p>
                        <p id="info-age" class="font-semibold" style="color:#2D1B69;">-</p>
                    </div>
                </div>
            </div>

            {{-- TANGGAL PEMERIKSAAN --}}
            <div>
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                    Tanggal Pemeriksaan <span style="color:#ef4444;">*</span>
                </label>
                <div class="relative">
                    <i class="ti ti-calendar absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                    <input type="date" name="tanggal_pemeriksaan"
                           value="{{ old('tanggal_pemeriksaan', date('Y-m-d')) }}" required
                           class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none @error('tanggal_pemeriksaan') border-red-400 @enderror"
                           style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                           onfocus="this.style.borderColor='#9F86C0'"
                           onblur="this.style.borderColor='#CDB4DB'">
                </div>
                @error('tanggal_pemeriksaan')
                    <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                @enderror
            </div>

            {{-- DIAGNOSIS --}}
            <div>
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                    Diagnosis <span style="color:#ef4444;">*</span>
                </label>
                <div class="relative">
                    <i class="ti ti-stethoscope absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                    <input type="text" name="diagnosis" value="{{ old('diagnosis') }}" required
                           placeholder="Contoh: Cacingan, Flu Kucing, dll"
                           class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none @error('diagnosis') border-red-400 @enderror"
                           style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                           onfocus="this.style.borderColor='#9F86C0'"
                           onblur="this.style.borderColor='#CDB4DB'">
                </div>
                @error('diagnosis')
                    <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                @enderror
            </div>

            {{-- TINDAKAN --}}
            <div>
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                    Tindakan yang Dilakukan <span style="color:#ef4444;">*</span>
                </label>
                <div class="relative">
                    <i class="ti ti-activity absolute left-4 top-3.5" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                    <textarea name="tindakan" rows="3" required
                              placeholder="Contoh: Pemberian obat cacing, Vaksinasi, dll"
                              class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none resize-none @error('tindakan') border-red-400 @enderror"
                              style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                              onfocus="this.style.borderColor='#9F86C0'"
                              onblur="this.style.borderColor='#CDB4DB'">{{ old('tindakan') }}</textarea>
                </div>
                @error('tindakan')
                    <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                @enderror
            </div>

            {{-- NAMA DOKTER --}}
            <div>
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                    Nama Dokter <span style="color:#ef4444;">*</span>
                </label>
                <div class="relative">
                    <i class="ti ti-user-heart absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                    <input type="text" name="dokter" value="{{ old('dokter') }}" required
                           placeholder="Contoh: Dr. Denis"
                           class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none @error('dokter') border-red-400 @enderror"
                           style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                           onfocus="this.style.borderColor='#9F86C0'"
                           onblur="this.style.borderColor='#CDB4DB'">
                </div>
                @error('dokter')
                    <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                @enderror
            </div>

            {{-- CATATAN --}}
            <div>
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                    Catatan Tambahan
                </label>
                <div class="relative">
                    <i class="ti ti-notes absolute left-4 top-3.5" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                    <textarea name="catatan" rows="3"
                              placeholder="Catatan khusus atau instruksi dari dokter"
                              class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none resize-none"
                              style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                              onfocus="this.style.borderColor='#9F86C0'"
                              onblur="this.style.borderColor='#CDB4DB'">{{ old('catatan') }}</textarea>
                </div>
            </div>

            {{-- JADWAL BERIKUTNYA --}}
            <div>
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                    Jadwal Pemeriksaan Berikutnya
                </label>
                <div class="relative">
                    <i class="ti ti-calendar-plus absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                    <input type="date" name="jadwal_berikutnya" value="{{ old('jadwal_berikutnya') }}"
                           class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                           style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                           onfocus="this.style.borderColor='#9F86C0'"
                           onblur="this.style.borderColor='#CDB4DB'">
                </div>
                <p class="text-xs mt-1.5" style="color:#9ca3af;">Opsional — isi jika ada jadwal kontrol berikutnya</p>
            </div>

            {{-- BUTTONS --}}
            <div class="flex gap-3 justify-end pt-2">
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
                    Simpan Riwayat
                </button>
            </div>

        </form>
    </div>

</div>

<script>
function fillPetData() {
    const select = document.getElementById('pet_id');
    const option = select.options[select.selectedIndex];
    const petInfo = document.getElementById('pet-info');

    if (option.value) {
        petInfo.classList.remove('hidden');
        document.getElementById('info-name').textContent    = option.dataset.name    || '-';
        document.getElementById('info-species').textContent = (option.dataset.species || '-') + (option.dataset.breed ? ' (' + option.dataset.breed + ')' : '');
        document.getElementById('info-gender').textContent  = option.dataset.gender  || '-';
        document.getElementById('info-age').textContent     = option.dataset.age     || '-';
    } else {
        petInfo.classList.add('hidden');
    }
}
</script>
@endsection