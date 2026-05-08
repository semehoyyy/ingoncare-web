@extends('layouts.app')

@section('title', 'Tambah Riwayat Kesehatan')

@section('content')
<div class="">

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('riwayat') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold">Tambah Riwayat Kesehatan</h1>
        </div>
        <p class="text-gray-600 text-sm ml-9">Catat hasil pemeriksaan kesehatan hewan peliharaan Anda</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border p-6 md:p-8">
        
        <form action="{{ route('riwayat.store') }}" method="POST">
            @csrf

            <!-- ✅ PILIH HEWAN (DROPDOWN) -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pilih Hewan Peliharaan <span class="text-red-500">*</span>
                </label>
                
                @if($pets->isEmpty())
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <p class="text-yellow-800 text-sm">
                            ⚠️ Anda belum memiliki hewan peliharaan terdaftar. 
                            <a href="{{ route('pets.create') }}" class="font-semibold underline">Daftar hewan dulu</a>
                        </p>
                    </div>
                @else
                    <select name="pet_id" id="pet_id" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4EC4CE] focus:border-transparent @error('pet_id') border-red-500 @enderror"
                            onchange="fillPetData()">
                        <option value="">-- Pilih Hewan --</option>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->id }}" 
                                    data-name="{{ $pet->name }}"
                                    data-species="{{ $pet->species }}"
                                    data-breed="{{ $pet->breed }}"
                                    data-gender="{{ $pet->gender }}"
                                    data-age="{{ $pet->age }}">
                                {{ $pet->name }} - {{ $pet->species }}
                            </option>
                        @endforeach
                    </select>
                    @error('pet_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <!-- ✅ INFO HEWAN (Auto-fill, Read-only) -->
            <div id="pet-info" class="hidden mb-6 p-4 bg-cyan-50 border border-cyan-200 rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-3">📋 Informasi Hewan</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-gray-600">Nama:</p>
                        <p id="info-name" class="font-semibold">-</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Spesies/Ras:</p>
                        <p id="info-species" class="font-semibold">-</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Jenis Kelamin:</p>
                        <p id="info-gender" class="font-semibold">-</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Umur:</p>
                        <p id="info-age" class="font-semibold">-</p>
                    </div>
                </div>
            </div>

            <!-- Tanggal Pemeriksaan -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tanggal Pemeriksaan <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_pemeriksaan" value="{{ old('tanggal_pemeriksaan', date('Y-m-d')) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4EC4CE] focus:border-transparent @error('tanggal_pemeriksaan') border-red-500 @enderror">
                @error('tanggal_pemeriksaan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Diagnosis -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Diagnosis <span class="text-red-500">*</span>
                </label>
                <input type="text" name="diagnosis" value="{{ old('diagnosis') }}" required placeholder="Contoh: Cacingan, Flu Kucing, dll" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4EC4CE] focus:border-transparent @error('diagnosis') border-red-500 @enderror">
                @error('diagnosis')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tindakan -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tindakan yang Dilakukan <span class="text-red-500">*</span>
                </label>
                <textarea name="tindakan" rows="3" required placeholder="Contoh: Pemberian obat cacing, Vaksinasi, dll" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4EC4CE] focus:border-transparent @error('tindakan') border-red-500 @enderror">{{ old('tindakan') }}</textarea>
                @error('tindakan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Dokter -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Dokter <span class="text-red-500">*</span>
                </label>
                <input type="text" name="dokter" value="{{ old('dokter') }}" required placeholder="Contoh: Dr. Denis" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4EC4CE] focus:border-transparent @error('dokter') border-red-500 @enderror">
                @error('dokter')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Catatan Tambahan
                </label>
                <textarea name="catatan" rows="3" placeholder="Catatan khusus atau instruksi dari dokter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4EC4CE] focus:border-transparent @error('catatan') border-red-500 @enderror">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jadwal Pemeriksaan Berikutnya -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Jadwal Pemeriksaan Berikutnya
                </label>
                <input type="date" name="jadwal_berikutnya" value="{{ old('jadwal_berikutnya') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4EC4CE] focus:border-transparent @error('jadwal_berikutnya') border-red-500 @enderror">
                <p class="text-gray-500 text-xs mt-1">Opsional - Isi jika ada jadwal kontrol berikutnya</p>
                @error('jadwal_berikutnya')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-[#4EC4CE] text-white px-6 py-3 rounded-lg shadow-md hover:bg-[#3bb3bd] transition font-medium">
                    <i class="fas fa-save mr-2"></i> Simpan Riwayat
                </button>
                <a href="{{ route('riwayat') }}" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium text-gray-700">
                    Batal
                </a>
            </div>

        </form>

    </div>

</div>

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script>
function fillPetData() {
    const select = document.getElementById('pet_id');
    const option = select.options[select.selectedIndex];
    const petInfo = document.getElementById('pet-info');
    
    if (option.value) {
        // Show & Fill Info
        petInfo.classList.remove('hidden');
        document.getElementById('info-name').textContent = option.dataset.name || '-';
        document.getElementById('info-species').textContent = 
            (option.dataset.species || '-') + (option.dataset.breed ? ' (' + option.dataset.breed + ')' : '');
        document.getElementById('info-gender').textContent = option.dataset.gender || '-';
        document.getElementById('info-age').textContent = option.dataset.age || '-';
    } else {
        // Hide if no selection
        petInfo.classList.add('hidden');
    }
}
</script>

@endsection