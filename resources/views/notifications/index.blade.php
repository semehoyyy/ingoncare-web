@extends('layouts.app')

@section('content')
<div class="flex gap-6">

    {{-- SIDEBAR SUDAH ADA DI layout --}}
    
    <div class="flex-1">

        {{-- HEADER --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold">Notifikasi</h1>
                    <p class="text-sm text-gray-500">
                        {{ $notifications->where('is_read', false)->count() }} notifikasi belum dibaca
                    </p>
                </div>

                <form method="POST" action="/notifications">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-100 text-red-600 px-4 py-2 rounded-lg text-sm">
                        Hapus Semua
                    </button>
                </form>
            </div>

            {{-- TABS --}}
            <div class="flex gap-6 mt-6 border-b">
                <a href="?tab=all"
                   class="pb-2 {{ request('tab','all')=='all' ? 'text-teal-500 border-b-2 border-teal-500' : 'text-gray-500' }}">
                    Semua ({{ $notifications->count() }})
                </a>
                <a href="?tab=unread"
                   class="pb-2 {{ request('tab')=='unread' ? 'text-teal-500 border-b-2 border-teal-500' : 'text-gray-500' }}">
                    Belum dibaca ({{ $notifications->where('is_read', false)->count() }})
                </a>
                <a href="?tab=read"
                   class="pb-2 {{ request('tab')=='read' ? 'text-teal-500 border-b-2 border-teal-500' : 'text-gray-500' }}">
                    Sudah Dibaca ({{ $notifications->where('is_read', true)->count() }})
                </a>
            </div>

            <form method="POST" action="/notifications/read-all" class="mt-3">
                @csrf
                <button class="text-sm text-teal-500">
                    ✓ Tandai Semua Dibaca
                </button>
            </form>
        </div>

        {{-- LIST NOTIFIKASI --}}
        <div class="space-y-4">

            @php
                $list = $notifications;

                if(request('tab') == 'unread') {
                    $list = $notifications->where('is_read', false);
                } elseif(request('tab') == 'read') {
                    $list = $notifications->where('is_read', true);
                }
            @endphp

            @forelse($list as $n)
            <div class="bg-white rounded-xl shadow p-5 flex gap-4 border-l-4
                {{ $n->type == 'pengingat' ? 'border-blue-400' : '' }}
                {{ $n->type == 'forum' ? 'border-green-400' : '' }}
                {{ $n->type == 'like' ? 'border-purple-400' : '' }}
            ">

                {{-- ICON --}}
                <div class="w-12 h-12 flex items-center justify-center rounded-full
                    {{ $n->type == 'pengingat' ? 'bg-blue-100 text-blue-500' : '' }}
                    {{ $n->type == 'forum' ? 'bg-green-100 text-green-500' : '' }}
                    {{ $n->type == 'like' ? 'bg-purple-100 text-purple-500' : '' }}
                ">
                    @if($n->type == 'pengingat') 📅
                    @elseif($n->type == 'forum') 💬
                    @elseif($n->type == 'like') ❤️
                    @else 🔔
                    @endif
                </div>

                {{-- CONTENT --}}
                <div class="flex-1">
                    <div class="flex justify-between">
                        <h3 class="font-semibold">{{ $n->title }}</h3>
                        <span class="text-sm text-gray-400">
                            {{ $n->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600 mt-1">
                        {{ $n->message }}
                    </p>

                    <div class="flex gap-3 mt-3">
                        @if(!$n->is_read)
                        <form method="POST" action="/notifications/read/{{ $n->id }}">
                            @csrf
                            <button class="bg-green-100 text-green-600 px-3 py-1 rounded text-sm">
                                ✓ Tandai Dibaca
                            </button>
                        </form>
                        @endif

                        <form method="POST" action="/notifications/{{ $n->id }}">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-100 text-red-600 px-3 py-1 rounded text-sm">
                                🗑 Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @empty
                <p class="text-center text-gray-500">Tidak ada notifikasi</p>
            @endforelse

        </div>
    </div>
</div>
@endsection
