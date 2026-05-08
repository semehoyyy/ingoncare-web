@extends('layouts.app')

@section('title', 'Tambah Pengingat')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    
    {{-- HEADER BIRU --}}
    <div class="bg-[#13CAD6] text-white p-5 rounded-xl mb-6">
        <h1 class="text-xl font-bold">Tambah Pengingat Baru</h1>
        <p class="text-sm opacity-90">Kelola jadwal perawatan hewan peliharaan Anda</p>
    </div>

    {{-- FORM --}}
    <form action="{{ route('pengingat.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- NAMA HEWAN --}}
        {{-- PILIH HEWAN --}}
<div>
    <label class="block text-gray-700 font-medium mb-1">
        Pilih Hewan
    </label>

    @if($pets->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <p class="text-yellow-700 text-sm">
                ⚠️ Belum ada hewan terdaftar.
                <a href="{{ route('pets.create') }}" class="underline font-semibold">
                    Tambah hewan dulu
                </a>
            </p>
        </div>
    @else
        <select name="pet_id"
            class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-[#13CAD6] outline-none"
            required>

            <option value="">-- Pilih Hewan --</option>

            @foreach($pets as $pet)
                <option value="{{ $pet->id }}">
                    {{ $pet->name }} - {{ $pet->species }}
                </option>
            @endforeach

        </select>
    @endif
</div>

        {{-- JENIS PERAWATAN (INPUT TEXT) --}}
        <div>
            <label class="block text-gray-700 font-medium mb-1">Jenis Perawatan</label>
            <input type="text" name="kategori" 
                placeholder="Contoh: Vaksinasi"
                class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-[#13CAD6] outline-none"
                required>
        </div>

        {{-- TANGGAL --}}
        <div>
            <label class="block text-gray-700 font-medium mb-1">Tanggal</label>
            <div class="relative">
                <input type="date" name="tanggal"
                    class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-[#13CAD6] outline-none"
                    required>
                <span class="absolute right-3 top-3 text-gray-500">📅</span>
            </div>
        </div>

        {{-- WAKTU --}}
        <div>
            <label class="block text-gray-700 font-medium mb-1">Waktu</label>
            <div class="relative">
                <input type="time" name="waktu"
                    class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-[#13CAD6] outline-none"
                    required>
                <span class="absolute right-3 top-3 text-gray-500">⏰</span>
            </div>
        </div>

        {{-- CATATAN --}}
        <div>
            <label class="block text-gray-700 font-medium mb-1">Catatan</label>
            <textarea name="deskripsi"
                placeholder="Tambahkan Catatan (opsional)"
                class="w-full border rounded-xl p-3 h-32 resize-none focus:ring-2 focus:ring-[#13CAD6] outline-none"></textarea>
        </div>

        {{-- SUBMIT --}}
        <div class="flex justify-end">
            <button type="submit"
                class="bg-[#13CAD6] text-white px-6 py-3 rounded-xl hover:bg-[#11b4bf] transition shadow">
                Simpan Pengingat
            </button>
        </div>

    </form>

</div>
@endsection
