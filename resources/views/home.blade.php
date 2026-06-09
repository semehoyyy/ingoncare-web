@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Mobile Search Bar --}}
<div class="lg:hidden mb-4">
    <form action="{{ route('search') }}" method="GET" class="relative">
        <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Cari diskusi atau pengguna..."
            class="w-full pl-9 pr-4 py-2.5 rounded-full text-sm focus:outline-none focus:ring-2"
            style="background:white; border:1.5px solid #CDB4DB;">
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">

    {{-- ===================== --}}
    {{-- KOLOM POSTINGAN       --}}
    {{-- ===================== --}}
    <div class="lg:col-span-2 space-y-4 lg:space-y-6 order-1">

        {{-- Form Tambah Post --}}
        <div class="bg-white rounded-2xl overflow-hidden" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="p-5" style="border-bottom:1.5px solid #EDE4F5;">
                <div class="flex items-center gap-3">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                            class="w-11 h-11 rounded-full object-cover"
                            style="border:2px solid #CDB4DB;">
                    @else
                        <div class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0"
                            style="background:#EDE4F5; border:2px solid #CDB4DB;">
                            <i class="ti ti-user" style="font-size:18px; color:#9F86C0;" aria-hidden="true"></i>
                        </div>
                    @endif
                    <div>
                        <h3 class="font-semibold text-sm" style="color:#2D1B69;">Tambahkan diskusi</h3>
                        <p class="text-xs" style="color:#9ca3af;">Bagikan pengalamanmu dengan komunitas</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data" class="p-5">
                @csrf

                <input type="text" name="title"
                    class="w-full py-3 text-lg font-semibold focus:outline-none"
                    style="border:0; border-bottom:1.5px solid #EDE4F5; color:#2D1B69;"
                    placeholder="Judul diskusi (opsional)..."
                    maxlength="100"
                    onfocus="this.style.borderBottomColor='#9F86C0'"
                    onblur="this.style.borderBottomColor='#EDE4F5'">

                <textarea name="content"
                    class="w-full py-4 text-sm focus:outline-none resize-none"
                    style="border:0; color:#4b5563;"
                    rows="4"
                    placeholder="Apa yang ingin kamu diskusikan?"
                    required></textarea>

                <div id="imagePreviewContainer" class="hidden mt-4 relative">
                    <img id="imagePreview" src="" class="max-h-96 w-full object-cover rounded-xl" style="border:1.5px solid #EDE4F5;">
                    <button type="button" onclick="removeImage()"
                        class="absolute top-2 right-2 w-8 h-8 rounded-full flex items-center justify-center text-white"
                        style="background:#9F86C0;">
                        <i class="ti ti-x" style="font-size:14px;" aria-hidden="true"></i>
                    </button>
                </div>

                <input type="file" name="image" id="imageInput" class="hidden" accept="image/*" onchange="previewImage(event)">

                <div class="flex items-center justify-between mt-4 pt-4" style="border-top:1.5px solid #EDE4F5;">
                    <div class="flex gap-2">
                        <button type="button"
                            onclick="document.getElementById('imageInput').click()"
                            class="p-2 rounded-lg transition" aria-label="Upload gambar"
                            onmouseover="this.style.background='#EDE4F5'"
                            onmouseout="this.style.background=''">
                            <i class="ti ti-photo" style="font-size:20px; color:#9F86C0;" aria-hidden="true"></i>
                        </button>
                    </div>

                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm text-white transition"
                        style="background:#9F86C0;"
                        onmouseover="this.style.background='#5E4B8B'"
                        onmouseout="this.style.background='#9F86C0'">
                        <i class="ti ti-send" style="font-size:15px;" aria-hidden="true"></i>
                        Posting
                    </button>
                </div>
            </form>
        </div>

        {{-- LOOPING POST --}}
        @forelse($comments as $comment)
        <div class="bg-white rounded-2xl overflow-hidden transition-all"
            style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);"
            id="comment-{{ $comment->id }}"
            onmouseover="this.style.boxShadow='0 4px 20px rgba(159,134,192,0.16)'"
            onmouseout="this.style.boxShadow='0 2px 12px rgba(159,134,192,0.08)'">

            {{-- Header --}}
            <div class="p-5" style="border-bottom:1.5px solid #EDE4F5;">
                <div class="flex items-start justify-between">
                    <div class="flex gap-3">
                        <a href="{{ route('profile.show', $comment->user->id ?? 1) }}" class="flex-shrink-0">
                            @if($comment->user && $comment->user->profile_photo)
                                <img src="{{ asset('storage/' . $comment->user->profile_photo) }}"
                                    class="w-11 h-11 rounded-full object-cover"
                                    style="border:2px solid #CDB4DB;">
                            @else
                                <div class="w-11 h-11 rounded-full flex items-center justify-center"
                                    style="background:#EDE4F5; border:2px solid #CDB4DB;">
                                    <i class="ti ti-user" style="font-size:18px; color:#9F86C0;" aria-hidden="true"></i>
                                </div>
                            @endif
                        </a>
                        <div>
                            <a href="{{ route('profile.show', $comment->user->id ?? 1) }}"
                                class="font-bold text-sm hover:underline" style="color:#2D1B69;">
                                {{ $comment->user->name ?? 'Deleted User' }}
                            </a>
                            <p class="text-xs mt-0.5" style="color:#9ca3af;">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if(auth()->id() === $comment->user_id)
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

            {{-- BODY --}}
            <div class="cursor-pointer transition post-body px-5 py-4"
                data-url="{{ route('forum.show', $comment->id) }}"
                onmouseover="this.style.background='#FDFAFF'"
                onmouseout="this.style.background=''">

                @if($comment->title)
                    <h3 class="text-lg font-bold mb-2" style="color:#2D1B69;">{{ $comment->title }}</h3>
                @endif

                <p class="text-sm leading-relaxed" style="color:#4b5563;">
                    {{ Str::limit($comment->content, 200) }}
                </p>

                @if($comment->image)
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $comment->image) }}"
                        alt="Post image"
                        class="w-full max-h-96 rounded-xl object-cover"
                        style="border:1.5px solid #EDE4F5;">
                </div>
                @endif
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="px-5 py-3" style="border-top:1.5px solid #EDE4F5; background:#FDFAFF;">
                <div class="flex items-center gap-5">

                    {{-- LIKE --}}
                    @php $isLiked = $comment->likes->contains('id', auth()->id()); @endphp
                    <button type="button"
                        class="love-btn flex items-center gap-1.5 text-sm transition"
                        style="color: {{ $isLiked ? '#9F86C0' : '#9ca3af' }};"
                        data-id="{{ $comment->id }}"
                        onclick="event.stopPropagation()">
                        <i class="ti ti-heart like-icon" style="font-size:18px;" aria-hidden="true"></i>
                        <span class="like-count">{{ $comment->likes->count() }}</span>
                    </button>

                    {{-- COMMENT --}}
                    <a href="{{ route('forum.show', $comment->id) }}"
                        class="flex items-center gap-1.5 text-sm transition"
                        style="color:#9ca3af;"
                        onmouseover="this.style.color='#9F86C0'"
                        onmouseout="this.style.color='#9ca3af'"
                        onclick="event.stopPropagation()">
                        <i class="ti ti-message" style="font-size:18px;" aria-hidden="true"></i>
                        <span>{{ $comment->replies->count() }}</span>
                    </a>

                    {{-- SHARE --}}
                    <button type="button"
                        class="share-btn flex items-center gap-1.5 text-sm transition ml-auto"
                        style="color:#9ca3af;"
                        data-url="{{ route('forum.show', $comment->id) }}"
                        data-title="{{ $comment->title ?? Str::limit($comment->content, 50) }}"
                        onclick="event.stopPropagation()"
                        onmouseover="this.style.color='#9F86C0'"
                        onmouseout="this.style.color='#9ca3af'">
                        <i class="ti ti-share" style="font-size:18px;" aria-hidden="true"></i>
                        <span class="share-text">Bagikan</span>
                    </button>

                </div>
            </div>

        </div>
        @empty
        <div class="bg-white rounded-2xl p-12 text-center" style="border:1.5px solid #EDE4F5;">
            <i class="ti ti-message-off" style="font-size:48px; color:#CDB4DB;" aria-hidden="true"></i>
            <h3 class="text-lg font-bold mt-4 mb-2" style="color:#2D1B69;">Belum ada diskusi</h3>
            <p style="color:#9ca3af;">Jadilah yang pertama memulai!</p>
        </div>
        @endforelse

    </div>


    {{-- ===================== --}}
    {{-- KOLOM KANAN           --}}
    {{-- ===================== --}}
    <div class="space-y-4 lg:space-y-6 order-2">

        {{-- Hewan Peliharaan --}}
        <div class="bg-white rounded-2xl overflow-hidden" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="p-4" style="background:linear-gradient(135deg, #EDE4F5, #CDB4DB); border-bottom:1.5px solid #CDB4DB;">
                <h2 class="font-bold flex items-center gap-2" style="color:#5E4B8B;">
                    <i class="ti ti-paw" style="font-size:18px;" aria-hidden="true"></i>
                    Hewan Peliharaan Saya
                </h2>
            </div>

            <div class="p-4">
                @forelse ($pets as $pet)
                <div class="flex items-center gap-3 p-3 rounded-xl mb-2 transition"
                    style="border:1.5px solid transparent;"
                    onmouseover="this.style.background='#FDFAFF'; this.style.borderColor='#EDE4F5'"
                    onmouseout="this.style.background=''; this.style.borderColor='transparent'">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center text-white text-base font-bold shadow flex-shrink-0"
                        style="background:linear-gradient(135deg, #9F86C0, #5E4B8B);">
                        {{ strtoupper(substr($pet->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm truncate" style="color:#2D1B69;">{{ $pet->name }}</p>
                        <p class="text-xs truncate" style="color:#9ca3af;">{{ $pet->species }}{{ $pet->breed ? ' · ' . $pet->breed : '' }}</p>
                    </div>
                    <i class="ti ti-chevron-right flex-shrink-0" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                </div>
                @empty
                <p class="text-center text-sm py-4" style="color:#9ca3af;">Belum ada hewan peliharaan</p>
                @endforelse

                <a href="{{ route('hewan-saya') }}"
                    class="flex items-center justify-center gap-2 w-full mt-3 py-3 rounded-xl font-semibold text-sm transition"
                    style="border:2px dashed #CDB4DB; color:#9F86C0;"
                    onmouseover="this.style.background='#EDE4F5'"
                    onmouseout="this.style.background=''">
                    <i class="ti ti-plus" style="font-size:16px;" aria-hidden="true"></i>
                    Tambah Hewan
                </a>
            </div>
        </div>

        {{-- Tren Diskusi --}}
        <div class="bg-white rounded-2xl overflow-hidden" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="p-4" style="background:linear-gradient(135deg, #EDE4F5, #CDB4DB); border-bottom:1.5px solid #CDB4DB;">
                <h2 class="font-bold flex items-center gap-2" style="color:#5E4B8B;">
                    <i class="ti ti-trending-up" style="font-size:18px;" aria-hidden="true"></i>
                    Tren Diskusi
                </h2>
            </div>

            <div class="p-4">
                @forelse($trending as $i => $topic)
                <a href="{{ route('forum.show', $topic->id) }}"
                    class="flex items-center gap-3 p-3 rounded-xl mb-2 transition"
                    style="border:1.5px solid transparent;"
                    onmouseover="this.style.background='#FDFAFF'; this.style.borderColor='#EDE4F5'"
                    onmouseout="this.style.background=''; this.style.borderColor='transparent'">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                        style="background: {{ $i === 0 ? 'linear-gradient(135deg,#f59e0b,#ef4444)' : ($i === 1 ? '#9F86C0' : '#CDB4DB') }};">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm truncate" style="color:#2D1B69;">
                            {{ $topic->title ?: Str::limit($topic->content, 30) }}
                        </p>
                        <p class="text-xs" style="color:#9ca3af;">
                            {{ $topic->replies_count ?? 0 }} komentar
                        </p>
                    </div>
                </a>
                @empty
                <p class="text-center text-sm py-4" style="color:#9ca3af;">Belum ada diskusi trending</p>
                @endforelse

                <a href="{{ route('forum.index') }}"
                    class="flex items-center justify-center gap-1 w-full mt-3 py-2 rounded-lg text-sm font-semibold transition"
                    style="color:#9F86C0;"
                    onmouseover="this.style.background='#EDE4F5'"
                    onmouseout="this.style.background=''">
                    Lihat semua
                    <i class="ti ti-arrow-right" style="font-size:14px;" aria-hidden="true"></i>
                </a>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
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

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.querySelectorAll('.post-body').forEach(el => {
        el.addEventListener('click', function() {
            window.location.href = this.dataset.url;
        });
    });

    document.querySelectorAll('.love-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            const id = this.dataset.id;
            const countEl = this.querySelector('.like-count');
            const iconEl = this.querySelector('.like-icon');
            const button = this;
            button.style.pointerEvents = 'none';
            try {
                const res = await fetch(`/comments/${id}/like`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    countEl.textContent = data.likes_count;
                    button.style.color = data.is_liked ? '#9F86C0' : '#9ca3af';
                }
            } catch (err) {
                console.error(err);
            } finally {
                button.style.pointerEvents = 'auto';
            }
        });
    });

    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            const url = window.location.origin + this.dataset.url;
            const textEl = this.querySelector('.share-text');
            try {
                await navigator.clipboard.writeText(url);
                textEl.textContent = 'Tersalin!';
                this.style.color = '#9F86C0';
                setTimeout(() => { textEl.textContent = 'Bagikan'; this.style.color = '#9ca3af'; }, 2000);
            } catch (err) { console.error(err); }
        });
    });

    document.querySelectorAll('.delete-post-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (!confirm('Yakin ingin menghapus diskusi ini?')) return;
            const id = this.dataset.id;
            try {
                const res = await fetch(`/comments/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    const el = document.getElementById(`comment-${id}`);
                    el.style.opacity = '0';
                    el.style.transform = 'translateX(-10px)';
                    el.style.transition = 'all 0.3s';
                    setTimeout(() => el.remove(), 300);
                }
            } catch (err) { console.error(err); }
        });
    });
</script>
@endpush