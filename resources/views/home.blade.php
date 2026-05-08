@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ===================== --}}
    {{-- KOLOM POSTINGAN    --}}
    {{-- ===================== --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Form Tambah Post Baru --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                        class="w-12 h-12 rounded-full object-cover border-2 border-cyan-200">
                    @else
                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 border-2 border-gray-300">
                        👤
                    </div>
                    @endif

                    <div>
                        <h3 class="font-bold text-gray-900">Tambahkan diskusi</h3>
                        <p class="text-sm text-gray-500">Bagikan pengalamanmu dengan komunitas</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data" class="p-5">
                @csrf

                <input type="text"
                    name="title"
                    class="w-full border-0 border-b border-gray-200 focus:border-cyan-500 focus:ring-0 px-0 py-3 text-lg font-semibold placeholder-gray-400"
                    placeholder="Judul diskusi (opsional)..."
                    maxlength="100">

                <textarea name="content"
                    class="w-full border-0 focus:ring-0 px-0 py-4 text-gray-700 placeholder-gray-400 resize-none"
                    rows="4"
                    placeholder="Apa yang ingin kamu diskusikan?"
                    required></textarea>

                <div id="imagePreviewContainer" class="hidden mt-4 relative">
                    <img id="imagePreview" src="" class="max-h-96 w-full object-cover rounded-xl border-2 border-gray-200">
                    <button type="button"
                        onclick="removeImage()"
                        class="absolute top-2 right-2 bg-red-500 text-white w-8 h-8 rounded-full hover:bg-red-600 transition flex items-center justify-center">
                        ✕
                    </button>
                </div>

                <input type="file" name="image" id="imageInput" class="hidden" accept="image/*" onchange="previewImage(event)">

                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                    <div class="flex gap-2">
                        <button type="button"
                            onclick="document.getElementById('imageInput').click()"
                            class="p-2 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>

                    <button type="submit"
                        class="bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-sm hover:shadow-md transition-all">
                        Posting
                    </button>
                </div>
            </form>
        </div>

        {{-- LOOPING POST --}}
        @forelse($comments as $comment)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all overflow-hidden"
            id="comment-{{ $comment->id }}">

            {{-- Header --}}
            <div class="p-5 border-b border-gray-50">
                <div class="flex items-start justify-between">
                    <div class="flex gap-3">
                        <a href="{{ route('profile.show', $comment->user->id ?? 1) }}" class="flex-shrink-0">
                            @if($comment->user && $comment->user->profile_photo)
                            <img src="{{ asset('storage/' . $comment->user->profile_photo) }}"
                                class="w-12 h-12 rounded-full object-cover border-2 border-gray-100 hover:border-cyan-400 transition">
                            @else
                            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 border-2 border-gray-300">
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

                    @if(auth()->id() === $comment->user_id)
                    <button type="button"
                        class="delete-post-btn text-gray-400 hover:text-red-500 transition p-2"
                        data-id="{{ $comment->id }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                    @endif
                </div>
            </div>

            {{-- CLICKABLE BODY --}}
            <div class="cursor-pointer hover:bg-gray-50 transition post-body"
                data-url="{{ route('forum.show', $comment->id) }}">

                @if($comment->title)
                <div class="px-5 pt-5">
                    <h3 class="text-xl font-bold mb-2">{{ $comment->title }}</h3>
                </div>
                @endif

                <div class="px-5 {{ $comment->title ? '' : 'pt-5' }}">
                    <p class="text-gray-700 mb-3">
                        {{ Str::limit($comment->content, 200) }}
                    </p>
                </div>

                @if($comment->image)
                <div class="px-5 pb-4">
                    <img src="{{ asset('storage/' . $comment->image) }}"
                        alt="Post image"
                        class="w-full max-h-96 rounded-xl border object-cover">
                </div>
                @endif
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="px-5 py-3 border-t bg-gray-50">
                <div class="flex items-center gap-6 text-gray-600">

                    {{-- LIKE BUTTON --}}
                    @php
                    $isLiked = $comment->likes->contains('id', auth()->id());
                    @endphp
                    <button type="button"
                        class="love-btn flex items-center gap-2 transition {{ $isLiked ? 'text-red-500' : 'hover:text-red-500' }}"
                        data-id="{{ $comment->id }}"
                        onclick="event.stopPropagation()">
                        <svg class="w-5 h-5 like-icon" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="like-count">{{ $comment->likes->count() }}</span>
                    </button>

                    {{-- COMMENT BUTTON --}}
                    <a href="{{ route('forum.show', $comment->id) }}"
                        class="flex items-center gap-2 hover:text-cyan-500 transition"
                        onclick="event.stopPropagation()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span>{{ $comment->replies->count() }}</span>
                    </a>

                    {{-- SHARE BUTTON --}}
                    <button type="button"
                        class="share-btn flex items-center gap-2 hover:text-green-500 transition ml-auto"
                        data-url="{{ route('forum.show', $comment->id) }}"
                        data-title="{{ $comment->title ?? Str::limit($comment->content, 50) }}"
                        onclick="event.stopPropagation()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        <span class="share-text">Bagikan</span>
                    </button>

                </div>
            </div>

        </div>
        @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="text-6xl mb-4">💬</div>
            <h3 class="text-xl font-bold mb-2">Belum ada diskusi</h3>
            <p class="text-gray-500">Jadilah yang pertama memulai!</p>
        </div>
        @endforelse

    </div>


    {{-- ===================== --}}
    {{-- KOLOM KANAN      --}}
    {{-- ===================== --}}
    <div class="space-y-6">

        {{-- Hewan Peliharaan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-cyan-50 to-blue-50">
                <h2 class="font-bold text-lg flex items-center gap-2">
                    🐾 Hewan Peliharaan Saya
                </h2>
            </div>

            <div class="p-4">
                @forelse ($pets as $pet)
                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition mb-2">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-400 to-cyan-600 flex items-center justify-center text-white text-lg font-bold shadow">
                        {{ strtoupper(substr($pet->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">{{ $pet->name }}</p>
                        <p class="text-sm text-gray-500">{{ $pet->species }} {{ $pet->breed ? '- ' . $pet->breed : '' }}</p>
                    </div>
                    @if($pet->species == 'Kucing')
                    <span class="text-2xl">🐱</span>
                    @elseif($pet->species == 'Anjing')
                    <span class="text-2xl">🐕</span>
                    @elseif($pet->species == 'Kura-kura')
                    <span class="text-2xl">🐢</span>
                    @elseif($pet->species == 'Kelinci')
                    <span class="text-2xl">🐰</span>
                    @elseif($pet->species == 'Hamster')
                    <span class="text-2xl">🐹</span>
                    @elseif($pet->species == 'Burung')
                    <span class="text-2xl">🐦</span>
                    @else
                    <span class="text-2xl">🐾</span>
                    @endif
                </div>
                @empty
                <p class="text-center text-gray-400 py-4">Belum ada hewan peliharaan</p>
                @endforelse

                <a href="{{ route('hewan-saya') }}"
                    class="flex items-center justify-center gap-2 w-full mt-3 py-3 border-2 border-dashed border-cyan-300 rounded-xl text-cyan-600 font-semibold hover:bg-cyan-50 hover:border-cyan-400 transition">
                    <span class="text-xl">+</span> Tambah Hewan
                </a>
            </div>
        </div>

        {{-- Tren Diskusi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-red-50">
                <h2 class="font-bold text-lg flex items-center gap-2">
                    🔥 Tren Diskusi
                </h2>
            </div>

            <div class="p-4">
                @php
                $trendEmojis = ['💉', '🐱', '🐕', '🦴', '🏥', '💊', '🩺', '❤️'];
                @endphp

                @forelse($trending as $i => $topic)
                <a href="{{ route('forum.show', $topic->id) }}"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition mb-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold
                        @if($i == 0) bg-gradient-to-br from-yellow-400 to-orange-500
                        @elseif($i == 1) bg-gradient-to-br from-gray-300 to-gray-400
                        @elseif($i == 2) bg-gradient-to-br from-orange-300 to-orange-400
                        @else bg-gray-200 text-gray-600
                        @endif">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 truncate">
                            {{ $topic->title ?: Str::limit($topic->content, 30) }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $topic->replies_count ?? 0 }} Komentar
                        </p>
                    </div>
                    <span class="text-xl">{{ $trendEmojis[$i % count($trendEmojis)] }}</span>
                </a>
                @empty
                <p class="text-center text-gray-400 py-4">Belum ada diskusi trending</p>
                @endforelse

                <a href="{{ route('forum.index') }}"
                    class="block text-center mt-3 py-2 text-cyan-600 font-semibold hover:bg-cyan-50 rounded-lg transition">
                    Lihat semua →
                </a>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    // ========== IMAGE PREVIEW ==========
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        document.getElementById('imageInput').value = '';
        document.getElementById('imagePreviewContainer').classList.add('hidden');
    }

    // ========== CSRF TOKEN ==========
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // ========== POST BODY CLICK (Navigate to detail) ==========
    document.querySelectorAll('.post-body').forEach(el => {
        el.addEventListener('click', function() {
            window.location.href = this.dataset.url;
        });
    });

    // ========== LIKE BUTTON ==========
    document.querySelectorAll('.love-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();

            const id = this.dataset.id;
            const countEl = this.querySelector('.like-count');
            const iconEl = this.querySelector('.like-icon');
            const button = this;

            // Disable button sementara
            button.style.pointerEvents = 'none';

            try {
                const res = await fetch(`/comments/${id}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await res.json();
                console.log('Like response:', data);

                if (data.success) {
                    // Update count
                    countEl.textContent = data.likes_count;

                    // Toggle visual
                    if (data.is_liked) {
                        button.classList.add('text-red-500');
                        button.classList.remove('hover:text-red-500');
                        iconEl.setAttribute('fill', 'currentColor');
                    } else {
                        button.classList.remove('text-red-500');
                        button.classList.add('hover:text-red-500');
                        iconEl.setAttribute('fill', 'none');
                    }
                } else {
                    alert('Gagal: ' + (data.error || 'Unknown error'));
                }
            } catch (err) {
                console.error('Like error:', err);
                alert('Terjadi kesalahan. Pastikan Anda sudah login.');
            } finally {
                button.style.pointerEvents = 'auto';
            }
        });
    });

    // ========== SHARE BUTTON ==========
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();

            const url = window.location.origin + this.dataset.url;
            const title = this.dataset.title || 'Diskusi IngonCare';
            const textEl = this.querySelector('.share-text');
            const button = this;

            // Coba Web Share API dulu (mobile)
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: title,
                        text: 'Lihat diskusi ini di IngonCare',
                        url: url
                    });
                    return;
                } catch (err) {
                    // User cancelled atau error, fallback ke clipboard
                    if (err.name === 'AbortError') return;
                }
            }

            // Fallback: Copy ke clipboard
            try {
                await navigator.clipboard.writeText(url);
                textEl.textContent = 'Tersalin!';
                button.classList.add('text-green-500');

                setTimeout(() => {
                    textEl.textContent = 'Bagikan';
                    button.classList.remove('text-green-500');
                }, 2000);
            } catch (err) {
                // Fallback untuk browser lama
                const textarea = document.createElement('textarea');
                textarea.value = url;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);

                textEl.textContent = 'Tersalin!';
                button.classList.add('text-green-500');
                setTimeout(() => {
                    textEl.textContent = 'Bagikan';
                    button.classList.remove('text-green-500');
                }, 2000);
            }
        });
    });

    // ========== DELETE BUTTON ==========
    document.querySelectorAll('.delete-post-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();

            if (!confirm('Yakin ingin menghapus diskusi ini?')) return;

            const id = this.dataset.id;

            try {
                const res = await fetch(`/comments/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();

                if (data.success) {
                    const commentEl = document.getElementById(`comment-${id}`);
                    commentEl.style.transition = 'opacity 0.3s, transform 0.3s';
                    commentEl.style.opacity = '0';
                    commentEl.style.transform = 'translateX(-20px)';
                    setTimeout(() => commentEl.remove(), 300);
                } else {
                    alert('Gagal menghapus: ' + (data.message || 'Unknown error'));
                }
            } catch (err) {
                console.error('Delete error:', err);
                alert('Terjadi kesalahan saat menghapus.');
            }
        });
    });

    // Debug log
    console.log('Scripts loaded! CSRF Token:', csrfToken ? 'Found' : 'NOT FOUND');
</script>
@endpush