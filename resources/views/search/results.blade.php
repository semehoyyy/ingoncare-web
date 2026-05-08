@extends('layouts.app')

@section('title', 'Hasil Pencarian')

@section('content')
<div class="min-h-screen">

    {{-- Header Search --}}
    <div class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-2xl p-6 mb-6 shadow-lg">
        <div class="flex items-center gap-3 mb-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h1 class="text-2xl font-bold">Hasil Pencarian</h1>
        </div>
        <p class="text-cyan-100">
            Menampilkan hasil untuk: <span class="font-semibold">"{{ $search }}"</span>
        </p>
    </div>

    <div class="space-y-6">

        {{-- Users Results --}}
        @if($users->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="text-xl">👥</span>
                Pengguna yang Ditemukan
            </h3>
            <div class="space-y-3">
                @foreach($users as $user)
                <a href="{{ route('profile.show', $user->id) }}"
                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}"
                             alt="{{ $user->name }}"
                             class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                    @else
                        <img src="https://i.pravatar.cc/50?u={{ $user->id }}"
                             alt="{{ $user->name }}"
                             class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                    @endif
                    <div>
                        <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Discussions Results --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="text-xl">💬</span>
                Diskusi yang Ditemukan
                <span class="ml-auto text-sm font-normal text-gray-500">
                    {{ $comments->total() }} hasil
                </span>
            </h3>

            @forelse($comments as $comment)
            <div class="border-b border-gray-100 last:border-0 py-4 hover:bg-gray-50 transition rounded-lg px-3 mb-2"
                 id="comment-{{ $comment->id }}">

                <div class="flex items-start gap-3 mb-3">
                    <a href="{{ route('profile.show', $comment->user->id ?? 1) }}" class="flex-shrink-0">
                        @if($comment->user && $comment->user->profile_photo)
                            <img src="{{ asset('storage/' . $comment->user->profile_photo) }}"
                                 alt="{{ $comment->user->name }}"
                                 class="w-10 h-10 rounded-full object-cover border-2 border-gray-100 hover:border-cyan-400 transition">
                        @else
                            <img src="https://i.pravatar.cc/50?u={{ $comment->user->id ?? 'deleted' }}"
                                 alt="{{ $comment->user->name ?? 'User' }}"
                                 class="w-10 h-10 rounded-full object-cover border-2 border-gray-100 hover:border-cyan-400 transition">
                        @endif
                    </a>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <a href="{{ route('profile.show', $comment->user->id ?? 1) }}"
                               class="font-bold text-gray-900 hover:text-cyan-600 transition">
                                {{ $comment->user->name ?? 'Deleted User' }}
                            </a>
                            <span class="text-gray-400">•</span>
                            <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>

                        @if($comment->title)
                        <h4 class="font-bold text-lg text-gray-900 mb-2">{{ $comment->title }}</h4>
                        @endif

                        <p class="text-gray-700 mb-3">
                            {{ Str::limit($comment->content, 200) }}
                        </p>

                        @if($comment->image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $comment->image) }}"
                                 alt="Post image"
                                 class="w-full max-h-64 rounded-xl border object-cover">
                        </div>
                        @endif

                        <div class="flex items-center gap-6 text-sm text-gray-600">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                                {{ $comment->likes->count() }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                {{ $comment->replies->count() }}
                            </span>
                            <a href="{{ route('forum.show', $comment->id) }}"
                               class="ml-auto text-cyan-600 hover:underline font-medium">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Tidak ada hasil ditemukan</h3>
                <p class="text-gray-500 mb-4">Coba gunakan kata kunci yang berbeda</p>
                <a href="{{ route('home') }}"
                   class="inline-block px-6 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600 transition">
                    Kembali ke Home
                </a>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($comments->hasPages())
        <div class="mt-6">
            {{ $comments->appends(['q' => $search])->links() }}
        </div>
        @endif

    </div>

</div>
@endsection