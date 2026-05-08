{{-- resources/views/posts/show.blade.php atau post_detail.blade.php --}}
@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-6">

    {{-- POST UTAMA --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex gap-3 items-start">
            @if($post->user->profile_photo)
            <img src="{{ asset('storage/' . $post->user->profile_photo) }}"
                class="w-12 h-12 rounded-full object-cover">
            @else
            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                👤
            </div>
            @endif
            <div class="flex-1">
                <h3 class="font-bold text-lg text-gray-900">
                    {{ $post->user->name }}
                </h3>
                <p class="text-sm text-gray-500 mb-3">
                    {{ $post->created_at->diffForHumans() }}
                </p>
                <p class="text-gray-800 leading-relaxed">
                    {{ $post->content }}
                </p>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    {{-- DIVIDER --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="flex-1 h-px bg-gray-300"></div>
        <span class="text-sm text-gray-500 font-semibold px-3 py-1 bg-gray-100 rounded-full">
            {{ $post->replies->count() }} {{ $post->replies->count() == 1 ? 'Komentar' : 'Komentar' }}
        </span>
        <div class="flex-1 h-px bg-gray-300"></div>
    </div>

    {{-- FORM KOMENTAR BARU --}}
    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
        <h3 class="font-semibold text-gray-900 mb-3">Tulis Komentar</h3>
        <form action="{{ route('comments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">

            <div class="flex gap-3">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                        class="w-11 h-11 rounded-full object-cover">
                @else
                    <div class="w-11 h-11 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                        👤
                    </div>
                @endif

                <div class="flex-1">
                    <textarea name="content"
                        rows="3"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none bg-white"
                        placeholder="Tulis komentar Anda..."
                        required></textarea>
                    @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div class="text-right mt-2">
                        <button type="submit"
                            class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm hover:shadow-md">
                            Kirim Komentar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- LIST KOMENTAR (TOP LEVEL ONLY, tidak nested) --}}
    <div class="space-y-3">
        @forelse ($post->replies as $comment)
        @include('partials.comment', ['comment' => $comment])
        @empty
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <p class="text-lg font-medium">Belum ada komentar</p>
            <p class="text-sm mt-1">Jadilah yang pertama berkomentar!</p>
        </div>
        @endforelse
    </div>

</div>

{{-- JavaScript tampilan interaksi --}}
<script>
    document.addEventListener('click', function(e) {

        // === LIKE BUTTON ===
        const likeBtn = e.target.closest('.love-btn');
        if (likeBtn) {
            e.preventDefault();
            const id = likeBtn.dataset.id;
            const count = likeBtn.querySelector('.like-count');
            const token = document.querySelector('meta[name="csrf-token"]').content;

            fetch(`/comments/${id}/like`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token
                    }
                })
                .then(res => res.json())
                .then(data => {
                    count.textContent = data.likes_count ?? 0;
                    likeBtn.classList.add('text-red-500');
                    setTimeout(() => likeBtn.classList.remove('text-red-500'), 300);
                })
                .catch(err => console.error('Error:', err));

            return;
        }

        // === SHOW REPLY FORM ===
        const replyBtn = e.target.closest('.reply-btn');
        if (replyBtn) {
            e.preventDefault();
            const id = replyBtn.dataset.id;
            const form = document.getElementById("reply-form-" + id);
            form.classList.toggle("hidden");

            if (!form.classList.contains("hidden")) {
                const textarea = form.querySelector('textarea');
                textarea.focus();
            }
            return;
        }

        // === CANCEL REPLY ===
        const cancelBtn = e.target.closest('.cancel-reply-btn');
        if (cancelBtn) {
            e.preventDefault();
            const id = cancelBtn.dataset.id;
            const form = document.getElementById("reply-form-" + id);
            form.classList.add("hidden");
            form.querySelector('textarea').value = '';
            return;
        }

    });
</script>

@endsection