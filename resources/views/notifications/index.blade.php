@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div>

    {{-- HEADER --}}
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-2xl font-bold" style="color:#2D1B69;">Notifikasi</h1>
            <p class="text-sm mt-1" style="color:#9ca3af;">
                {{ $notifications->where('is_read', false)->count() }} notifikasi belum dibaca
            </p>
        </div>

        <form method="POST" action="/notifications">
            @csrf
            @method('DELETE')
            <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition"
                    style="background:#fef2f2; color:#ef4444; border:1.5px solid #fecaca;"
                    onmouseover="this.style.background='#ef4444'; this.style.color='white'"
                    onmouseout="this.style.background='#fef2f2'; this.style.color='#ef4444'">
                <i class="ti ti-trash" style="font-size:15px;" aria-hidden="true"></i>
                Hapus Semua
            </button>
        </form>
    </div>

    {{-- TABS --}}
    <div class="bg-white rounded-2xl overflow-hidden mb-6" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
        <div class="p-4" style="border-bottom:1.5px solid #EDE4F5;">

            {{-- Tab Buttons --}}
            <div class="flex gap-2 p-1 rounded-xl" style="background:#F5F0FA;">
                @php
                    $currentTab = request('tab', 'all');
                    $tabs = [
                        'all'    => ['label' => 'Semua', 'count' => $notifications->count()],
                        'unread' => ['label' => 'Belum Dibaca', 'count' => $notifications->where('is_read', false)->count()],
                        'read'   => ['label' => 'Sudah Dibaca', 'count' => $notifications->where('is_read', true)->count()],
                    ];
                @endphp

                @foreach($tabs as $key => $tab)
                <a href="?tab={{ $key }}"
                   class="flex-1 text-center py-2.5 rounded-lg text-sm font-semibold transition"
                   style="{{ $currentTab === $key ? 'background:#9F86C0; color:white;' : 'color:#5E4B8B;' }}"
                   onmouseover="if('{{ $currentTab }}' !== '{{ $key }}') this.style.background='#EDE4F5'"
                   onmouseout="if('{{ $currentTab }}' !== '{{ $key }}') this.style.background=''">
                    {{ $tab['label'] }}
                    <span class="ml-1 text-xs px-1.5 py-0.5 rounded-full inline-block"
                          style="{{ $currentTab === $key ? 'background:rgba(255,255,255,0.3); color:white;' : 'background:#EDE4F5; color:#9F86C0;' }}">
                        {{ $tab['count'] }}
                    </span>
                </a>
                @endforeach
            </div>

            {{-- Tandai Semua --}}
            @if($notifications->where('is_read', false)->count() > 0)
            <form method="POST" action="/notifications/read-all" class="mt-3">
                @csrf
                <button class="flex items-center gap-1.5 text-sm font-semibold transition"
                        style="color:#9F86C0;"
                        onmouseover="this.style.color='#5E4B8B'"
                        onmouseout="this.style.color='#9F86C0'">
                    <i class="ti ti-checks" style="font-size:15px;" aria-hidden="true"></i>
                    Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>

        {{-- LIST NOTIFIKASI --}}
        <div class="divide-y" style="border-color:#EDE4F5;">

            @php
                $list = $notifications;
                if($currentTab === 'unread') $list = $notifications->where('is_read', false);
                elseif($currentTab === 'read')   $list = $notifications->where('is_read', true);
            @endphp

            @forelse($list as $n)
            @php
                $typeConfig = [
                    'pengingat' => ['icon' => 'ti-calendar-event', 'bg' => '#EDE4F5', 'color' => '#9F86C0', 'border' => '#CDB4DB', 'emoji' => '📅'],
                    'forum'     => ['icon' => 'ti-message-dots',   'bg' => '#EDE4F5', 'color' => '#9F86C0', 'border' => '#CDB4DB', 'emoji' => '💬'],
                    'like'      => ['icon' => 'ti-heart',          'bg' => '#EDE4F5', 'color' => '#9F86C0', 'border' => '#CDB4DB', 'emoji' => '❤️'],
                ];
                $cfg = $typeConfig[$n->type] ?? ['icon' => 'ti-bell', 'bg' => '#EDE4F5', 'color' => '#9F86C0', 'border' => '#CDB4DB', 'emoji' => '🔔'];
            @endphp

            <div class="p-5 transition {{ !$n->is_read ? '' : '' }}"
                 style="{{ !$n->is_read ? 'background:#FDFAFF;' : '' }}"
                 onmouseover="this.style.background='#F5F0FA'"
                 onmouseout="this.style.background='{{ !$n->is_read ? '#FDFAFF' : '' }}'">

                <div class="flex items-start gap-4">

                    {{-- ICON --}}
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 text-xl"
                         style="background:{{ $cfg['bg'] }}; border:1.5px solid {{ $cfg['border'] }};">
                        {{ $cfg['emoji'] }}
                    </div>

                    {{-- CONTENT --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-semibold text-sm" style="color:#2D1B69;">{{ $n->title }}</h3>
                                    @if(!$n->is_read)
                                        <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:#9F86C0;"></span>
                                    @endif
                                </div>
                                <p class="text-sm leading-relaxed" style="color:#4b5563;">{{ $n->message }}</p>
                            </div>
                            <span class="text-xs flex-shrink-0" style="color:#9ca3af;">
                                {{ $n->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex gap-2 mt-3">
                            @if(!$n->is_read)
                            <form method="POST" action="/notifications/read/{{ $n->id }}">
                                @csrf
                                <button class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition"
                                        style="background:#EDE4F5; color:#9F86C0;"
                                        onmouseover="this.style.background='#9F86C0'; this.style.color='white'"
                                        onmouseout="this.style.background='#EDE4F5'; this.style.color='#9F86C0'">
                                    <i class="ti ti-check" style="font-size:12px;" aria-hidden="true"></i>
                                    Tandai Dibaca
                                </button>
                            </form>
                            @endif

                            <form method="POST" action="/notifications/{{ $n->id }}">
                                @csrf
                                @method('DELETE')
                                <button class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition"
                                        style="background:#fef2f2; color:#ef4444;"
                                        onmouseover="this.style.background='#ef4444'; this.style.color='white'"
                                        onmouseout="this.style.background='#fef2f2'; this.style.color='#ef4444'">
                                    <i class="ti ti-trash" style="font-size:12px;" aria-hidden="true"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @empty
            <div class="py-16 text-center">
                <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center" style="background:#EDE4F5;">
                    <i class="ti ti-bell-off" style="font-size:28px; color:#CDB4DB;" aria-hidden="true"></i>
                </div>
                <p class="font-semibold" style="color:#5E4B8B;">Tidak ada notifikasi</p>
                <p class="text-sm mt-1" style="color:#9ca3af;">
                    @if($currentTab === 'unread') Semua notifikasi sudah dibaca
                    @elseif($currentTab === 'read') Belum ada notifikasi yang dibaca
                    @else Belum ada notifikasi apapun
                    @endif
                </p>
            </div>
            @endforelse

        </div>
    </div>

</div>
@endsection