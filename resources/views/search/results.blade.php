@extends('layouts.app')

@section('title', 'Hasil Pencarian')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="text-white px-6 py-5 rounded-2xl"
         style="background:linear-gradient(135deg,#2D1B69 0%,#5E4B8B 50%,#9F86C0 100%);">

        {{-- Dot Pattern --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="border-radius:inherit;">
            <svg width="100%" height="100%">
                <defs>
                    <pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.5" fill="white"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dots)"/>
            </svg>
        </div>

        <div class="relative flex items-center gap-3 mb-1">
            <i class="ti ti-search text-white" style="font-size:22px;" aria-hidden="true"></i>
            <h1 class="text-2xl font-bold">Hasil Pencarian</h1>
        </div>
        <p class="relative text-sm" style="color:#EDE4F5;">
            Menampilkan hasil untuk: <span class="font-semibold text-white">"{{ $search }}"</span>
        </p>
    </div>

    {{-- USERS --}}
    @if($users->count() > 0)
    <div class="bg-white rounded-2xl p-5"
         style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">

        <h3 class="font-bold text-base mb-4 flex items-center gap-2" style="color:#2D1B69;">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#EDE4F5;">
                <i class="ti ti-users" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
            </div>
            Pengguna yang Ditemukan
        </h3>

        <div class="space-y-2">
            @foreach($users as $user)
            <a href="{{ route('profile.show', $user->id) }}"
               class="flex items-center gap-3 p-3 rounded-xl transition"
               style="background:#F5F0FA;"
               onmouseover="this.style.background='#EDE4F5'"
               onmouseout="this.style.background='#F5F0FA'">

                <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0"
                     style="border:2px solid #CDB4DB; background:#EDE4F5;">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}"
                             alt="{{ $user->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="ti ti-user" style="font-size:20px; color:#CDB4DB;"></i>
                        </div>
                    @endif
                </div>

                <div>
                    <p class="font-semibold text-sm" style="color:#2D1B69;">{{ $user->name }}</p>
                    <p class="text-xs" style="color:#9ca3af;">{{ $user->email }}</p>
                </div>

                <div class="ml-auto">
                    <i class="ti ti-chevron-right" style="font-size:16px; color:#CDB4DB;"></i>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- DISKUSI --}}
    <div class="bg-white rounded-2xl p-5"
         style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">

        <h3 class="font-bold text-base mb-4 flex items-center gap-2" style="color:#2D1B69;">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#EDE4F5;">
                <i class="ti ti-message-dots" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
            </div>
            Diskusi yang Ditemukan
            <span class="ml-auto text-xs font-normal" style="color:#9ca3af;">
                {{ $comments->total() }} hasil
            </span>
        </h3>

        @forelse($comments as $comment)
        <div class="py-4 transition rounded-xl px-3 mb-1"
             style="border-bottom:1.5px solid #EDE4F5;"
             id="comment-{{ $comment->id }}"
             onmouseover="this.style.background='#FDFAFF'"
             onmouseout="this.style.background=''">

            <div class="flex items-start gap-3">

                {{-- Avatar --}}
                <a href="{{ route('profile.show', $comment->user->id ?? 1) }}" class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full overflow-hidden"
                         style="border:2px solid #CDB4DB; background:#EDE4F5;">
                        @if($comment->user && $comment->user->profile_photo)
                            <img src="{{ asset('storage/' . $comment->user->profile_photo) }}"
                                 alt="{{ $comment->user->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="ti ti-user" style="font-size:16px; color:#CDB4DB;"></i>
                            </div>
                        @endif
                    </div>
                </a>

                <div class="flex-1 min-w-0">

                    {{-- Meta --}}
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <a href="{{ route('profile.show', $comment->user->id ?? 1) }}"
                           class="font-semibold text-sm transition"
                           style="color:#2D1B69;"
                           onmouseover="this.style.color='#9F86C0'"
                           onmouseout="this.style.color='#2D1B69'">
                            {{ $comment->user->name ?? 'Deleted User' }}
                        </a>
                        <span style="color:#CDB4DB;">•</span>
                        <span class="text-xs" style="color:#9ca3af;">
                            {{ $comment->created_at->diffForHumans() }}
                        </span>
                    </div>

                    {{-- Title --}}
                    @if($comment->title)
                    <h4 class="font-bold text-base mb-1" style="color:#2D1B69;">{{ $comment->title }}</h4>
                    @endif

                    {{-- Content --}}
                    <p class="text-sm leading-relaxed mb-3" style="color:#5E4B8B;">
                        {{ Str::limit($comment->content, 200) }}
                    </p>

                    {{-- Image --}}
                    @if($comment->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $comment->image) }}"
                             alt="Post image"
                             class="w-full max-h-64 rounded-xl object-cover"
                             style="border:1.5px solid #EDE4F5;">
                    </div>
                    @endif

                    {{-- Footer --}}
                    <div class="flex items-center gap-5 text-xs" style="color:#9ca3af;">
                        <span class="flex items-center gap-1">
                            <i class="ti ti-heart" style="font-size:14px; color:#9F86C0;" aria-hidden="true"></i>
                            {{ $comment->likes->count() }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="ti ti-message-circle" style="font-size:14px; color:#9F86C0;" aria-hidden="true"></i>
                            {{ $comment->replies->count() }}
                        </span>
                        <a href="{{ route('forum.show', $comment->id) }}"
                           class="ml-auto flex items-center gap-1 text-xs font-semibold transition"
                           style="color:#9F86C0;"
                           onmouseover="this.style.color='#5E4B8B'"
                           onmouseout="this.style.color='#9F86C0'">
                            Lihat Detail
                            <i class="ti ti-arrow-right" style="font-size:13px;" aria-hidden="true"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        @empty
        <div class="py-16 text-center">
            <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center"
                 style="background:#EDE4F5;">
                <i class="ti ti-search-off" style="font-size:28px; color:#CDB4DB;" aria-hidden="true"></i>
            </div>
            <h3 class="font-semibold mb-1" style="color:#5E4B8B;">Tidak ada hasil ditemukan</h3>
            <p class="text-sm mb-5" style="color:#9ca3af;">Coba gunakan kata kunci yang berbeda</p>
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white transition"
               style="background:#9F86C0;"
               onmouseover="this.style.background='#5E4B8B'"
               onmouseout="this.style.background='#9F86C0'">
                <i class="ti ti-home" style="font-size:15px;" aria-hidden="true"></i>
                Kembali ke Home
            </a>
        </div>
        @endforelse

    </div>

    {{-- PAGINATION --}}
    @if($comments->hasPages())
    <div>
        {{ $comments->appends(['q' => $search])->links() }}
    </div>
    @endif

</div>
@endsection