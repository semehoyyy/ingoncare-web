@extends('layouts.app')

@section('title', 'Hewan Saya')

@section('content')
<div>

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold" style="color:#2D1B69;">Hewan Peliharaan Saya</h1>
            <p class="text-sm mt-1" style="color:#9ca3af;">Kelola informasi dan kesehatan hewan peliharaan Anda</p>
        </div>
        <a href="{{ route('pets.create') }}">
            <button class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm text-white transition"
                style="background:#9F86C0;"
                onmouseover="this.style.background='#5E4B8B'"
                onmouseout="this.style.background='#9F86C0'">
                <i class="ti ti-plus" style="font-size:16px;" aria-hidden="true"></i>
                Tambah Hewan
            </button>
        </a>
    </div>

    {{-- Total Hewan --}}
    <div class="mb-8">
        <div class="bg-white rounded-2xl px-6 py-5 inline-flex items-center gap-4" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div>
                <p class="text-sm" style="color:#9ca3af;">Total Hewan</p>
                <h2 class="text-3xl font-bold mt-1" style="color:#2D1B69;">{{ $totalHewan }}</h2>
            </div>
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background:#EDE4F5;">
                <i class="ti ti-paw" style="font-size:26px; color:#9F86C0;" aria-hidden="true"></i>
            </div>
        </div>
    </div>

    {{-- Daftar Hewan --}}
    <h2 class="text-base font-semibold mb-4" style="color:#5E4B8B;">Daftar Hewan</h2>

    @if ($pets->count() == 0)
        <div class="text-center py-16 bg-white rounded-2xl" style="border:1.5px solid #EDE4F5;">
            <i class="ti ti-paw-off" style="font-size:48px; color:#CDB4DB;" aria-hidden="true"></i>
            <p class="mt-4 font-semibold" style="color:#5E4B8B;">Belum ada hewan yang ditambahkan</p>
            <p class="text-sm mt-1" style="color:#9ca3af;">Tambahkan hewan peliharaan pertama kamu!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($pets as $pet)
            <div class="rounded-2xl bg-white overflow-hidden transition-all"
                style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);"
                onmouseover="this.style.boxShadow='0 4px 20px rgba(159,134,192,0.16)'"
                onmouseout="this.style.boxShadow='0 2px 12px rgba(159,134,192,0.08)'">

                {{-- Header Card --}}
                <div class="flex items-center gap-4 p-5" style="border-bottom:1.5px solid #EDE4F5;">
                    <div class="w-16 h-16 rounded-full overflow-hidden flex-shrink-0" style="border:2px solid #CDB4DB;">
                        @if ($pet->photo)
                            <img src="{{ asset('storage/' . $pet->photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="background:#EDE4F5;">
                                <i class="ti ti-paw" style="font-size:28px; color:#9F86C0;" aria-hidden="true"></i>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-bold truncate" style="color:#2D1B69;">{{ $pet->name }}</h3>
                        <p class="text-sm" style="color:#9ca3af;">
                            {{ $pet->species }}{{ $pet->breed ? ' · ' . $pet->breed : '' }}
                        </p>
                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                            @if ($pet->gender == 'Jantan')
                                <span class="px-2 py-0.5 text-xs rounded-full font-medium flex items-center gap-1"
                                    style="background:#EDE4F5; color:#5E4B8B;">
                                    <i class="ti ti-mars" style="font-size:12px;" aria-hidden="true"></i>
                                    Jantan
                                </span>
                            @elseif ($pet->gender == 'Betina')
                                <span class="px-2 py-0.5 text-xs rounded-full font-medium flex items-center gap-1"
                                    style="background:#EDE4F5; color:#5E4B8B;">
                                    <i class="ti ti-venus" style="font-size:12px;" aria-hidden="true"></i>
                                    Betina
                                </span>
                            @endif
                            @if ($pet->age)
                                <span class="px-2 py-0.5 text-xs rounded-full font-medium"
                                    style="background:#EDE4F5; color:#5E4B8B;">
                                    {{ $pet->age }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Info Kesehatan --}}
                <div class="p-5 space-y-3">
                    <div class="p-3 rounded-xl" style="background:#FDFAFF; border:1.5px solid #EDE4F5;">
                        <p class="text-xs font-semibold mb-1" style="color:#9F86C0;">
                            <i class="ti ti-sparkles mr-1" style="font-size:12px;" aria-hidden="true"></i>
                            Ciri Khusus
                        </p>
                        <p class="text-sm" style="color:#4b5563;">{{ $pet->special_marks ?: 'Belum ada ciri khusus.' }}</p>
                    </div>

                    <div class="p-3 rounded-xl" style="background:#FDFAFF; border:1.5px solid #EDE4F5;">
                        <p class="text-xs font-semibold mb-2" style="color:#9F86C0;">
                            <i class="ti ti-heart-rate-monitor mr-1" style="font-size:12px;" aria-hidden="true"></i>
                            Informasi Kesehatan
                        </p>
                        <div class="space-y-1 text-sm" style="color:#4b5563;">
                            <p>Status Steril: <span class="font-medium" style="color:#5E4B8B;">{{ (int)$pet->is_steril === 1 ? 'Sudah' : 'Belum' }}</span></p>
                            @if ($pet->allergies)
                                <p>Alergi: <span class="font-medium" style="color:#5E4B8B;">{{ $pet->allergies }}</span></p>
                            @endif
                            @if ($pet->health_notes)
                                <p>Kondisi Khusus: <span class="font-medium" style="color:#5E4B8B;">{{ $pet->health_notes }}</span></p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="flex items-center justify-between px-5 pb-5">
                    <a href="{{ route('pets.show', $pet->id) }}"
                        class="flex items-center gap-1 text-sm font-semibold transition hover:underline"
                        style="color:#9F86C0;">
                        <i class="ti ti-clipboard-list" style="font-size:15px;" aria-hidden="true"></i>
                        Riwayat
                    </a>
                    <a href="{{ route('pets.edit', $pet->id) }}"
                        class="flex items-center gap-1 text-sm font-semibold transition hover:underline"
                        style="color:#5E4B8B;">
                        <i class="ti ti-edit" style="font-size:15px;" aria-hidden="true"></i>
                        Edit
                    </a>
                    <form action="{{ route('pets.destroy', $pet->id) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf @method('DELETE')
                        <button class="flex items-center gap-1 text-sm font-semibold text-red-400 hover:text-red-600 transition">
                            <i class="ti ti-trash" style="font-size:15px;" aria-hidden="true"></i>
                            Hapus
                        </button>
                    </form>
                </div>

            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection