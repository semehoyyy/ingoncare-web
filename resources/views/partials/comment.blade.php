{{-- resources/views/partials/comment.blade.php --}}
<div class="p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition"
    id="comment-{{ $comment->id }}">

    <div class="flex gap-3 items-start">

        {{-- Avatar --}}
        @if($comment->user && $comment->user->profile_photo)
        <img src="{{ asset('storage/' . $comment->user->profile_photo) }}"
            alt="{{ $comment->user->name }}"
            class="w-11 h-11 rounded-full object-cover flex-shrink-0">
        @else
        <div class="w-11 h-11 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 flex-shrink-0">
            👤
        </div>
        @endif


        {{-- Header: Name + Time + Delete Button --}}
        <div class="flex items-center justify-between gap-2 flex-wrap">
            <div class="flex items-center gap-2">
                <p class="font-semibold text-gray-900">
                    {{ optional($comment->user)->name ?? 'Deleted User' }}
                </p>
                <span class="text-gray-400">•</span>
                <p class="text-sm text-gray-500">
                    {{ $comment->created_at->diffForHumans() }}
                </p>
            </div>

            {{-- DELETE BUTTON (hanya jika user = pemilik komentar) --}}
            @if(auth()->check() && auth()->id() === $comment->user_id)
            <button type="button"
                class="delete-btn text-gray-400 hover:text-red-500 transition-colors"
                data-id="{{ $comment->id }}"
                title="Hapus komentar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
            @endif
        </div>

        {{-- Content --}}
        <div class="mt-2">
            {{-- Title (if exists) --}}
            @if(isset($comment->title) && $comment->title)
            <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $comment->title }}</h3>
            @endif

            {{-- Content Text --}}
            <p class="text-gray-800 break-words leading-relaxed">
                {{ $comment->content }}
            </p>

            {{-- Hashtags (displayed separately as badges) --}}
            @if($comment->hashtags)
            <div class="flex flex-wrap gap-2 mt-3">
                @php
                $hashtagArray = array_filter(array_map('trim', explode(',', $comment->hashtags)));
                @endphp
                @foreach($hashtagArray as $tag)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors cursor-pointer">
                    {{ $tag }}
                </span>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Actions: Like, Reply, Share --}}
        <div class="flex gap-6 text-gray-500 text-sm mt-3 items-center flex-wrap">

            {{-- LIKE BUTTON --}}
            <button type="button"
                class="love-btn flex items-center gap-1.5 hover:text-red-500 transition-colors"
                data-id="{{ $comment->id }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span class="like-count font-medium">{{ $comment->likes->count() }}</span>
            </button>

            {{-- COMMENT/REPLY COUNT --}}
            <button type="button"
                class="reply-btn flex items-center gap-1.5 hover:text-blue-500 transition-colors"
                data-id="{{ $comment->id }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span class="font-medium">{{ $comment->replies->count() }}</span>
            </button>

            {{-- VIEW REPLIES LINK (hanya jika ada balasan) --}}
            @if($comment->replies->count() > 0)
            <a href="{{ route('forum.show', $comment->id) }}"
                class="flex items-center gap-1.5 text-blue-600 hover:text-blue-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
                <span class="font-medium">Lihat {{ $comment->replies->count() }} balasan</span>
            </a>
            @endif

            {{-- SHARE BUTTON --}}
            <button type="button"
                class="share-btn flex items-center gap-1.5 hover:text-green-500 transition-colors ml-auto"
                data-id="{{ $comment->id }}"
                data-url="{{ route('forum.show', $comment->id) }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                </svg>
                <span class="font-medium">Bagikan</span>
            </button>

        </div>

        {{-- REPLY FORM (Hidden by default) --}}
        <div class="mt-3 hidden reply-form" id="reply-form-{{ $comment->id }}">
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">

                <div class="flex gap-2">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                            alt="Your avatar"
                            class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                    @else
                        <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 flex-shrink-0">
                            👤
                        </div>
                    @endif

                    <div class="flex-1">
                        <textarea name="content"
                            rows="2"
                            class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            placeholder="Tulis balasan Anda..."
                            required></textarea>
                        <div class="flex gap-2 justify-end mt-2">
                            <button type="button"
                                class="cancel-reply-btn px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                data-id="{{ $comment->id }}">
                                Batal
                            </button>
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-1.5 rounded-lg text-sm hover:bg-blue-700 transition-colors font-medium">
                                Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

</div>