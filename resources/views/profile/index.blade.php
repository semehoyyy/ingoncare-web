@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen max-w-7xl mx-auto px-4 py-6">

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center justify-between">
        <span class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </span>

        <button onclick="this.parentElement.remove()"
                class="text-green-600 hover:text-green-800">
            ✕
        </button>
    </div>
    @endif

    {{-- COVER --}}
    <div class="relative rounded-3xl overflow-hidden" style="
        background: linear-gradient(135deg, #13CAD6 0%, #0ea5e9 45%, #6366f1 100%);
        min-height: 240px;
    ">

        {{-- Glow --}}
        <div class="absolute inset-0 overflow-hidden">
            <div style="position:absolute;width:320px;height:320px;background:radial-gradient(circle,rgba(255,255,255,0.18) 0%,transparent 65%);top:-90px;left:-50px;border-radius:50%;"></div>

            <div style="position:absolute;width:260px;height:260px;background:radial-gradient(circle,rgba(255,255,255,0.12) 0%,transparent 65%);bottom:-80px;right:60px;border-radius:50%;"></div>

            <div style="position:absolute;width:180px;height:180px;background:radial-gradient(circle,rgba(99,102,241,0.4) 0%,transparent 70%);top:25px;right:220px;border-radius:50%;filter:blur(18px);"></div>
        </div>

        {{-- Dot Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <svg width="100%" height="100%">
                <defs>
                    <pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.5" fill="white"/>
                    </pattern>
                </defs>

                <rect width="100%" height="100%" fill="url(#dots)"/>
            </svg>
        </div>

        {{-- Shimmer --}}
        <div class="absolute inset-0"
             style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.06) 50%,transparent 70%);">
        </div>

        {{-- Edit Button --}}
        @if($isOwnProfile)
        <div class="relative p-6 flex justify-end">
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-white transition"
               style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.25);backdrop-filter:blur(8px);">

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>

                Edit Profil
            </a>
        </div>
        @endif
    </div>

    {{-- PROFILE HEADER --}}
    <div class="relative px-6 md:px-8 pb-6">

        {{-- Avatar --}}
        <div class="-mt-16 mb-4 relative z-10">
            <div class="w-32 h-32 rounded-full border-4 border-white shadow-2xl overflow-hidden bg-white">

                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 text-5xl">
                        👤
                    </div>
                @endif

            </div>
        </div>

        {{-- User Info --}}
        <div class="mb-6">

            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                {{ $user->name }}
            </h1>

            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">

                @if($user->address)
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>

                    {{ $user->address }}
                </span>

                <span class="text-gray-300">•</span>
                @endif

                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>

                    Bergabung {{ $user->created_at->format('F Y') }}
                </span>

                <span class="text-gray-300">•</span>

                <span class="flex items-center gap-1.5 text-xs px-3 py-1 rounded-full bg-green-100 text-green-700 font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                    Online
                </span>

            </div>
        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-3 gap-4">

            <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center shadow-sm">
                <div class="text-3xl font-bold text-gray-900">
                    {{ $stats['total_posts'] }}
                </div>

                <div class="text-sm text-gray-500 mt-1">
                    Postingan
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center shadow-sm">
                <div class="text-3xl font-bold text-gray-900">
                    {{ $stats['total_replies'] }}
                </div>

                <div class="text-sm text-gray-500 mt-1">
                    Balasan
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center shadow-sm">
                <div class="text-3xl font-bold text-gray-900">
                    {{ $stats['total_likes'] }}
                </div>

                <div class="text-sm text-gray-500 mt-1">
                    Likes
                </div>
            </div>

        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT SIDE --}}
        <div class="space-y-5">

            @php $pets = $user->pets ?? collect(); @endphp

            {{-- PETS --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm"
                         style="background:#e0f7fa;">
                        🐾
                    </div>

                    <h3 class="font-bold text-gray-900">
                        Hewan Peliharaan
                    </h3>
                </div>

                @if($pets->count() > 0)

                <div class="p-4 space-y-3">

                    @foreach($pets as $pet)

                    <a href="{{ route('pets.show', $pet->id) }}"
                       class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-cyan-50 transition">

                        <div class="w-11 h-11 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center flex-shrink-0">

                            @if($pet->photo)
                                <img src="{{ asset('storage/' . $pet->photo) }}"
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-xl">🐾</span>
                            @endif

                        </div>

                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $pet->name }}
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ $pet->species }}
                                {{ $pet->breed ? ' · ' . $pet->breed : '' }}
                            </p>
                        </div>

                    </a>

                    @endforeach

                    @if($isOwnProfile)
                    <a href="{{ route('pets.create') }}"
                       class="flex items-center justify-center gap-2 w-full py-3 border-2 border-dashed border-cyan-300 rounded-xl text-cyan-600 text-sm font-semibold hover:bg-cyan-50 transition">
                        + Tambah Hewan
                    </a>
                    @endif

                </div>

                @else

                <div class="p-6 text-center">
                    <p class="text-gray-400 text-sm mb-4">
                        Belum ada hewan peliharaan
                    </p>

                    @if($isOwnProfile)
                    <a href="{{ route('pets.create') }}"
                       class="inline-block px-5 py-2 bg-[#13CAD6] text-white rounded-xl text-sm font-semibold hover:bg-[#0fb3c2] transition">
                        + Tambah Hewan
                    </a>
                    @endif
                </div>

                @endif

            </div>

        </div>

        {{-- RIGHT SIDE --}}
        <div class="lg:col-span-2">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                {{-- TAB NAVIGATION --}}
                <div class="flex border-b border-gray-100">

                    @foreach(['postingan' => 'Postingan', 'balasan' => 'Balasan', 'suka' => 'Disukai'] as $key => $label)

                    <a href="{{ request()->url() }}?tab={{ $key }}"
                       class="flex-1 text-center py-4 text-sm font-semibold transition
                       {{ $tab === $key
                           ? 'text-[#13CAD6] border-b-2 border-[#13CAD6]'
                           : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">

                        {{ $label }}

                    </a>

                    @endforeach

                </div>

                {{-- TAB CONTENT --}}
                <div class="divide-y divide-gray-50">

                    @forelse($content as $item)

                    <div class="p-5 hover:bg-gray-50 transition">

                        @if($item->title)
                        <h4 class="font-bold text-gray-900 mb-2">
                            {{ $item->title }}
                        </h4>
                        @endif

                        <p class="text-sm text-gray-700 leading-relaxed mb-3">
                            {{ Str::limit($item->content, 180) }}
                        </p>

                        @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}"
                             class="w-full max-h-60 object-cover rounded-xl border mb-3">
                        @endif

                        <div class="flex items-center gap-5 text-xs text-gray-400">

                            <span>
                                ❤️ {{ $item->likes->count() }}
                            </span>

                            <span>
                                💬 {{ $item->replies->count() }}
                            </span>

                            <span class="ml-auto">
                                {{ $item->created_at->diffForHumans() }}
                            </span>

                        </div>

                    </div>

                    @empty

                    <div class="py-20 text-center">

                        <div class="text-5xl mb-4">
                            💬
                        </div>

                        <p class="text-gray-500 font-medium">
                            Belum ada konten
                        </p>

                    </div>

                    @endforelse

                </div>

                {{-- PAGINATION --}}
                @if($content instanceof \Illuminate\Pagination\LengthAwarePaginator && $content->hasPages())

                <div class="p-4 border-t border-gray-100">
                    {{ $content->appends(['tab' => $tab])->links() }}
                </div>

                @endif

            </div>

        </div>

    </div>

</div>
@endsection