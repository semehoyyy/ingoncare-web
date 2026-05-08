{{-- resources/views/forum/show.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    .love-btn,
    .reply-btn,
    .share-btn,
    .toggle-replies-btn {
        transition: all 0.2s ease;
    }

    .love-btn:hover,
    .reply-btn:hover,
    .share-btn:hover,
    .toggle-replies-btn:hover {
        transform: scale(1.05);
    }

    .love-btn:active,
    .reply-btn:active,
    .share-btn:active,
    .toggle-replies-btn:active {
        transform: scale(0.95);
    }

    .reply-icon {
        transition: transform 0.3s ease;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">

    {{-- Header --}}
    <div class="sticky top-0 bg-white/90 backdrop-blur-xl border-b border-gray-200 z-10 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-3">
            <div class="flex items-center gap-3">
                <button onclick="window.history.back()"
                    class="p-2 hover:bg-gray-100 rounded-full transition-all duration-200 group active:scale-95">
                    <svg class="w-5 h-5 text-gray-600 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Diskusi & Balasan</h1>
                    <p class="text-xs text-gray-500">{{ $comment->total_replies_count }} balasan</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="max-w-4xl mx-auto px-4 pt-4">
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2.5 rounded-lg flex items-center justify-between shadow-sm">
            <span class="text-sm font-medium">✓ {{ session('success') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800">✕</button>
        </div>
    </div>
    @endif

    {{-- Main Content --}}
    <div class="max-w-4xl mx-auto px-4 py-4 space-y-4">

        {{-- Main Post --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Post Header --}}
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('profile.show', $comment->user->id ?? 1) }}">
                            @if($comment->user && $comment->user->profile_photo)
                            <img src="{{ asset('storage/' . $comment->user->profile_photo) }}"
                                alt="{{ $comment->user->name }}"
                                class="w-12 h-12 rounded-full object-cover hover:border-cyan-400 border-2 border-gray-100 transition">
                            @else
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                                👤
                            </div>
                            @endif
                        </a>
                        <div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('profile.show', $comment->user->id ?? 1) }}"
                                    class="font-semibold text-gray-900 hover:text-cyan-600 transition">
                                    {{ $comment->user->name ?? 'Deleted User' }}
                                </a>
                                <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full font-medium">
                                    Penulis
                                </span>
                            </div>
                            <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if(auth()->check() && auth()->id() === $comment->user_id)
                    <button type="button"
                        class="delete-btn text-gray-400 hover:text-red-500 transition-colors"
                        data-id="{{ $comment->id }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                    @endif
                </div>
            </div>

            {{-- Post Content --}}
            <div class="p-4">
                @if($comment->title)
                <h2 class="text-lg font-bold text-gray-900 mb-2">{{ $comment->title }}</h2>
                @endif

                <p class="text-gray-700 leading-relaxed mb-3">{{ $comment->content }}</p>

                @if($comment->image)
                <div class="mt-3 mb-3">
                    <img src="{{ asset('storage/' . $comment->image) }}"
                        alt="Post image"
                        class="w-full max-h-96 rounded-xl border object-cover cursor-pointer hover:opacity-95 transition"
                        onclick="window.open(this.src, '_blank')">
                </div>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="px-4 py-2.5 bg-gray-50 border-t border-gray-100">
                <div class="flex items-center gap-4 text-sm">

                    @php
                    $isLiked = $comment->likes->contains('id', auth()->id());
                    @endphp
                    <button type="button"
                        class="love-btn flex items-center gap-1.5 transition-colors {{ $isLiked ? 'text-red-500' : 'text-gray-600 hover:text-red-500' }}"
                        data-id="{{ $comment->id }}">
                        <svg class="w-5 h-5 like-icon" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="like-count font-medium">{{ $comment->likes->count() }}</span>
                    </button>

                    <button type="button"
                        class="reply-btn flex items-center gap-1.5 text-gray-600 hover:text-blue-500 transition-colors"
                        data-id="{{ $comment->id }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span class="font-medium">Balas</span>
                    </button>

                    <button type="button"
                        class="share-btn flex items-center gap-1.5 text-gray-600 hover:text-green-500 transition-colors ml-auto"
                        data-url="{{ route('forum.show', $comment->id) }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        <span class="font-medium">Bagikan</span>
                    </button>
                </div>
            </div>

            {{-- Reply Form (Hidden by default) --}}
            <div class="hidden border-t border-gray-200 p-4 bg-gray-50" id="reply-form-{{ $comment->id }}">
                <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <div class="flex gap-2">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                            class="w-8 h-8 rounded-full object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                👤
                            </div>
                        @endif

                        <div class="flex-1">
                            <textarea name="content"
                                rows="2"
                                class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                placeholder="Tulis balasan Anda..."
                                required></textarea>

                            <div id="main-reply-image-preview" class="hidden mt-2 relative">
                                <img id="main-reply-image-display" src="" class="max-h-40 rounded-lg border">
                                <button type="button"
                                    onclick="removeMainReplyImage()"
                                    class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-full hover:bg-red-600 transition flex items-center justify-center text-xs">
                                    ✕
                                </button>
                            </div>

                            <input type="file" name="image" id="main-reply-image-input" class="hidden" accept="image/*" onchange="previewMainReplyImage(event)">

                            <div class="flex gap-2 mt-2 justify-between">
                                <button type="button"
                                    onclick="document.getElementById('main-reply-image-input').click()"
                                    class="p-1.5 hover:bg-gray-200 rounded transition">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </button>

                                <div class="flex gap-2">
                                    <button type="button"
                                        class="cancel-reply-btn px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-200 rounded-lg transition-colors"
                                        data-id="{{ $comment->id }}">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-1.5 text-sm bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium">
                                        Kirim
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Divider --}}
        @if($comment->replies->count() > 0)
        <div class="flex items-center gap-2 my-4">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="text-xs text-gray-600 font-medium px-3 py-1 bg-gray-100 rounded-full">
                {{ $comment->total_replies_count }} Balasan
            </span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>
        @endif

        {{-- Nested Replies (Using Recursive Component) --}}
        <div class="space-y-3">
            @forelse($comment->replies as $reply)
            @include('partials.comment-item', [
            'comment' => $reply,
            'depth' => 0,
            'rootId' => $comment->id
            ])
            @empty
            <div class="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-1">Belum ada balasan</h3>
                <p class="text-sm text-gray-500">Klik tombol Balas untuk membalas diskusi ini!</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Floating Action Button --}}
    <button type="button"
        class="reply-btn fixed bottom-6 right-6 w-12 h-12 bg-blue-500 text-white rounded-full shadow-lg hover:bg-blue-600 active:scale-95 transition-all duration-200 flex items-center justify-center z-20"
        data-id="{{ $comment->id }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </button>
</div>

{{-- JavaScript --}}
<script>
    // Main reply image preview
    function previewMainReplyImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('main-reply-image-display').src = e.target.result;
                document.getElementById('main-reply-image-preview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function removeMainReplyImage() {
        document.getElementById('main-reply-image-input').value = '';
        document.getElementById('main-reply-image-preview').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const token = document.querySelector('meta[name="csrf-token"]').content;

        // === LIKE BUTTON ===
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.love-btn');
            if (!btn) return;

            const id = btn.dataset.id;
            const count = btn.querySelector('.like-count');
            const icon = btn.querySelector('.like-icon');

            fetch(`/comments/${id}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        count.textContent = data.likes_count ?? 0;
                        if (data.is_liked) {
                            btn.classList.add('text-red-500');
                            btn.classList.remove('text-gray-600', 'hover:text-red-500');
                            icon.setAttribute('fill', 'currentColor');
                        } else {
                            btn.classList.remove('text-red-500');
                            btn.classList.add('text-gray-600', 'hover:text-red-500');
                            icon.setAttribute('fill', 'none');
                        }
                    }
                })
                .catch(err => console.error('Error:', err));
        });

        // === REPLY BUTTON (Improved) ===
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.reply-btn');
            if (!btn) return;

            e.preventDefault();
            e.stopPropagation();

            const id = btn.dataset.id;
            const form = document.getElementById('reply-form-' + id);

            if (form) {
                const isHidden = form.classList.contains('hidden');
                form.classList.toggle('hidden');

                if (isHidden) {
                    const textarea = form.querySelector('textarea');
                    if (textarea) {
                        setTimeout(() => {
                            textarea.focus();
                            form.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest'
                            });
                        }, 100);
                    }
                }
            }
        });

        // === CANCEL REPLY ===
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.cancel-reply-btn');
            if (!btn) return;

            const id = btn.dataset.id;
            const form = document.getElementById('reply-form-' + id);

            if (form) {
                form.classList.add('hidden');
                const textarea = form.querySelector('textarea');
                if (textarea) textarea.value = '';
            }
        });

        // === TOGGLE REPLIES (EXPAND/COLLAPSE) ===
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.toggle-replies-btn');
            if (!btn) return;

            const id = btn.dataset.id;
            const container = document.getElementById(`replies-container-${id}`);
            const icon = btn.querySelector('.reply-icon');
            const text = btn.querySelector('.reply-text');

            if (!container || !text) return;

            const count = text.textContent.match(/\d+/)?.[0] || '0';
            const isHidden = container.classList.contains('hidden');

            container.classList.toggle('hidden');

            if (isHidden) {
                // Expanding
                icon.style.transform = 'rotate(-180deg)';
                text.textContent = `Sembunyikan ${count} balasan`;
                btn.classList.add('bg-cyan-50');
            } else {
                // Collapsing
                icon.style.transform = 'rotate(0deg)';
                text.textContent = `Lihat ${count} balasan`;
                btn.classList.remove('bg-cyan-50');
            }
        });

        // === SHARE BUTTON ===
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.share-btn');
            if (!btn) return;

            const url = window.location.origin + btn.dataset.url;

            navigator.clipboard.writeText(url).then(() => {
                const originalText = btn.querySelector('span').textContent;
                btn.querySelector('span').textContent = 'Link disalin!';
                btn.classList.add('text-green-500');

                setTimeout(() => {
                    btn.querySelector('span').textContent = originalText;
                    btn.classList.remove('text-green-500');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
                alert('Gagal menyalin link');
            });
        });

        // === DELETE BUTTON ===
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.delete-btn');
            if (!btn) return;

            if (!confirm('Yakin ingin menghapus?')) return;

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
                        const element = document.getElementById('comment-' + id);
                        if (element) {
                            element.style.opacity = '0';
                            element.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                element.remove();
                                if (id == '{{ $comment->id }}') {
                                    window.location.href = '{{ route('home') }}';
                                }
                            }, 300);
                        }
                    }
                })
                .catch(err => console.error('Error:', err));
        });
    });
</script>

@endsection