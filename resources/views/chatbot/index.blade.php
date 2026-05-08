{{-- resources/views/chatbot/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Chatbot Kesehatan Hewan')

@section('content')
<div class="flex gap-4 h-[calc(100vh-200px)]">

    {{-- Sidebar Riwayat Sesi --}}
    <div class="w-64 flex-shrink-0 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-cyan-500 to-blue-600 text-white">
            <h3 class="font-bold">🤖 IngonCare AI</h3>
            <p class="text-xs text-cyan-100">Asisten Kesehatan Hewan</p>
        </div>

        <div class="p-3">
            <a href="{{ route('chatbot.new-session') }}"
                class="block w-full text-center py-2 bg-cyan-500 text-white rounded-xl text-sm font-medium hover:bg-cyan-600 transition mb-3">
                + Percakapan Baru
            </a>
        </div>

        <div class="flex-1 overflow-y-auto px-3 pb-3 space-y-2">
            @forelse($sessions as $session)
                <a href="{{ route('chatbot.index', ['session' => $session->session_id]) }}"
                    class="block p-3 rounded-xl text-sm transition
                    {{ $session->session_id === $activeSession ? 'bg-cyan-50 border border-cyan-200 text-cyan-700' : 'hover:bg-gray-50 text-gray-700' }}">
                    <p class="font-medium truncate">Percakapan</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($session->last_at)->format('d M Y, H:i') }}</p>
                </a>
            @empty
                <p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat</p>
            @endforelse
        </div>
    </div>

    {{-- Chat Area --}}
    <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">

        {{-- Chat Header --}}
        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-cyan-50 to-blue-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-white font-bold">
                    AI
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">IngonCare Assistant</h3>
                    <p class="text-xs text-green-500 flex items-center gap-1">
                        <span class="w-2 h-2 bg-green-400 rounded-full inline-block"></span>
                        Online
                    </p>
                </div>
            </div>
        </div>

        {{-- Messages Area --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messagesContainer">

            {{-- Pesan Selamat Datang --}}
            @if($history->isEmpty())
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    AI
                </div>
                <div class="bg-gray-100 rounded-2xl rounded-tl-sm px-4 py-3 max-w-md">
                    <p class="text-sm text-gray-800">
                        Halo! Saya <strong>IngonCare Assistant</strong> 🐾<br>
                        Saya siap membantu pertanyaan seputar kesehatan dan perawatan hewan peliharaan Anda.
                        @if($pets->isNotEmpty())
                            <br><br>Saya sudah mengetahui hewan peliharaan Anda:
                            <ul class="mt-1 space-y-0.5">
                                @foreach($pets as $pet)
                                    <li>• <strong>{{ $pet->name }}</strong> ({{ $pet->species }}{{ $pet->breed ? ', ' . $pet->breed : '' }})</li>
                                @endforeach
                            </ul>
                        @endif
                        <br><br>Ada yang bisa saya bantu?
                    </p>
                </div>
            </div>
            @endif

            {{-- Load existing history --}}
            @foreach($history as $chat)
                @if($chat->role === 'user')
                <div class="flex gap-3 justify-end">
                    <div class="bg-cyan-500 text-white rounded-2xl rounded-tr-sm px-4 py-3 max-w-md">
                        <p class="text-sm">{{ $chat->message }}</p>
                    </div>
                    <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-300 flex items-center justify-center text-xs">👤</div>
                        @endif
                    </div>
                </div>
                @else
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        AI
                    </div>
                    <div class="bg-gray-100 rounded-2xl rounded-tl-sm px-4 py-3 max-w-md">
                        <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $chat->message }}</p>
                    </div>
                </div>
                @endif
            @endforeach

            {{-- Loading indicator --}}
            <div id="loadingIndicator" class="hidden flex gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    AI
                </div>
                <div class="bg-gray-100 rounded-2xl rounded-tl-sm px-4 py-3">
                    <div class="flex gap-1">
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Suggested Questions --}}
        @if($history->isEmpty())
        <div class="px-4 pb-3 flex flex-wrap gap-2">
            @foreach([
                'Apa gejala kucing yang sakit?',
                'Kapan harus vaksinasi anjing?',
                'Makanan apa yang berbahaya untuk kucing?',
                'Bagaimana cara grooming anjing di rumah?',
            ] as $suggestion)
            <button onclick="useSuggestion('{{ $suggestion }}')"
                class="text-xs px-3 py-1.5 bg-cyan-50 text-cyan-700 border border-cyan-200 rounded-full hover:bg-cyan-100 transition">
                {{ $suggestion }}
            </button>
            @endforeach
        </div>
        @endif

        {{-- Input Area --}}
        <div class="p-4 border-t border-gray-100">
            <div class="flex gap-3 items-end">
                <textarea
                    id="messageInput"
                    placeholder="Tanya tentang kesehatan hewan peliharaan Anda..."
                    class="flex-1 border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent resize-none"
                    rows="1"
                    onkeydown="handleKeyDown(event)"
                    oninput="autoResize(this)"></textarea>
                <button
                    id="sendButton"
                    onclick="sendMessage()"
                    class="w-11 h-11 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl flex items-center justify-center transition flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>
            <p class="text-xs text-gray-400 mt-2 text-center">
                AI dapat membuat kesalahan. Konsultasikan masalah kesehatan serius ke dokter hewan.
            </p>
        </div>

    </div>
</div>

<script>
const sessionId   = '{{ $activeSession }}';
const csrfToken   = document.querySelector('meta[name="csrf-token"]').content;
const container   = document.getElementById('messagesContainer');
const input       = document.getElementById('messageInput');
const sendBtn     = document.getElementById('sendButton');
const loadingEl   = document.getElementById('loadingIndicator');

// Scroll ke bawah saat load
window.addEventListener('load', () => scrollToBottom());

function scrollToBottom() {
    container.scrollTop = container.scrollHeight;
}

function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

function handleKeyDown(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}

function useSuggestion(text) {
    input.value = text;
    input.focus();
    autoResize(input);
}

function appendMessage(role, text) {
    const isUser = role === 'user';
    const div = document.createElement('div');
    div.className = 'flex gap-3' + (isUser ? ' justify-end' : '');

    if (isUser) {
        div.innerHTML = `
            <div class="bg-cyan-500 text-white rounded-2xl rounded-tr-sm px-4 py-3 max-w-md">
                <p class="text-sm">${escapeHtml(text)}</p>
            </div>
            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-xs flex-shrink-0">👤</div>
        `;
    } else {
        div.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">AI</div>
            <div class="bg-gray-100 rounded-2xl rounded-tl-sm px-4 py-3 max-w-md">
                <p class="text-sm text-gray-800 whitespace-pre-wrap">${escapeHtml(text)}</p>
            </div>
        `;
    }

    container.insertBefore(div, loadingEl);
    scrollToBottom();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}

async function sendMessage() {
    const msg = input.value.trim();
    if (!msg) return;

    // Tampilkan pesan user
    appendMessage('user', msg);
    input.value = '';
    input.style.height = 'auto';
    sendBtn.disabled = true;

    // Tampilkan loading
    loadingEl.classList.remove('hidden');
    scrollToBottom();

    try {
        const res = await fetch('{{ route("chatbot.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: msg, session_id: sessionId }),
        });

        const data = await res.json();
        loadingEl.classList.add('hidden');

        if (data.success) {
            appendMessage('bot', data.message);
        } else {
            appendMessage('bot', 'Maaf, terjadi kesalahan. Silakan coba lagi.');
        }
    } catch (err) {
        loadingEl.classList.add('hidden');
        appendMessage('bot', 'Gagal menghubungi server. Periksa koneksi Anda.');
    } finally {
        sendBtn.disabled = false;
    }
}
</script>
@endsection