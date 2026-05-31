@extends('layouts.app')

@section('title', 'Tambah Pengingat')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="text-white p-5 rounded-2xl mb-6" style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.2);">
                <i class="ti ti-bell-plus text-white" style="font-size:20px;" aria-hidden="true"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold">Tambah Pengingat Baru</h1>
                <p class="text-sm opacity-80">Kelola jadwal perawatan hewan peliharaan Anda</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
        <form action="{{ route('pengingat.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Pilih Hewan --}}
            <div>
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                    Pilih Hewan
                </label>
                @if($pets->isEmpty())
                    <div class="p-4 rounded-xl text-sm" style="background:#EDE4F5; color:#5E4B8B;">
                        <i class="ti ti-alert-triangle mr-1" style="font-size:14px;" aria-hidden="true"></i>
                        Belum ada hewan terdaftar.
                        <a href="{{ route('pets.create') }}" class="underline font-semibold">Tambah hewan dulu</a>
                    </div>
                @else
                    <select name="pet_id" required
                        class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none"
                        style="border:1.5px solid #CDB4DB; background:#FDFAFF; color:#2D1B69;">
                        <option value="">-- Pilih Hewan --</option>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->id }}">{{ $pet->name }} - {{ $pet->species }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            {{-- Jenis Perawatan --}}
            <div>
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                    Jenis Perawatan
                </label>
                <input type="text" name="kategori" placeholder="Contoh: Vaksinasi"
                    class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none"
                    style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                    required>
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                    Tanggal
                </label>
                <div class="relative">
                    <i class="ti ti-calendar absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                    <input type="date" name="tanggal" required
                        class="w-full rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none"
                        style="border:1.5px solid #CDB4DB; background:#FDFAFF;">
                </div>
            </div>

            {{-- Waktu --}}
            <div>
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                    Waktu
                </label>
                <div class="relative">
                    <i class="ti ti-clock absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                    <input type="time" name="waktu" required
                        class="w-full rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none"
                        style="border:1.5px solid #CDB4DB; background:#FDFAFF;">
                </div>
            </div>

            {{-- Catatan --}}
            <div>
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                    Catatan (opsional)
                </label>
                <textarea name="deskripsi" placeholder="Tambahkan catatan..."
                    class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none resize-none"
                    style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                    rows="3"></textarea>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 justify-end pt-2">
                <a href="{{ route('pengingat.list') }}"
                    class="px-5 py-2.5 rounded-xl text-sm font-semibold transition"
                    style="border:1.5px solid #CDB4DB; color:#9F86C0;"
                    onmouseover="this.style.background='#EDE4F5'"
                    onmouseout="this.style.background=''">
                    Batal
                </a>
                <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition"
                    style="background:#9F86C0;"
                    onmouseover="this.style.background='#5E4B8B'"
                    onmouseout="this.style.background='#9F86C0'">
                    <i class="ti ti-device-floppy" style="font-size:16px;" aria-hidden="true"></i>
                    Simpan Pengingat
                </button>
            </div>

        </form>
    </div>

</div>
@endsection