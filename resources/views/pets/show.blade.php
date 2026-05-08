{{-- resources/views/pets/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Hewan')

@section('content')
<div class="">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('hewan-saya') }}"
            class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold">{{ $pet->name }}</h1>
            <p class="text-sm text-gray-500">{{ $pet->species }} {{ $pet->breed ? '• ' . $pet->breed : '' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Foto & Info Singkat -->
        <div class="bg-white rounded-2xl shadow-sm border p-6 text-center">
            <div class="w-32 h-32 rounded-full mx-auto overflow-hidden bg-gray-100 border-4 border-[#13CAD6] mb-4">
                @if($pet->photo)
                    <img src="{{ asset('storage/' . $pet->photo) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-5xl">🐾</div>
                @endif
            </div>

            <h2 class="text-2xl font-bold text-gray-900">{{ $pet->name }}</h2>
            <p class="text-gray-500 mt-1">{{ $pet->species }}{{ $pet->breed ? ' - ' . $pet->breed : '' }}</p>

            <div class="flex justify-center gap-2 mt-3">
                @if($pet->gender == 'Jantan')
                    <span class="px-3 py-1 text-sm bg-blue-100 text-blue-600 rounded-full">♂ Jantan</span>
                @else
                    <span class="px-3 py-1 text-sm bg-pink-100 text-pink-600 rounded-full">♀ Betina</span>
                @endif

                @if($pet->age)
                    <span class="px-3 py-1 text-sm bg-green-100 text-green-600 rounded-full">{{ $pet->age }}</span>
                @endif
            </div>

            <div class="flex gap-2 mt-6">
                <a href="{{ route('pets.edit', $pet->id) }}"
                    class="flex-1 py-2 bg-[#13CAD6] text-white rounded-xl text-sm font-medium hover:bg-[#0fb3c2] transition">
                    Edit
                </a>
                <form action="{{ route('pets.destroy', $pet->id) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus?')" class="flex-1">
                    @csrf @method('DELETE')
                    <button class="w-full py-2 bg-red-100 text-red-600 rounded-xl text-sm font-medium hover:bg-red-200 transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <!-- Detail Info -->
        <div class="md:col-span-2 space-y-4">

            <!-- Informasi Dasar -->
            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <span class="text-xl">📋</span> Informasi Dasar
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Lahir</p>
                        <p class="font-semibold">
                            {{ $pet->birth_date ? \Carbon\Carbon::parse($pet->birth_date)->format('d F Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Umur</p>
                        <p class="font-semibold">{{ $pet->age ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Berat</p>
                        <p class="font-semibold">{{ $pet->weight ? $pet->weight . ' kg' : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status Steril</p>
                        <p class="font-semibold">{{ $pet->is_steril ? 'Sudah' : 'Belum' }}</p>
                    </div>
                </div>
            </div>

            <!-- Kesehatan -->
            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <span class="text-xl">🏥</span> Informasi Kesehatan
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Ciri Khusus</p>
                        <p class="font-medium text-gray-800">{{ $pet->special_marks ?: 'Tidak ada' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Alergi</p>
                        <p class="font-medium text-gray-800">{{ $pet->allergies ?: 'Tidak ada' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kondisi Kesehatan Khusus</p>
                        <p class="font-medium text-gray-800">{{ $pet->health_notes ?: 'Tidak ada' }}</p>
                    </div>
                </div>
            </div>

            <!-- Link ke Riwayat -->
            <a href="{{ route('riwayat') }}"
                class="block bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-2xl p-5 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-lg">📈 Riwayat Kesehatan</h3>
                        <p class="text-cyan-100 text-sm">Lihat semua catatan pemeriksaan</p>
                    </div>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

        </div>
    </div>

</div>
@endsection