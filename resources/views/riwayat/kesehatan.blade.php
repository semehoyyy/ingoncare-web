@extends('layouts.app')

@section('title', 'Riwayat Kesehatan')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold" style="color:#2D1B69;">Riwayat Kesehatan</h1>
            <p class="text-sm mt-1" style="color:#9ca3af;">
                Catatan lengkap pemeriksaan kesehatan hewan peliharaan Anda
            </p>
        </div>

        <a href="{{ route('riwayat.create') }}"
           class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm text-white transition"
           style="background:#9F86C0;"
           onmouseover="this.style.background='#5E4B8B'"
           onmouseout="this.style.background='#9F86C0'">
            <i class="ti ti-plus" style="font-size:16px;" aria-hidden="true"></i>
            Tambah Riwayat
        </a>
    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="bg-white rounded-2xl p-6 relative overflow-hidden"
             style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="relative z-10">
                <p class="text-sm mb-2" style="color:#9ca3af;">Total Pemeriksaan</p>
                <p class="text-3xl font-bold" style="color:#2D1B69;">{{ $totalPemeriksaan }}</p>
            </div>
            <div class="absolute right-5 bottom-5 w-12 h-12 rounded-2xl flex items-center justify-center"
                 style="background:#EDE4F5;">
                <i class="ti ti-clipboard-list" style="font-size:22px; color:#9F86C0;" aria-hidden="true"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 relative overflow-hidden"
             style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="relative z-10">
                <p class="text-sm mb-2" style="color:#9ca3af;">Hewan Diperiksa</p>
                <p class="text-3xl font-bold" style="color:#2D1B69;">{{ $hewanDiperiksa }}</p>
            </div>
            <div class="absolute right-5 bottom-5 w-12 h-12 rounded-2xl flex items-center justify-center"
                 style="background:#EDE4F5;">
                <i class="ti ti-paw" style="font-size:22px; color:#9F86C0;" aria-hidden="true"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 relative overflow-hidden"
             style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="relative z-10">
                <p class="text-sm mb-2" style="color:#9ca3af;">Bulan Ini</p>
                <p class="text-3xl font-bold" style="color:#2D1B69;">{{ $bulanIni }}</p>
            </div>
            <div class="absolute right-5 bottom-5 w-12 h-12 rounded-2xl flex items-center justify-center"
                 style="background:#EDE4F5;">
                <i class="ti ti-calendar-stats" style="font-size:22px; color:#9F86C0;" aria-hidden="true"></i>
            </div>
        </div>

    </div>

    {{-- LIST RIWAYAT --}}
    @if ($riwayats->isEmpty())
        <div class="bg-white rounded-2xl p-16 text-center"
             style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center"
                 style="background:#EDE4F5;">
                <i class="ti ti-clipboard-off" style="font-size:30px; color:#CDB4DB;" aria-hidden="true"></i>
            </div>
            <p class="font-semibold mb-1" style="color:#5E4B8B;">Belum ada riwayat kesehatan</p>
            <p class="text-sm" style="color:#9ca3af;">Tambahkan catatan pemeriksaan pertama hewan Anda</p>
            <a href="{{ route('riwayat.create') }}"
               class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition"
               style="background:#9F86C0;"
               onmouseover="this.style.background='#5E4B8B'"
               onmouseout="this.style.background='#9F86C0'">
                <i class="ti ti-plus" style="font-size:15px;" aria-hidden="true"></i>
                Tambah Riwayat
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @foreach ($riwayats as $riwayat)
            @php
                $pet = $riwayat->pet;
                $speciesIcon = 'ti-paw';
                if ($pet) {
                    $s = strtolower($pet->species ?? '');
                    if (str_contains($s, 'kucing'))       $speciesIcon = 'ti-cat';
                    elseif (str_contains($s, 'anjing'))   $speciesIcon = 'ti-dog';
                    elseif (str_contains($s, 'kelinci'))  $speciesIcon = 'ti-bunny';
                    elseif (str_contains($s, 'burung'))   $speciesIcon = 'ti-feather';
                    elseif (str_contains($s, 'ikan'))     $speciesIcon = 'ti-fish';
                    elseif (str_contains($s, 'hamster'))  $speciesIcon = 'ti-hamster';
                    elseif (str_contains($s, 'kura'))     $speciesIcon = 'ti-turtle';
                }
            @endphp

            <div class="bg-white rounded-2xl overflow-hidden transition-all"
                 style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);"
                 onmouseover="this.style.boxShadow='0 4px 20px rgba(159,134,192,0.18)'"
                 onmouseout="this.style.boxShadow='0 2px 12px rgba(159,134,192,0.08)'">

                {{-- CARD HEADER --}}
                <div class="px-5 py-4" style="background:linear-gradient(135deg,#EDE4F5,#F5F0FA); border-bottom:1.5px solid #EDE4F5;">
                    <div class="flex justify-between items-start">

                        <div class="flex gap-3 items-center">
                            {{-- Icon Hewan --}}
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
                                 style="background:white; border:1.5px solid #CDB4DB; box-shadow:0 2px 6px rgba(159,134,192,0.12);">
                                <i class="ti {{ $speciesIcon }}" style="font-size:28px; color:#9F86C0;" aria-hidden="true"></i>
                            </div>

                            <div>
                                <h3 class="font-bold text-base leading-tight" style="color:#2D1B69;">
                                    {{ $pet ? $pet->name : 'Hewan Dihapus' }}
                                </h3>
                                <p class="text-sm mt-0.5" style="color:#9ca3af;">
                                    {{ $pet ? $pet->species : '-' }}
                                    @if($pet && $pet->breed) · {{ $pet->breed }} @endif
                                </p>
                                @if($pet)
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    <span class="flex items-center gap-1 text-xs px-2 py-0.5 rounded-full font-medium"
                                          style="background:#EDE4F5; color:#9F86C0;">
                                        @if(strtolower($pet->gender ?? '') == 'jantan')
                                            <i class="ti ti-mars" style="font-size:11px;" aria-hidden="true"></i>
                                        @else
                                            <i class="ti ti-venus" style="font-size:11px;" aria-hidden="true"></i>
                                        @endif
                                        {{ $pet->gender }}
                                    </span>
                                    @if($pet->age)
                                    <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                          style="background:#EDE4F5; color:#9F86C0;">
                                        {{ $pet->age }}
                                    </span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="flex gap-1.5">
                            <a href="{{ route('riwayat.edit', $riwayat->id) }}"
                               class="w-9 h-9 rounded-xl flex items-center justify-center transition"
                               style="background:white; border:1.5px solid #CDB4DB;"
                               onmouseover="this.style.background='#EDE4F5'"
                               onmouseout="this.style.background='white'"
                               title="Edit">
                                <i class="ti ti-edit" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                            </a>

                            <form action="{{ route('riwayat.destroy', $riwayat->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus riwayat ini?')">
                                @csrf @method('DELETE')
                                <button class="w-9 h-9 rounded-xl flex items-center justify-center transition"
                                        style="background:white; border:1.5px solid #fecaca;"
                                        onmouseover="this.style.background='#fef2f2'"
                                        onmouseout="this.style.background='white'"
                                        title="Hapus">
                                    <i class="ti ti-trash" style="font-size:16px; color:#ef4444;" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- TANGGAL --}}
                    <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-sm font-medium"
                         style="background:white; border:1.5px solid #CDB4DB; color:#5E4B8B;">
                        <i class="ti ti-calendar" style="font-size:14px; color:#9F86C0;" aria-hidden="true"></i>
                        {{ \Carbon\Carbon::parse($riwayat->tanggal_pemeriksaan)->format('d F Y') }}
                    </div>
                </div>

                {{-- DETAIL INFORMASI --}}
                <div class="px-5 py-4 space-y-3">

                    {{-- Diagnosis --}}
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:#EDE4F5;">
                            <i class="ti ti-stethoscope" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs mb-0.5" style="color:#9ca3af;">Diagnosis</p>
                            <p class="text-sm font-semibold" style="color:#2D1B69;">{{ $riwayat->diagnosis }}</p>
                        </div>
                    </div>

                    {{-- Tindakan --}}
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:#EDE4F5;">
                            <i class="ti ti-activity" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs mb-0.5" style="color:#9ca3af;">Tindakan</p>
                            <p class="text-sm font-semibold" style="color:#2D1B69;">{{ $riwayat->tindakan }}</p>
                        </div>
                    </div>

                    {{-- Dokter --}}
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:#EDE4F5;">
                            <i class="ti ti-user-heart" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs mb-0.5" style="color:#9ca3af;">Dokter</p>
                            <p class="text-sm font-semibold" style="color:#2D1B69;">{{ $riwayat->dokter }}</p>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:#EDE4F5;">
                            <i class="ti ti-notes" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs mb-0.5" style="color:#9ca3af;">Catatan</p>
                            <p class="text-sm font-semibold" style="color:#2D1B69;">
                                {{ $riwayat->catatan ?? 'Tidak ada catatan' }}
                            </p>
                        </div>
                    </div>

                </div>

                {{-- JADWAL BERIKUTNYA --}}
                @if ($riwayat->jadwal_berikutnya)
                <div class="px-5 py-3" style="border-top:1.5px solid #EDE4F5; background:#FDFAFF;">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:#9F86C0;">
                            <i class="ti ti-calendar-plus text-white" style="font-size:16px;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <p class="text-xs" style="color:#9ca3af;">Jadwal Pemeriksaan Berikutnya</p>
                            <p class="text-sm font-bold" style="color:#5E4B8B;">
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