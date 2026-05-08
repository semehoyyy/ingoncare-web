@extends('layouts.app')

@section('title', 'Hewan Saya')

@section('content')
<div class="">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Hewan Peliharaan Saya</h1>
            <p class="text-gray-600 text-sm">Kelola informasi dan kesehatan hewan peliharaan Anda</p>
        </div>

        <a href="{{ route('pets.create') }}">
            <button class="bg-[#4EC4CE] text-white px-6 py-2 rounded-lg shadow-md hover:bg-[#3bb3bd] transition">
                + Tambah Hewan
            </button>
        </a>
    </div>

    <!-- Total Hewan -->
    <div class="mt-6">
        <div class="bg-white rounded-2xl shadow-sm px-6 py-4 border w-60">
            <p class="text-gray-600">Total Hewan</p>
            <div class="flex justify-between items-center mt-2">
                <h2 class="text-2xl font-bold">{{ $totalHewan }}</h2>
                <div class="w-12 h-12 bg-[#DDF7FB] rounded-xl flex items-center justify-center text-xl">
                    🐾
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Hewan -->
    <div class="mt-8">
        <h2 class="text-lg font-semibold mb-3">Daftar Hewan</h2>

        @if ($pets->count() == 0)
            <p class="text-gray-500 text-sm">Belum ada hewan yang ditambahkan.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                @foreach ($pets as $pet)
                <div class="rounded-2xl border bg-white shadow-sm hover:shadow-md transition p-5">

                    <!-- Header Card -->
                    <div class="flex items-center gap-4">

                        <!-- Foto -->
                        <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                            @if ($pet->photo)
                                <img src="{{ asset('storage/' . $pet->photo) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-3xl">🐾</span>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-xl font-bold">{{ $pet->name }}</h3>

                            <p class="text-gray-600 text-sm">
                                {{ $pet->species }}
                                @if($pet->breed) • {{ $pet->breed }} @endif
                            </p>

                            <!-- Gender + Age -->
                            <div class="flex items-center gap-2 mt-1">

                                @if ($pet->gender == 'Jantan')
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-600 rounded-md flex items-center gap-1">
                                        ♂ Jantan
                                    </span>
                                @elseif ($pet->gender == 'Betina')
                                    <span class="px-2 py-1 text-xs bg-pink-100 text-pink-600 rounded-md flex items-center gap-1">
                                        ♀ Betina
                                    </span>
                                @endif

                                @if ($pet->age)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded-md">
                                        {{ $pet->age }}
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>


                    <!-- Karakteristik Fisik -->
                    <!-- Ciri Khusus -->
                    <div class="bg-gray-50 p-4 rounded-xl mt-4 text-sm">
                        <p class="font-medium text-gray-700 mb-1">Ciri Khusus</p>

                        @if ($pet->special_marks)
                            <p class="text-gray-600">• {{ $pet->special_marks }}</p>
                        @else
                            <p class="text-gray-500">Belum ada ciri khusus.</p>
                        @endif
                    </div>

                
                    <!-- Informasi Kesehatan Singkat -->
                    <div class="bg-gray-50 p-4 rounded-xl mt-4 text-sm">

                        @if ($pet->health_notes || $pet->allergies || $pet->is_steril !== null)
                            <p class="font-medium text-gray-700 mb-1">Informasi Kesehatan</p>

                            {{-- STATUS STERIL --}}
                            <p class="text-gray-600">
                                • Status Steril: {{ (int)$pet->is_steril === 1 ? 'Sudah' : 'Belum' }}
                            </p>


                            @if ($pet->allergies)
                                <p class="text-gray-600">• Alergi: {{ $pet->allergies }}</p>
                            @endif

                            @if ($pet->health_notes)
                                <p class="text-gray-600">• Kondisi Kesehatan Khusus: {{ $pet->health_notes }}</p>
                            @endif

                        @else
                            <p class="text-gray-500">Belum ada informasi kesehatan.</p>
                        @endif

                    </div>


                    <!-- Tombol -->
                    <div class="flex justify-between mt-4 text-sm">
                        <a href="{{ route('pets.show', $pet->id) }}" class="text-[#2A8CCB] hover:underline">Riwayat</a>
                        <a href="{{ route('pets.edit', $pet->id) }}" class="text-yellow-600 hover:underline">Edit</a>

                        <form action="{{ route('pets.destroy', $pet->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </div>

                </div>
                @endforeach

            </div>
        @endif
    </div>

</div>
@endsection
