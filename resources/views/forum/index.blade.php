@extends('layouts.app')

@section('title', 'Forum Diskusi')

@section('content')

<div class="min-h-screen">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-2xl p-8 mb-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Forum Diskusi</h1>
                <p class="text-cyan-100">Berbagi pengalaman dan bertanya seputar hewan peliharaan</p>
            </div>
        </div>
    </div>

    <div class="w-full">

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Filter Tabs --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-1">
                <div class="flex gap-2">
                    <a href="{{ route('forum.index', ['filter' => 'trending']) }}"
                        class="flex-1 text-center py-3 rounded-xl font-semibold transition-all duration-200
                              {{ $filter === 'trending' ? 'bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50' }}">
                        🔥 Trending
                    </a>
                    <a href="{{ route('forum.index', ['filter' => 'terbaru']) }}"
                        class="flex-1 text-center py-3 rounded-xl font-semibold transition-all duration-200
                              {{ $filter === 'terbaru' ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50' }}">
                        🕐 Terbaru
                    </a>
                    <a href="{{ route('forum.index', ['filter' => 'populer']) }}"
                        class="flex-1 text-center py-3 rounded-xl font-semibold transition-all duration-200
                              {{ $filter === 'populer' ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50' }}">
                        ⭐ Populer
                    </a>
                </div>
            </div>

            {{-- Info Banner --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="text-2xl">
                        @if($filter === 'trending') 🔥
                        @elseif($filter === 'populer') ⭐
                        @else 🕐
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">
                            @if($filter === 'trending')
                            <span class="text-orange-600">Trending:</span> Diskusi yang sedang ramai dibicarakan dalam beberapa hari terakhir
                            @elseif($filter === 'populer')
                            <span class="text-purple-600">Populer:</span> Diskusi dengan interaksi terbanyak sepanjang waktu
                            @else
                            <span class="text-cyan-600">Terbaru:</span> Diskusi yang baru saja diposting, diurutkan dari yang paling baru
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- List Diskusi --}}
            @forelse($comments as $comment)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all overflow-hidden group"
                id="comment-{{ $comment->id }}">

                {{-- Badge Trending/Populer --}}
                @if($filter === 'trending' && $comment->likes_count + $comment->replies_count > 5)
                <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-4 py-1 text-xs font-semibold">
                    🔥 TRENDING - {{ $comment->likes_count + $comment->replies_count }} Interaksi
                </div>
                @elseif($filter === 'populer' && $comment->likes_count + $comment->replies_count > 10)
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-1 text-xs font-semibold">
                    ⭐ POPULER - {{ $comment->likes_count + $comment->replies_count }} Interaksi
                </div>
                @endif

                {{-- Post Header --}}
                <div class="p-5 border-b border-gray-50">
                    <div class="flex items-start justify-between">
                        <div class="flex gap-3">
                            <a href="{{ route('profile.show', $comment->user->id ?? 1) }}" class="flex-shrink-0">
                                @if($comment->user && $comment->user->profile_photo)
                                <img src="{{ asset('storage/' . $comment->user->profile_photo) }}"
                                    alt="{{ $comment->user->name }}"
                                    class="w-12 h-12 rounded-full object-cover border-2 border-gray-100 hover:border-cyan-400 transition">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                        👤
                                    </div>
                                @endif
                            </a>
                            <div>
                                <a href="{{ route('profile.show', $comment->user->id ?? 1) }}"
                                    class="font-bold text-gray-900 hover:text-cyan-600 transition">
                                    {{ $comment->user->name ?? 'Deleted User' }}
                                </a>
                                <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        @if(auth()->check() && auth()->id() === $comment->user_id)
                        <button type="button"
                            class="delete-post-btn text-gray-400 hover:text-red-500 transition p-2 opacity-0 group-hover:opacity-100"
                            data-id="{{ $comment->id }}"
                            title="Hapus post">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Post Content --}}
                <div class="p-5 cursor-pointer hover:bg-gray-50 transition"
                    onclick="window.location='{{ route('forum.show', $comment->id) }}'">

                    @if($comment->title)
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        {{ $comment->title }}
                    </h3>
                    @endif

                    <p class="text-gray-700 leading-relaxed mb-3">
                        {{ Str::limit($comment->content, 200) }}
                    </p>

                    @if($comment->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $comment->image) }}"
                            alt="Post image"
                            class="w-full max-h-80 rounded-xl border object-cover">
                    </div>
                    @endif
                </div>

                {{-- Post Actions --}}
                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
                    <div class="flex items-center gap-6 text-gray-600">

                        <button type="button"
                            class="love-btn flex items-center gap-2 hover:text-red-500 transition font-medium"
                            data-id="{{ $comment->id }}"
                            onclick="event.stopPropagation()">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>
                            <span class="like-count">{{ $comment->likes->count() }}</span>
                        </button>

                        <a href="{{ route('forum.show', $comment->id) }}"
                            class="flex items-center gap-2 hover:text-cyan-500 transition font-medium"
                            onclick="event.stopPropagation()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span>{{ $comment->replies->count() }}</span>
                        </a>

                        <button type="button"
                            class="share-post-btn flex items-center gap-2 hover:text-green-500 transition font-medium ml-auto"
                            data-id="{{ $comment->id }}"
                            data-url="{{ route('forum.show', $comment->id) }}"
                            onclick="event.stopPropagation()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                            <span>Bagikan</span>
                        </button>

                    </div>
                </div>

            </div>
            @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada diskusi</h3>
                <p class="text-gray-500 mb-4">Jadilah yang pertama memulai diskusi!</p>
                <a href="{{ route('home') }}" class="inline-block px-6 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600 transition">
                    Buat Diskusi Baru
                </a>
            </div>
            @endforelse

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $comments->links() }}
            </div>

    </div>

</div>

{{-- JavaScript Interactions --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = document.querySelector('meta[name="csrf-token"]').content;

        // Like Button
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.love-btn');
            if (!btn) return;

            e.stopPropagation();
            const id = btn.dataset.id;
            const count = btn.querySelector('.like-count');

            fetch(`/comments/${id}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    }
                })
                .then(res => res.json())
                .then(data => {
                    count.textContent = data.likes_count ?? 0;
                    btn.classList.add('text-red-500');
                    setTimeout(() => btn.classList.remove('text-red-500'), 300);
                })
                .catch(err => console.error(err));
        });

        // Share Button
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.share-post-btn');
            if (!btn) return;

            e.stopPropagation();
            const url = window.location.origin + btn.dataset.url;

            navigator.clipboard.writeText(url).then(() => {
                const originalText = btn.querySelector('span').textContent;
                btn.querySelector('span').textContent = 'Link disalin!';
                btn.classList.add('text-green-500');

                setTimeout(() => {
                    btn.querySelector('span').textContent = originalText;
                    btn.classList.remove('text-green-500');
                }, 2000);
            });
        });

        // Delete Post
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.delete-post-btn');
            if (!btn) return;

            e.stopPropagation();

            if (!confirm('Yakin ingin menghapus diskusi ini?')) return;

            const id = btn.dataset.id;

            fetch(`/comments/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`comment-${id}`).remove();
                    }
                })
                .catch(err => alert('Gagal menghapus diskusi'));
        });
    });
</script>

@endsection