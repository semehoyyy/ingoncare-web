@extends('layouts.app')

@section('title', 'Pengingat')

@section('content')
<div>

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold" style="color:#2D1B69;">Pengingat Perawatan</h1>
            <p class="text-sm mt-1" style="color:#9ca3af;">Kelola jadwal perawatan hewan peliharaan Anda</p>
        </div>
        <a href="{{ route('pengingat.create') }}">
            <button class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm text-white transition"
                style="background:#9F86C0;"
                onmouseover="this.style.background='#5E4B8B'"
                onmouseout="this.style.background='#9F86C0'">
                <i class="ti ti-plus" style="font-size:16px;" aria-hidden="true"></i>
                Tambah Pengingat
            </button>
        </a>
    </div>

    {{-- PENGINGAT AKTIF --}}
    <div class="bg-white rounded-2xl overflow-hidden mb-6" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
        <div class="p-4 flex items-center gap-2" style="background:linear-gradient(135deg,#EDE4F5,#CDB4DB); border-bottom:1.5px solid #CDB4DB;">
            <i class="ti ti-bell-ringing" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
            <h2 class="font-bold" style="color:#5E4B8B;">Pengingat Aktif ({{ count($aktif) }})</h2>
        </div>

        <div class="p-4 space-y-3">
            @forelse ($aktif as $item)
            <div class="p-4 rounded-xl flex justify-between items-start transition"
                style="border:1.5px solid #EDE4F5;"
                onmouseover="this.style.background='#FDFAFF'"
                onmouseout="this.style.background=''">
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold" style="color:#2D1B69;">{{ $item->nama_hewan }}</h3>
                    <span class="inline-block text-xs px-2 py-0.5 rounded-full mt-1 font-medium"
                        style="background:#EDE4F5; color:#9F86C0;">
                        {{ $item->kategori }}
                    </span>
                    <div class="flex items-center gap-4 mt-2 text-sm" style="color:#9ca3af;">
                        <span class="flex items-center gap-1">
                            <i class="ti ti-calendar" style="font-size:14px; color:#9F86C0;" aria-hidden="true"></i>
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="ti ti-clock" style="font-size:14px; color:#9F86C0;" aria-hidden="true"></i>
                            {{ $item->waktu }}
                        </span>
                    </div>
                    @if($item->deskripsi)
                        <p class="text-sm mt-1" style="color:#9ca3af;">{{ $item->deskripsi }}</p>
                    @endif
                </div>

                <div class="flex items-center gap-2 ml-4 flex-shrink-0">
                    <form action="{{ route('pengingat.selesai', $item->id) }}" method="POST">
                        @csrf
                        <button class="w-9 h-9 rounded-lg flex items-center justify-center transition"
                            style="background:#EDE4F5;"
                            onmouseover="this.style.background='#9F86C0'; this.querySelector('i').style.color='white'"
                            onmouseout="this.style.background='#EDE4F5'; this.querySelector('i').style.color='#9F86C0'"
                            title="Tandai selesai">
                            <i class="ti ti-check" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                        </button>
                    </form>
                    <form action="{{ route('pengingat.delete', $item->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="w-9 h-9 rounded-lg flex items-center justify-center transition"
                            style="background:#fef2f2;"
                            onmouseover="this.style.background='#ef4444'; this.querySelector('i').style.color='white'"
                            onmouseout="this.style.background='#fef2f2'; this.querySelector('i').style.color='#ef4444'"
                            title="Hapus">
                            <i class="ti ti-trash" style="font-size:16px; color:#ef4444;" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <i class="ti ti-bell-off" style="font-size:36px; color:#CDB4DB;" aria-hidden="true"></i>
                <p class="mt-3 text-sm" style="color:#9ca3af;">Tidak ada pengingat aktif</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- RIWAYAT SELESAI --}}
    <div class="bg-white rounded-2xl overflow-hidden" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
        <div class="p-4 flex items-center gap-2" style="background:linear-gradient(135deg,#EDE4F5,#CDB4DB); border-bottom:1.5px solid #CDB4DB;">
            <i class="ti ti-circle-check" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
            <h2 class="font-bold" style="color:#5E4B8B;">Riwayat Selesai ({{ count($selesai) }})</h2>
        </div>

        <div class="p-4 space-y-3">
            @forelse ($selesai as $item)
            <div class="p-4 rounded-xl flex justify-between items-start"
                style="border:1.5px solid #EDE4F5; opacity:0.75;">
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold" style="color:#2D1B69;">{{ $item->nama_hewan }}</h3>
                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                        <span class="inline-block text-xs px-2 py-0.5 rounded-full font-medium"
                            style="background:#EDE4F5; color:#9F86C0;">
                            {{ $item->kategori }}
                        </span>
                        <span class="inline-block text-xs px-2 py-0.5 rounded-full font-medium"
                            style="background:#d1fae5; color:#059669;">
                            Selesai
                        </span>
                    </div>
                    <div class="flex items-center gap-4 mt-2 text-sm" style="color:#9ca3af;">
                        <span class="flex items-center gap-1">
                            <i class="ti ti-calendar" style="font-size:14px;" aria-hidden="true"></i>
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="ti ti-clock" style="font-size:14px;" aria-hidden="true"></i>
                            {{ $item->waktu }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('pengingat.delete', $item->id) }}" method="POST" class="ml-4 flex-shrink-0">
                    @csrf @method('DELETE')
                    <button class="w-9 h-9 rounded-lg flex items-center justify-center transition"
                        style="background:#fef2f2;"
                        onmouseover="this.style.background='#ef4444'; this.querySelector('i').style.color='white'"
                        onmouseout="this.style.background='#fef2f2'; this.querySelector('i').style.color='#ef4444'">
                        <i class="ti ti-trash" style="font-size:16px; color:#ef4444;" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
            @empty
            <div class="text-center py-8">
                <i class="ti ti-checks" style="font-size:36px; color:#CDB4DB;" aria-hidden="true"></i>
                <p class="mt-3 text-sm" style="color:#9ca3af;">Belum ada riwayat selesai</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection