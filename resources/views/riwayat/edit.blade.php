@extends('layouts.app')

@section('title', 'Edit Riwayat Kesehatan')

@section('content')
<div class="">

    <!-- Header -->
    <div class="bg-[#13CAD6] text-white p-5 rounded-xl mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('riwayat') }}"
               class="w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 transition flex items-center justify-center">
                <i class="fas fa-arrow-left text-white"></i>
            </a>

            <div>
                <h1 class="text-xl font-bold">Edit Riwayat Kesehatan</h1>
                <p class="text-sm opacity-90">
                    Perbarui informasi kesehatan hewan peliharaan Anda
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border p-6 md:p-8">

        <form action="{{ route('riwayat.update', $riwayat->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Informasi Hewan (Auto Fill dari Data Hewan) -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Hewan Peliharaan
                </label>

                <input type="text"
                       value="{{ $riwayat->pet->name ?? '-' }}"
                       readonly
                       class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed">
            </div>

            <!-- Spesies -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Spesies / Ras
                </label>

                <input type="text"
                       value="{{ $riwayat->pet->species ?? '-' }} {{ $riwayat->pet->breed ? '(' . $riwayat->pet->breed . ')' : '' }}"
                       readonly
                       class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed">
            </div>

            <!-- Jenis Hewan -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Jenis Hewan
                </label>

                <input type="text"
                       value="{{ $riwayat->pet->species ?? '-' }}"
                       readonly
                       class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed">
            </div>

            <!-- Jenis Kelamin -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Jenis Kelamin
                </label>

                <input type="text"
                       value="{{ $riwayat->pet->gender ?? '-' }}"
                       readonly
                       class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed">
            </div>

            <!-- Umur -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Umur Hewan
                </label>

                <input type="text"
                       value="{{ $riwayat->pet->age ?? '-' }}"
                       readonly
                       class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed">
            </div>

            <!-- Tanggal Pemeriksaan -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tanggal Pemeriksaan <span class="text-red-500">*</span>
                </label>

                <input type="date"
                       name="tanggal_pemeriksaan"
                       value="{{ old('tanggal_pemeriksaan', $riwayat->tanggal_pemeriksaan?->format('Y-m-d')) }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4EC4CE] focus:border-transparent @error('tanggal_pemeriksaan') border-red-500 @enderror">

                @error('tanggal_pemeriksaan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Diagnosis -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Diagnosis <span class="text-red-500">*</span>
                </label>

                <input type="text"
                       name="diagnosis"
                       value="{{ old('diagnosis', $riwayat->diagnosis) }}"
                       required
                       placeholder="Contoh: Cacingan, Flu Kucing, dll"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4EC4CE] focus:border-transparent @error('diagnosis') border-red-500 @enderror">

                @error('diagnosis')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tindakan -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tindakan yang Dilakukan <span class="text-red-500">*</span>
                </label>

                <textarea name="tindakan"
                          rows="3"
                          required
                          placeholder="Contoh: Pemberian obat cacing, Vaksinasi, dll"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4EC4CE] focus:border-transparent @error('tindakan') border-red-500 @enderror">{{ old('tindakan', $riwayat->tindakan) }}</textarea>

                @error('tindakan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Dokter -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Dokter <span class="text-red-500">*</span>
                </label>

                <input type="text"
                       name="dokter"
                       value="{{ old('dokter', $riwayat->dokter) }}"
                       required
                       placeholder="Contoh: Dr. Denis"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4EC4CE] focus:border-transparent @error('dokter') border-red-500 @enderror">

                @error('dokter')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Catatan Tambahan
                </label>

                <textarea name="catatan"
                          rows="3"
                          placeholder="Catatan khusus atau instruksi dari dokter"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4EC4CE] focus:border-transparent @error('catatan') border-red-500 @enderror">{{ old('catatan', $riwayat->catatan) }}</textarea>

                @error('catatan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jadwal Berikutnya -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Jadwal Pemeriksaan Berikutnya
                </label>

                <input type="date"
                       name="jadwal_berikutnya"
                       value="{{ old('jadwal_berikutnya', $riwayat->jadwal_berikutnya?->format('Y-m-d')) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4EC4CE] focus:border-transparent @error('jadwal_berikutnya') border-red-500 @enderror">

                <p class="text-gray-500 text-xs mt-1">
                    Opsional - Isi jika ada jadwal kontrol berikutnya
                </p>

                @error('jadwal_berikutnya')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit"
                        class="flex-1 bg-[#4EC4CE] text-white px-6 py-3 rounded-lg shadow-md hover:bg-[#3bb3bd] transition font-medium">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>

                <a href="{{ route('riwayat') }}"
                   class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium text-gray-700">
                    Batal
                </a>
            </div>

        </form>

    </div>

</div>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@endsection