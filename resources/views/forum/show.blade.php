{{-- resources/views/forum/show.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    .love-btn, .reply-btn, .share-btn, .toggle-replies-btn { transition: all 0.2s ease; }
    .love-btn:hover, .reply-btn:hover, .share-btn:hover, .toggle-replies-btn:hover { transform: scale(1.05); }
    .love-btn:active, .reply-btn:active, .share-btn:active, .toggle-replies-btn:active { transform: scale(0.95); }
    .reply-icon { transition: transform 0.3s ease; }
</style>

<div class="min-h-screen" style="background:#F5F0FA;">

    {{-- Header --}}
    <div class="sticky top-0 z-10" style="background:white; border-bottom:1.5px solid #EDE4F5; box-shadow:0 2px 8px rgba(159,134,192,0.08);">
        <div class="max-w-4xl mx-auto px-4 py-3">
            <div class="flex items-center gap-3">
                <button onclick="window.history.back()"
                    class="w-9 h-9 rounded-full flex items-center justify-center transition"
                    style="background:#EDE4F5;"
                    onmouseover="this.style.background='#CDB4DB'"
                    onmouseout="this.style.background='#EDE4F5'">
                    <i class="ti ti-arrow-left" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
                </button>
                <div>
                    <h1 class="text-lg font-bold" style="color:#2D1B69;">Diskusi & Balasan</h1>
                    <p class="text-xs" style="color:#9ca3af;">{{ $comment->total_replies_count }} balasan</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="max-w-4xl mx-auto px-4 pt-4">
        <div class="px-4 py-2.5 rounded-xl flex items-center justify-between"
             style="background:#EDE4F5; border:1px solid #CDB4DB;">
            <span class="text-sm font-medium" style="color:#5E4B8B;">✓ {{ session('success') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="color:#9F86C0;">✕</button>
        </div>
    </div>
    @endif

    {{-- Main Content --}}
    <div class="max-w-4xl mx-auto px-4 py-4 space-y-4">

        {{-- Main Post --}}
        <div class="bg-white rounded-2xl overflow-hidden" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">

            {{-- Post Header --}}
            <div class="p-4" style="border-bottom:1.5px solid #EDE4F5;">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('profile.show', $comment->user->id ?? 1) }}">
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
                            <div class="flex items-center gap-2">
                                <a href="{{ route('profile.show', $comment->user->id ?? 1) }}"
                                    class="font-semibold hover:underline" style="color:#2D1B69;">
                                    {{ $comment->user->name ?? 'Deleted User' }}
                                </a>
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                      style="background:#EDE4F5; color:#9F86C0;">
                                    Penulis
                                </span>
                            </div>
                            <p class="text-xs" style="color:#9ca3af;">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if(auth()->check() && auth()->id() === $comment->user_id)
                    <button type="button"
                        class="delete-btn p-2 rounded-lg transition"
                        data-id="{{ $comment->id }}"
                        style="color:#9ca3af;"
                        onmouseover="this.style.color='#ef4444'; this.style.background='#fef2f2'"
                        onmouseout="this.style.color='#9ca3af'; this.style.background=''">
                        <i class="ti ti-trash" style="font-size:18px;" aria-hidden="true"></i>
                    </button>
                    @endif
                </div>
            </div>

            {{-- Post Content --}}
            <div class="p-4">
                @if($comment->title)
                <h2 class="text-lg font-bold mb-2" style="color:#2D1B69;">{{ $comment->title }}</h2>
                @endif

                <p class="leading-relaxed mb-3" style="color:#4b5563;">{{ $comment->content }}</p>

                @if($comment->image)
                <div class="mt-3 mb-3">
                    <img src="{{ asset('storage/' . $comment->image) }}"
                        alt="Post image"
                        class="w-full max-h-96 rounded-xl object-cover cursor-pointer hover:opacity-95 transition"
                        style="border:1.5px solid #EDE4F5;"
                        onclick="window.open(this.src, '_blank')">
                </div>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="px-4 py-2.5" style="background:#FDFAFF; border-top:1.5px solid #EDE4F5;">
                <div class="flex items-center gap-4 text-sm">

                    @php $isLiked = $comment->likes->contains('id', auth()->id()); @endphp
                    <button type="button"
                        class="love-btn flex items-center gap-1.5 transition"
                        style="color: {{ $isLiked ? '#9F86C0' : '#9ca3af' }};"
                        data-id="{{ $comment->id }}">
                        <i class="ti ti-heart like-icon" style="font-size:18px;" aria-hidden="true"></i>
                        <span class="like-count font-medium">{{ $comment->likes->count() }}</span>
                    </button>

                    <button type="button"
                        class="reply-btn flex items-center gap-1.5 transition"
                        style="color:#9ca3af;"
                        data-id="{{ $comment->id }}"
                        onmouseover="this.style.color='#9F86C0'"
                        onmouseout="this.style.color='#9ca3af'">
                        <i class="ti ti-message" style="font-size:18px;" aria-hidden="true"></i>
                        <span class="font-medium">Balas</span>
                    </button>

                    <button type="button"
                        class="share-btn flex items-center gap-1.5 transition ml-auto"
                        style="color:#9ca3af;"
                        data-url="{{ route('forum.show', $comment->id) }}"
                        onmouseover="this.style.color='#9F86C0'"
                        onmouseout="this.style.color='#9ca3af'">
                        <i class="ti ti-share" style="font-size:18px;" aria-hidden="true"></i>
                        <span class="font-medium">Bagikan</span>
                    </button>
                </div>
            </div>

            {{-- Reply Form --}}
            <div class="hidden border-t p-4" style="border-color:#EDE4F5; background:#FDFAFF;" id="reply-form-{{ $comment->id }}">
                <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <div class="flex gap-2">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                            class="w-9 h-9 rounded-full object-cover flex-shrink-0"
                            style="border:2px solid #CDB4DB;">
                        @else
                            <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0"
                                 style="background:#EDE4F5; border:2px solid #CDB4DB;">
                                <i class="ti ti-user" style="font-size:14px; color:#9F86C0;" aria-hidden="true"></i>
                            </div>
                        @endif

                        <div class="flex-1">
                            <textarea name="content" rows="2"
                                class="w-full p-2.5 text-sm rounded-xl resize-none focus:outline-none focus:ring-2"
                                style="border:1.5px solid #CDB4DB; background:#FDFAFF; focus-ring-color:#9F86C0;"
                                placeholder="Tulis balasan Anda..."
                                required></textarea>

                            <div id="main-reply-image-preview" class="hidden mt-2 relative">
                                <img id="main-reply-image-display" src="" class="max-h-40 rounded-xl"
                                     style="border:1.5px solid #EDE4F5;">
                                <button type="button"
                                    onclick="removeMainReplyImage()"
                                    class="absolute top-1 right-1 w-6 h-6 rounded-full flex items-center justify-center text-white text-xs"
                                    style="background:#9F86C0;">
                                    ✕
                                </button>
                            </div>

                            <input type="file" name="image" id="main-reply-image-input" class="hidden" accept="image/*" onchange="previewMainReplyImage(event)">

                            <div class="flex gap-2 mt-2 justify-between">
                                <button type="button"
                                    onclick="document.getElementById('main-reply-image-input').click()"
                                    class="p-1.5 rounded-lg transition"
                                    onmouseover="this.style.background='#EDE4F5'"
                                    onmouseout="this.style.background=''">
                                    <i class="ti ti-photo" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                                </button>

                                <div class="flex gap-2">
                                    <button type="button"
                                        class="cancel-reply-btn px-3 py-1.5 text-sm rounded-xl transition"
                                        style="color:#9F86C0; border:1.5px solid #CDB4DB;"
                                        data-id="{{ $comment->id }}"
                                        onmouseover="this.style.background='#EDE4F5'"
                                        onmouseout="this.style.background=''">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-1.5 text-sm rounded-xl font-medium text-white transition"
                                        style="background:#9F86C0;"
                                        onmouseover="this.style.background='#5E4B8B'"
                                        onmouseout="this.style.background='#9F86C0'">
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
            <div class="flex-1 h-px" style="background:#CDB4DB;"></div>
            <span class="text-xs font-medium px-3 py-1 rounded-full"
                  style="background:#EDE4F5; color:#9F86C0;">
                {{ $comment->total_replies_count }} Balasan
            </span>
            <div class="flex-1 h-px" style="background:#CDB4DB;"></div>
        </div>
        @endif

        {{-- Nested Replies --}}
        <div class="space-y-3">
            @forelse($comment->replies as $reply)
            @include('partials.comment-item', [
                'comment' => $reply,
                'depth' => 0,
                'rootId' => $comment->id
            ])
            @empty
            <div class="bg-white rounded-2xl p-12 text-center"
                 style="border:1.5px solid #EDE4F5;">
                <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center"
                     style="background:#EDE4F5;">
                    <i class="ti ti-message-off" style="font-size:28px; color:#CDB4DB;" aria-hidden="true"></i>
                </div>
                <h3 class="text-base font-bold mb-1" style="color:#2D1B69;">Belum ada balasan</h3>
                <p class="text-sm" style="color:#9ca3af;">Klik tombol Balas untuk membalas diskusi ini!</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Floating Action Button --}}
    <button type="button"
        class="reply-btn fixed bottom-6 right-6 w-12 h-12 rounded-full shadow-lg flex items-center justify-center z-20 transition text-white"
        style="background:#9F86C0;"
        data-id="{{ $comment->id }}"
        onmouseover="this.style.background='#5E4B8B'"
        onmouseout="this.style.background='#9F86C0'">
        <i class="ti ti-plus" style="font-size:20px;" aria-hidden="true"></i>
    </button>
</div>

<script>
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

        // Like
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.love-btn');
            if (!btn) return;
            const id = btn.dataset.id;
            const count = btn.querySelector('.like-count');
            const icon = btn.querySelector('.like-icon');
            fetch(`/comments/${id}/like`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        count.textContent = data.likes_count ?? 0;
                        btn.style.color = data.is_liked ? '#9F86C0' : '#9ca3af';
                    }
                });
        });

        // Reply toggle
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
                    setTimeout(() => {
                        const ta = form.querySelector('textarea');
                        if (ta) { ta.focus(); form.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }
                    }, 100);
                }
            }
        });

        // Cancel reply
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.cancel-reply-btn');
            if (!btn) return;
            const id = btn.dataset.id;
            const form = document.getElementById('reply-form-' + id);
            if (form) { form.classList.add('hidden'); const ta = form.querySelector('textarea'); if (ta) ta.value = ''; }
        });

        // Toggle replies collapse
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
                icon.style.transform = 'rotate(-180deg)';
                text.textContent = `Sembunyikan ${count} balasan`;
            } else {
                icon.style.transform = 'rotate(0deg)';
                text.textContent = `Lihat ${count} balasan`;
            }
        });

        // Share
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.share-btn');
            if (!btn) return;
            const url = window.location.origin + btn.dataset.url;
            navigator.clipboard.writeText(url).then(() => {
                const span = btn.querySelector('span');
                const orig = span.textContent;
                span.textContent = 'Link disalin!';
                btn.style.color = '#9F86C0';
                setTimeout(() => { span.textContent = orig; btn.style.color = '#9ca3af'; }, 2000);
            });
        });

        // Delete
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.delete-btn');
            if (!btn) return;
            if (!confirm('Yakin ingin menghapus?')) return;
            const id = btn.dataset.id;
            fetch(`/comments/${id}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const el = document.getElementById('comment-' + id);
                        if (el) {
                            el.style.opacity = '0';
                            el.style.transform = 'scale(0.95)';
                            el.style.transition = 'all 0.3s';
                            setTimeout(() => {
                                el.remove();
                                if (id == '{{ $comment->id }}') window.location.href = '{{ route('home') }}';
                            }, 300);
                        }
                    }
                });
        });
    });
</script>

@endsection