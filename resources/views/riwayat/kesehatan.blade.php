@extends('layouts.app')

@section('title', 'Riwayat Kesehatan')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Kesehatan</h1>
            <p class="text-gray-500 text-sm mt-1">
                Catatan lengkap pemeriksaan kesehatan hewan peliharaan Anda
            </p>
        </div>

        <a href="{{ route('riwayat.create') }}"
           class="bg-[#4EC4CE] text-white px-5 py-2.5 rounded-lg shadow hover:bg-[#3bb3bd] transition flex items-center gap-2">
            <span class="text-lg">+</span>
            Tambah Riwayat Kesehatan 
        </a>
    </div>

    <!-- STATISTIK -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm text-gray-500 mb-2">Total Pemeriksaan</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalPemeriksaan }}</p>
            </div>
            <div class="absolute right-5 bottom-5 opacity-50">
                <img src="{{ asset('img/paw.png') }}" alt="" class="w-7 h-7 object-contain">
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm text-gray-500 mb-2">Hewan Diperiksa</p>
                <p class="text-3xl font-bold text-gray-800">{{ $hewanDiperiksa }}</p>
            </div>
            <div class="absolute right-5 bottom-5 opacity-50">
                <img src="{{ asset('img/paw.png') }}" alt="" class="w-7 h-7 object-contain">
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm text-gray-500 mb-2">Bulan Ini</p>
                <p class="text-3xl font-bold text-gray-800">{{ $bulanIni }}</p>
            </div>
            <div class="absolute right-5 bottom-5 opacity-50">
                <img src="{{ asset('img/paw.png') }}" alt="" class="w-7 h-7 object-contain">
            </div>
        </div>
    </div>

    <!-- LIST RIWAYAT -->
    @if ($riwayats->isEmpty())
        <div class="text-center py-12">
            <p class="text-gray-400 text-sm">Belum ada riwayat kesehatan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @foreach ($riwayats as $riwayat)
                @php
                    $pet = $riwayat->pet; // ✅ Ambil data hewan dari relasi
                @endphp
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">

                    <!-- HEADER CARD dengan Background Warna -->
                    <div class="bg-gradient-to-br from-[#FFF8E7] to-[#FFF3DB] px-6 py-5">
                        <div class="flex justify-between items-start">
                            <div class="flex gap-4 items-center">
                                <!-- Icon Hewan -->
                                <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center text-3xl">
                                    @if($pet && strtolower($pet->species) == 'kucing')
                                        🐱
                                    @elseif($pet && strtolower($pet->species) == 'anjing')
                                        🐶
                                    @elseif($pet && strtolower($pet->species) == 'kelinci')
                                        🐰
                                    @elseif($pet && strtolower($pet->species) == 'burung')
                                        🦜
                                    @else
                                        🐾
                                    @endif
                                </div>

                                <div>
                                    <h3 class="font-bold text-lg text-gray-800 leading-tight">
                                        {{ $pet ? $pet->name : 'Hewan Dihapus' }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-0.5">
                                        {{ $pet ? $pet->species : '-' }} 
                                        @if($pet && $pet->breed) - {{ $pet->breed }} @endif
                                    </p>
                                    <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                        @if($pet)
                                            <!-- Icon Gender -->
                                            <div class="flex items-center gap-1">
                                                @if(strtolower($pet->gender) == 'jantan' || strtolower($pet->gender) == 'male')
                                                    <img src="{{ asset('img/male.png') }}" alt="" class="w-3 h-3 object-contain">
                                                @else
                                                    <img src="{{ asset('img/female.png') }}" alt="" class="w-3 h-3 object-contain">
                                                @endif
                                                <span>{{ $pet->gender }}</span>
                                            </div>
                                            <!-- Umur -->
                                            <span>• {{ $pet->age ?? '0 tahun' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- ACTION BUTTONS -->
                            <div class="flex gap-2">
                                <a href="{{ route('riwayat.edit', $riwayat->id) }}"
                                   class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/50 transition">
                                    <img src="{{ asset('img/edit.png') }}" alt="" class="w-5 h-5 object-contain">
                                </a>

                                <form action="{{ route('riwayat.destroy', $riwayat->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus riwayat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/50 transition">
                                        <img src="{{ asset('img/delete.png') }}" alt="" class="w-5 h-5 object-contain">
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- TANGGAL -->
                        <div class="mt-4 flex items-center gap-2 text-sm text-gray-600 bg-white/60 px-3 py-2 rounded-lg inline-flex">
                            <img src="{{ asset('img/calendar.png') }}" alt="" class="w-6 h-6 object-contain">
                            {{ \Carbon\Carbon::parse($riwayat->tanggal_pemeriksaan)->format('d F Y') }}
                        </div>
                    </div>

                    <!-- DETAIL INFORMASI -->
                    <div class="px-6 py-5 space-y-4">
                        <!-- Diagnosis -->
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <img src="{{ asset('img/diagnosa.png') }}" alt="" class="w-7 h-7 object-contain">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 mb-1">Diagnosis</p>
                                <p class="text-sm font-medium text-gray-800">{{ $riwayat->diagnosis }}</p>
                            </div>
                        </div>

                        <!-- Tindakan -->
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-pink-50 flex items-center justify-center flex-shrink-0">
                                <img src="{{ asset('img/tindakan.png') }}" alt="" class="w-7 h-7 object-contain">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 mb-1">Tindakan</p>
                                <p class="text-sm font-medium text-gray-800">{{ $riwayat->tindakan }}</p>
                            </div>
                        </div>

                        <!-- Dokter -->
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center flex-shrink-0">
                                <img src="{{ asset('img/dokter.png') }}" alt="" class="w-7 h-7 object-contain">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 mb-1">Dokter</p>
                                <p class="text-sm font-medium text-gray-800">{{ $riwayat->dokter }}</p>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
                                <img src="{{ asset('img/catatan.png') }}" alt="" class="w-7 h-7 object-contain">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 mb-1">Catatan</p>
                                <p class="text-sm font-medium text-gray-800">{{ $riwayat->catatan ?? 'Tidak ada catatan' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- JADWAL BERIKUTNYA -->
                    @if ($riwayat->jadwal_berikutnya)
                        <div class="bg-blue-50 px-6 py-4 border-t border-blue-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-500 flex items-center justify-center flex-shrink-0">
                                    <img src="{{ asset('img/Info.png') }}" alt="" class="w-7 h-7 object-contain brightness-0 invert">
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Jadwal Pemeriksaan Berikutnya</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-0.5">
                                        {{ \Carbon\Carbon::parse($riwayat->jadwal_berikutnya)->format('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            @endforeach

        </div>
    @endif

</div>
@endsection