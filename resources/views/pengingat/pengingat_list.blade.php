@extends('layouts.app')

@section('title', 'Pengingat')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pengingat Perawatan</h1>
        <p class="text-gray-500 text-sm">Kelola jadwal perawatan hewan peliharaan Anda</p>
    </div>

    <a href="{{ route('pengingat.create') }}"
       class="px-4 py-2 bg-[#13CAD6] text-white rounded-xl shadow hover:bg-[#11b4bf] transition flex items-center gap-2">
        <span class="text-lg font-bold">+</span> Tambah Pengingat
    </a>
</div>

{{-- PENGINGAT AKTIF --}}
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h2 class="font-semibold text-gray-700 mb-4">Pengingat Aktif ({{ count($aktif) }})</h2>

    @foreach ($aktif as $item)
        <div class="p-4 border rounded-xl mb-3 hover:bg-gray-50 transition flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold">{{ $item->nama_hewan }}</h3>

                <span class="text-xs bg-[#E6F9FF] text-[#13CAD6] px-2 py-1 rounded-full">
                    {{ $item->kategori }}
                </span>

                <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        📅 {{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}
                    </div>

                    <div class="flex items-center gap-2">
                        ⏰ {{ $item->waktu }}
                    </div>
                </div>

                <p class="text-gray-500 text-sm mt-1">{{ $item->deskripsi }}</p>
            </div>

            <div class="flex items-center gap-3">

                {{-- Tandai selesai --}}
                <form action="{{ route('pengingat.selesai', $item->id) }}" method="POST">
                    @csrf
                    <button class="bg-green-200 text-green-700 p-2 rounded-lg hover:bg-green-300 transition">
                        ✔
                    </button>
                </form>

                {{-- Hapus --}}
                <form action="{{ route('pengingat.delete', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-200 text-red-700 p-2 rounded-lg hover:bg-red-300 transition">
                        🗑
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>

{{-- RIWAYAT SELESAI --}}
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h2 class="font-semibold text-gray-700 mb-4">Riwayat Selesai ({{ count($selesai) }})</h2>

    @foreach ($selesai as $item)
        <div class="p-4 border rounded-xl mb-3 hover:bg-gray-50 transition flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold">{{ $item->nama_hewan }}</h3>

                <span class="text-xs bg-[#DFF6DD] text-[#3A7A2A] px-2 py-1 rounded-full">
                    {{ $item->kategori }}
                </span>

                <span class="text-xs bg-[#E8F5E9] text-green-700 px-2 py-1 rounded-full">
                    Selesai
                </span>

                <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        📅 {{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}
                    </div>

                    <div class="flex items-center gap-2">
                        ⏰ {{ $item->waktu }}
                    </div>
                </div>
            </div>

            <form action="{{ route('pengingat.delete', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="bg-red-200 text-red-700 p-2 rounded-lg hover:bg-red-300 transition">
                    🗑
                </button>
            </form>
        </div>
    @endforeach
</div>

@endsection
