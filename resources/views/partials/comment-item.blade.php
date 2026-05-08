{{-- resources/views/partials/comment-item.blade.php --}}
{{-- Recursive component for nested comments with collapsible replies --}}

<div id="comment-{{ $comment->id }}"
    class="bg-white rounded-lg border border-gray-200 overflow-hidden {{ $depth > 0 ? 'ml-8 mt-3' : '' }}">

    {{-- Comment Header --}}
    <div class="p-3.5 border-b border-gray-100">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-2.5">
                <a href="{{ route('profile.show', $comment->user->id) }}" class="flex-shrink-0">
                    @if($comment->user && $comment->user->profile_photo)
                    <img src="{{ asset('storage/' . $comment->user->profile_photo) }}"
                        alt="{{ $comment->user->name }}"
                        class="w-11 h-11 rounded-full object-cover flex-shrink-0">
                    @else
                    <div class="w-11 h-11 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 flex-shrink-0">
                        👤
                    </div>
                    @endif

                </a>
                <div>
                    <a href="{{ route('profile.show', $comment->user->id) }}"
                        class="font-semibold text-gray-900 text-sm hover:text-cyan-600 transition">
                        {{ $comment->user->name }}
                    </a>
                    <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @if(auth()->check() && auth()->id() === $comment->user_id)
            <button type="button"
                class="delete-btn text-gray-400 hover:text-red-500 transition-colors"
                data-id="{{ $comment->id }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
            @endif
        </div>
    </div>

    {{-- Comment Content --}}
    <div class="p-3.5">
        @if($comment->title)
        <h3 class="font-bold text-base text-gray-900 mb-2">{{ $comment->title }}</h3>
        @endif

        <p class="text-gray-700 text-sm leading-relaxed">{{ $comment->content }}</p>

        {{-- Comment Image --}}
        @if($comment->image)
        <div class="mt-3">
            <img src="{{ asset('storage/' . $comment->image) }}"
                alt="Comment image"
                class="w-full max-h-64 rounded-lg border object-cover cursor-pointer hover:opacity-95 transition"
                onclick="window.open(this.src, '_blank')">
        </div>
        @endif
    </div>

    {{-- Comment Actions --}}
    <div class="px-3.5 py-2.5 bg-gray-50 border-t border-gray-100">
        <div class="flex items-center gap-4 text-sm">
            {{-- Like --}}
            @php
            $isLiked = $comment->likes->contains('id', auth()->id());
            @endphp
            <button type="button"
                class="love-btn flex items-center gap-1.5 transition-colors {{ $isLiked ? 'text-red-500' : 'text-gray-600 hover:text-red-500' }}"
                data-id="{{ $comment->id }}">
                <svg class="w-4 h-4 like-icon" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span class="like-count font-medium">{{ $comment->likes->count() }}</span>
            </button>

            {{-- Reply Button --}}
            <button type="button"
                class="reply-btn flex items-center gap-1.5 text-gray-600 hover:text-blue-500 transition-colors"
                data-id="{{ $comment->id }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                </svg>
                <span class="font-medium">Balas</span>
            </button>

            {{-- Toggle Replies Button (Only if has replies) --}}
            @if($comment->replies->count() > 0)
            <button type="button"
                class="toggle-replies-btn flex items-center gap-1.5 px-2 py-1 rounded-lg hover:bg-cyan-50 text-cyan-600 transition-all"
                data-id="{{ $comment->id }}">
                <svg class="w-4 h-4 reply-icon transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                <span class="font-medium text-sm reply-text">
                    Lihat {{ $comment->replies->count() }} balasan
                </span>
            </button>
            @endif

            {{-- Share --}}
            <button type="button"
                class="share-btn flex items-center gap-1.5 text-gray-600 hover:text-green-500 transition-colors ml-auto"
                data-id="{{ $comment->id }}"
                data-url="{{ route('forum.show', $rootId ?? $comment->id) }}#comment-{{ $comment->id }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Reply Form (Hidden by default) --}}
    <div class="hidden border-t border-gray-200 p-3.5 bg-gray-50" id="reply-form-{{ $comment->id }}">
        <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">

            <div class="flex gap-2">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                    class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                @else
                    <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 flex-shrink-0">
                        👤
                    </div>
                @endif
                <div class="flex-1">
                    <textarea name="content"
                        rows="2"
                        class="w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                        placeholder="Balas @{{ $comment->user->name }}..."
                        required></textarea>

                    {{-- Image Preview --}}
                    <div id="reply-image-preview-{{ $comment->id }}" class="hidden mt-2 relative">
                        <img id="reply-image-display-{{ $comment->id }}" src="" class="max-h-40 rounded-lg border">
                        <button type="button"
                            onclick="removeReplyImage({{ $comment->id }})"
                            class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-full hover:bg-red-600 transition flex items-center justify-center text-xs">
                            ✕
                        </button>
                    </div>

                    <input type="file" name="image" id="reply-image-input-{{ $comment->id }}" class="hidden" accept="image/*" onchange="previewReplyImage(event, {{ $comment->id }})">

                    <div class="flex gap-2 mt-2 justify-between items-center">
                        <button type="button"
                            onclick="document.getElementById('reply-image-input-{{ $comment->id }}').click()"
                            class="p-1.5 hover:bg-gray-200 rounded transition">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </button>

                        <div class="flex gap-2">
                            <button type="button"
                                class="cancel-reply-btn px-3 py-1 text-xs text-gray-600 hover:bg-gray-200 rounded transition-colors"
                                data-id="{{ $comment->id }}">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors font-medium">
                                Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Nested Replies Container (HIDDEN BY DEFAULT) --}}
@if($comment->replies->count() > 0)
<div id="replies-container-{{ $comment->id }}" class="hidden border-l-2 border-cyan-200 {{ $depth > 0 ? 'ml-4' : 'ml-8' }}">
    @foreach($comment->replies as $reply)
    @include('partials.comment-item', [
    'comment' => $reply,
    'depth' => ($depth ?? 0) + 1,
    'rootId' => $rootId ?? $comment->id
    ])
    @endforeach
</div>
@endif

{{-- JavaScript untuk Image Preview (hanya dimuat sekali) --}}
@once
<script>
    function previewReplyImage(event, commentId) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                const display = document.getElementById(`reply-image-display-${commentId}`);
                const preview = document.getElementById(`reply-image-preview-${commentId}`);
                if (display && preview) {
                    display.src = e.target.result;
                    preview.classList.remove('hidden');
                }
            };
            reader.readAsDataURL(file);
        }
    }

    function removeReplyImage(commentId) {
        const input = document.getElementById(`reply-image-input-${commentId}`);
        const preview = document.getElementById(`reply-image-preview-${commentId}`);
        if (input && preview) {
            input.value = '';
            preview.classList.add('hidden');
        }
    }
</script>
@endonce