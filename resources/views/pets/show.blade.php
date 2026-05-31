@extends('layouts.app')

@section('title', 'Detail Hewan')

@section('content')
<div>

    {{-- HEADER --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('hewan-saya') }}"
            class="w-10 h-10 rounded-full flex items-center justify-center transition"
            style="background:#EDE4F5;"
            onmouseover="this.style.background='#CDB4DB'"
            onmouseout="this.style.background='#EDE4F5'">
            <i class="ti ti-arrow-left" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold" style="color:#2D1B69;">{{ $pet->name }}</h1>
            <p class="text-sm" style="color:#9ca3af;">{{ $pet->species }}{{ $pet->breed ? ' · ' . $pet->breed : '' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- FOTO & INFO SINGKAT --}}
        <div class="bg-white rounded-2xl p-6 text-center" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">

            <div class="w-32 h-32 rounded-full mx-auto overflow-hidden mb-4"
                 style="border:3px solid #CDB4DB; background:#EDE4F5;">
                @if($pet->photo)
                    <img src="{{ asset('storage/' . $pet->photo) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="ti ti-paw" style="font-size:40px; color:#CDB4DB;" aria-hidden="true"></i>
                    </div>
                @endif
            </div>

            <h2 class="text-2xl font-bold mb-1" style="color:#2D1B69;">{{ $pet->name }}</h2>
            <p class="text-sm mb-3" style="color:#9ca3af;">{{ $pet->species }}{{ $pet->breed ? ' · ' . $pet->breed : '' }}</p>

            <div class="flex justify-center gap-2 flex-wrap mb-6">
                @if($pet->gender == 'Jantan')
                    <span class="flex items-center gap-1 px-3 py-1 text-xs rounded-full font-medium"
                          style="background:#EDE4F5; color:#5E4B8B;">
                        <i class="ti ti-mars" style="font-size:12px;" aria-hidden="true"></i> Jantan
                    </span>
                @elseif($pet->gender == 'Betina')
                    <span class="flex items-center gap-1 px-3 py-1 text-xs rounded-full font-medium"
                          style="background:#EDE4F5; color:#5E4B8B;">
                        <i class="ti ti-venus" style="font-size:12px;" aria-hidden="true"></i> Betina
                    </span>
                @endif
                @if($pet->age)
                    <span class="px-3 py-1 text-xs rounded-full font-medium"
                          style="background:#EDE4F5; color:#5E4B8B;">{{ $pet->age }}</span>
                @endif
            </div>

            <div class="flex gap-2">
                <a href="{{ route('pets.edit', $pet->id) }}"
                    class="flex-1 flex items-center justify-center gap-1 py-2.5 rounded-xl text-sm font-semibold text-white transition"
                    style="background:#9F86C0;"
                    onmouseover="this.style.background='#5E4B8B'"
                    onmouseout="this.style.background='#9F86C0'">
                    <i class="ti ti-edit" style="font-size:14px;" aria-hidden="true"></i>
                    Edit
                </a>
                <form action="{{ route('pets.destroy', $pet->id) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus?')" class="flex-1">
                    @csrf @method('DELETE')
                    <button class="w-full flex items-center justify-center gap-1 py-2.5 rounded-xl text-sm font-semibold transition"
                            style="background:#fef2f2; color:#ef4444; border:1.5px solid #fecaca;"
                            onmouseover="this.style.background='#ef4444'; this.style.color='white'"
                            onmouseout="this.style.background='#fef2f2'; this.style.color='#ef4444'">
                        <i class="ti ti-trash" style="font-size:14px;" aria-hidden="true"></i>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        {{-- DETAIL INFO --}}
        <div class="md:col-span-2 space-y-5">

            {{-- Informasi Dasar --}}
            <div class="bg-white rounded-2xl p-6" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
                <h3 class="font-bold text-base mb-4 flex items-center gap-2" style="color:#2D1B69;">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#EDE4F5;">
                        <i class="ti ti-clipboard-list" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                    </div>
                    Informasi Dasar
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 rounded-xl" style="background:#FDFAFF; border:1.5px solid #EDE4F5;">
                        <p class="text-xs mb-1" style="color:#9ca3af;">Tanggal Lahir</p>
                        <p class="font-semibold text-sm" style="color:#2D1B69;">
                            {{ $pet->birth_date ? \Carbon\Carbon::parse($pet->birth_date)->format('d F Y') : '-' }}
                        </p>
                    </div>
                    <div class="p-3 rounded-xl" style="background:#FDFAFF; border:1.5px solid #EDE4F5;">
                        <p class="text-xs mb-1" style="color:#9ca3af;">Umur</p>
                        <p class="font-semibold text-sm" style="color:#2D1B69;">{{ $pet->age ?? '-' }}</p>
                    </div>
                    <div class="p-3 rounded-xl" style="background:#FDFAFF; border:1.5px solid #EDE4F5;">
                        <p class="text-xs mb-1" style="color:#9ca3af;">Berat</p>
                        <p class="font-semibold text-sm" style="color:#2D1B69;">{{ $pet->weight ? $pet->weight . ' kg' : '-' }}</p>
                    </div>
                    <div class="p-3 rounded-xl" style="background:#FDFAFF; border:1.5px solid #EDE4F5;">
                        <p class="text-xs mb-1" style="color:#9ca3af;">Status Steril</p>
                        <p class="font-semibold text-sm" style="color:#2D1B69;">{{ $pet->is_steril ? 'Sudah' : 'Belum' }}</p>
                    </div>
                </div>
            </div>

            {{-- Kesehatan --}}
            <div class="bg-white rounded-2xl p-6" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
                <h3 class="font-bold text-base mb-4 flex items-center gap-2" style="color:#2D1B69;">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#EDE4F5;">
                        <i class="ti ti-heart-rate-monitor" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                    </div>
                    Informasi Kesehatan
                </h3>
                <div class="space-y-3">
                    <div class="p-3 rounded-xl" style="background:#FDFAFF; border:1.5px solid #EDE4F5;">
                        <p class="text-xs mb-1" style="color:#9ca3af;">Ciri Khusus</p>
                        <p class="font-medium text-sm" style="color:#2D1B69;">{{ $pet->special_marks ?: 'Tidak ada' }}</p>
                    </div>
                    <div class="p-3 rounded-xl" style="background:#FDFAFF; border:1.5px solid #EDE4F5;">
                        <p class="text-xs mb-1" style="color:#9ca3af;">Alergi</p>
                        <p class="font-medium text-sm" style="color:#2D1B69;">{{ $pet->allergies ?: 'Tidak ada' }}</p>
                    </div>
                    <div class="p-3 rounded-xl" style="background:#FDFAFF; border:1.5px solid #EDE4F5;">
                        <p class="text-xs mb-1" style="color:#9ca3af;">Kondisi Kesehatan Khusus</p>
                        <p class="font-medium text-sm" style="color:#2D1B69;">{{ $pet->health_notes ?: 'Tidak ada' }}</p>
                    </div>
                </div>
            </div>

            {{-- Link ke Riwayat --}}
            <a href="{{ route('riwayat') }}"
                class="flex items-center justify-between p-5 rounded-2xl text-white transition"
                style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);"
                onmouseover="this.style.opacity='0.9'"
                onmouseout="this.style.opacity='1'">
                <div>
                    <h3 class="font-bold text-base flex items-center gap-2">
                        <i class="ti ti-clipboard-list" style="font-size:18px;" aria-hidden="true"></i>
                        Riwayat Kesehatan
                    </h3>
                    <p class="text-sm mt-0.5" style="color:#EDE4F5;">Lihat semua catatan pemeriksaan</p>
                </div>
                <i class="ti ti-arrow-right" style="font-size:20px;" aria-hidden="true"></i>
            </a>

        </div>
    </div>

</div>
@endsection