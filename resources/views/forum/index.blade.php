@extends('layouts.app')

@section('title', 'Forum Diskusi')

@section('content')

<div class="min-h-screen">

    {{-- Header --}}
    <div class="text-white rounded-2xl p-8 mb-6 shadow-lg"
         style="background: linear-gradient(135deg, #2D1B69 0%, #9F86C0 100%);">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Forum Diskusi</h1>
                <p style="color:#EDE4F5;">Berbagi pengalaman dan bertanya seputar hewan peliharaan</p>
            </div>
        </div>
    </div>

    <div class="w-full">
        <div class="space-y-6">

            {{-- Filter Tabs --}}
            <div class="bg-white rounded-2xl overflow-hidden p-1"
                 style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
                <div class="flex gap-2">
                    <a href="{{ route('forum.index', ['filter' => 'trending']) }}"
                        class="flex-1 text-center py-3 rounded-xl font-semibold transition-all text-sm"
                        style="{{ $filter === 'trending' ? 'background:linear-gradient(135deg,#ef4444,#f97316); color:white;' : 'color:#5E4B8B;' }}"
                        onmouseover="if('{{ $filter }}' !== 'trending') this.style.background='#EDE4F5'"
                        onmouseout="if('{{ $filter }}' !== 'trending') this.style.background=''">
                        🔥 Trending
                    </a>
                    <a href="{{ route('forum.index', ['filter' => 'terbaru']) }}"
                        class="flex-1 text-center py-3 rounded-xl font-semibold transition-all text-sm"
                        style="{{ $filter === 'terbaru' ? 'background:linear-gradient(135deg,#9F86C0,#5E4B8B); color:white;' : 'color:#5E4B8B;' }}"
                        onmouseover="if('{{ $filter }}' !== 'terbaru') this.style.background='#EDE4F5'"
                        onmouseout="if('{{ $filter }}' !== 'terbaru') this.style.background=''">
                        🕐 Terbaru
                    </a>
                    <a href="{{ route('forum.index', ['filter' => 'populer']) }}"
                        class="flex-1 text-center py-3 rounded-xl font-semibold transition-all text-sm"
                        style="{{ $filter === 'populer' ? 'background:linear-gradient(135deg,#5E4B8B,#2D1B69); color:white;' : 'color:#5E4B8B;' }}"
                        onmouseover="if('{{ $filter }}' !== 'populer') this.style.background='#EDE4F5'"
                        onmouseout="if('{{ $filter }}' !== 'populer') this.style.background=''">
                        ⭐ Populer
                    </a>
                </div>
            </div>

            {{-- Info Banner --}}
            <div class="p-4 rounded-xl" style="background:#EDE4F5; border-left:4px solid #9F86C0;">
                <div class="flex items-center gap-3">
                    <div class="text-2xl">
                        @if($filter === 'trending') 🔥
                        @elseif($filter === 'populer') ⭐
                        @else 🕐
                        @endif
                    </div>
                    <p class="font-semibold text-sm" style="color:#5E4B8B;">
                        @if($filter === 'trending')
                            Diskusi yang sedang ramai dibicarakan dalam beberapa hari terakhir
                        @elseif($filter === 'populer')
                            Diskusi dengan interaksi terbanyak sepanjang waktu
                        @else
                            Diskusi terbaru, diurutkan dari yang paling baru
                        @endif
                    </p>
                </div>
            </div>

            {{-- List Diskusi --}}
            @forelse($comments as $comment)
            <div class="bg-white rounded-2xl overflow-hidden transition-all group"
                style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);"
                id="comment-{{ $comment->id }}"
                onmouseover="this.style.boxShadow='0 4px 20px rgba(159,134,192,0.18)'"
                onmouseout="this.style.boxShadow='0 2px 12px rgba(159,134,192,0.08)'">

                {{-- Badge Trending/Populer --}}
                @if($filter === 'trending' && $comment->likes_count + $comment->replies_count > 5)
                <div class="px-4 py-1 text-xs font-semibold text-white"
                     style="background:linear-gradient(135deg,#ef4444,#f97316);">
                    🔥 TRENDING — {{ $comment->likes_count + $comment->replies_count }} Interaksi
                </div>
                @elseif($filter === 'populer' && $comment->likes_count + $comment->replies_count > 10)
                <div class="px-4 py-1 text-xs font-semibold text-white"
                     style="background:linear-gradient(135deg,#5E4B8B,#2D1B69);">
                    ⭐ POPULER — {{ $comment->likes_count + $comment->replies_count }} Interaksi
                </div>
                @endif

                {{-- Post Header --}}
                <div class="p-5" style="border-bottom:1.5px solid #EDE4F5;">
                    <div class="flex items-start justify-between">
                        <div class="flex gap-3">
                            <a href="{{ route('profile.show', $comment->user->id ?? 1) }}" class="flex-shrink-0">
                                @if($comment->user && $comment->user->profile_photo)
                                <img src="{{ asset('storage/' . $comment->user->profile_photo) }}"
                                    alt="{{ $comment->user->name }}"
                                    class="w-12 h-12 rounded-full object-cover"
                                    style="border:2px solid #CDB4DB;">
                                @else
                                <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                     style="background:#EDE4F5; border:2px solid #CDB4DB;">
                                    <i class="ti ti-user" style="font-size:18px; color:#9F86C0;" aria-hidden="true"></i>
                                </div>
                                @endif
                            </a>
                            <div>
                                <a href="{{ route('profile.show', $comment->user->id ?? 1) }}"
                                    class="font-bold hover:underline" style="color:#2D1B69;">
                                    {{ $comment->user->name ?? 'Deleted User' }}
                                </a>
                                <p class="text-sm" style="color:#9ca3af;">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        @if(auth()->check() && auth()->id() === $comment->user_id)
                        <button type="button"
                            class="delete-post-btn p-2 rounded-lg opacity-0 group-hover:opacity-100 transition"
                            data-id="{{ $comment->id }}" aria-label="Hapus post"
                            onmouseover="this.style.background='#fef2f2'"
                            onmouseout="this.style.background=''">
                            <i class="ti ti-trash" style="font-size:17px; color:#9ca3af;" aria-hidden="true"></i>
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Post Content --}}
                <div class="px-5 py-4 cursor-pointer transition"
                    onmouseover="this.style.background='#FDFAFF'"
                    onmouseout="this.style.background=''"
                    onclick="window.location='{{ route('forum.show', $comment->id) }}'">

                    @if($comment->title)
                    <h3 class="text-lg font-bold mb-2" style="color:#2D1B69;">
                        {{ $comment->title }}
                    </h3>
                    @endif

                    <p class="leading-relaxed text-sm mb-3" style="color:#4b5563;">
                        {{ Str::limit($comment->content, 200) }}
                    </p>

                    @if($comment->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $comment->image) }}"
                            alt="Post image"
                            class="w-full max-h-80 rounded-xl object-cover"
                            style="border:1.5px solid #EDE4F5;">
                    </div>
                    @endif
                </div>

                {{-- Post Actions --}}
                <div class="px-5 py-3" style="border-top:1.5px solid #EDE4F5; background:#FDFAFF;">
                    <div class="flex items-center gap-5 text-sm">

                        <button type="button"
                            class="love-btn flex items-center gap-1.5 transition font-medium"
                            style="color:#9ca3af;"
                            data-id="{{ $comment->id }}"
                            onclick="event.stopPropagation()"
                            onmouseover="this.style.color='#9F86C0'"
                            onmouseout="this.style.color='#9ca3af'">
                            <i class="ti ti-heart like-icon" style="font-size:18px;" aria-hidden="true"></i>
                            <span class="like-count">{{ $comment->likes->count() }}</span>
                        </button>

                        <a href="{{ route('forum.show', $comment->id) }}"
                            class="flex items-center gap-1.5 transition font-medium"
                            style="color:#9ca3af;"
                            onmouseover="this.style.color='#9F86C0'"
                            onmouseout="this.style.color='#9ca3af'"
                            onclick="event.stopPropagation()">
                            <i class="ti ti-message" style="font-size:18px;" aria-hidden="true"></i>
                            <span>{{ $comment->replies->count() }}</span>
                        </a>

                        <button type="button"
                            class="share-post-btn flex items-center gap-1.5 transition font-medium ml-auto"
                            style="color:#9ca3af;"
                            data-id="{{ $comment->id }}"
                            data-url="{{ route('forum.show', $comment->id) }}"
                            onclick="event.stopPropagation()"
                            onmouseover="this.style.color='#9F86C0'"
                            onmouseout="this.style.color='#9ca3af'">
                            <i class="ti ti-share" style="font-size:18px;" aria-hidden="true"></i>
                            <span>Bagikan</span>
                        </button>

                    </div>
                </div>

            </div>
            @empty
            <div class="bg-white rounded-2xl p-12 text-center" style="border:1.5px solid #EDE4F5;">
                <i class="ti ti-message-off" style="font-size:48px; color:#CDB4DB;" aria-hidden="true"></i>
                <h3 class="text-xl font-bold mt-4 mb-2" style="color:#2D1B69;">Belum ada diskusi</h3>
                <p class="mb-4" style="color:#9ca3af;">Jadilah yang pertama memulai diskusi!</p>
                <a href="{{ route('home') }}"
                   class="inline-block px-6 py-2 rounded-xl text-sm font-semibold text-white transition"
                   style="background:#9F86C0;"
                   onmouseover="this.style.background='#5E4B8B'"
                   onmouseout="this.style.background='#9F86C0'">
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
</div>

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
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token }
                })
                .then(res => res.json())
                .then(data => {
                    count.textContent = data.likes_count ?? 0;
                    btn.style.color = data.is_liked ? '#9F86C0' : '#9ca3af';
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
                const span = btn.querySelector('span');
                const orig = span.textContent;
                span.textContent = 'Link disalin!';
                btn.style.color = '#9F86C0';
                setTimeout(() => { span.textContent = orig; btn.style.color = '#9ca3af'; }, 2000);
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
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const el = document.getElementById(`comment-${id}`);
                        el.style.opacity = '0';
                        el.style.transform = 'translateX(-10px)';
                        el.style.transition = 'all 0.3s';
                        setTimeout(() => el.remove(), 300);
                    }
                })
                .catch(err => console.error(err));
        });
    });
</script>

@endsection