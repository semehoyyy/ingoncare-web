{{-- resources/views/chatbot/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Chatbot Kesehatan Hewan')

@section('content')
<div class="flex gap-4 h-[calc(100vh-200px)]">

    {{-- Sidebar Riwayat Sesi --}}
    <div class="w-72 flex-shrink-0 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">

        {{-- Sidebar Header --}}
        <div class="p-4 border-b border-gray-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                    🐾
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">IngonCare AI</h3>
                    <p class="text-xs text-gray-400">Asisten Kesehatan Hewan</p>
                </div>
            </div>
            <a href="{{ route('chatbot.new-session') }}"
                class="flex items-center justify-center gap-2 w-full py-2.5 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-xl text-sm font-medium hover:from-cyan-600 hover:to-blue-600 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Percakapan Baru
            </a>
        </div>

        {{-- Pet Info Cards --}}
        @if(isset($pets) && $pets->isNotEmpty())
        <div class="px-3 pt-3 pb-2">
            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-1">Hewan Peliharaan Anda</p>
            <div class="space-y-1.5">
                @foreach($pets->take(3) as $pet)
                <div class="flex items-center gap-2 bg-cyan-50 border border-cyan-100 rounded-xl px-3 py-2">
                    <span class="text-base">
                        @if(strtolower($pet->species) === 'kucing' || strtolower($pet->species) === 'cat') 🐱
                        @elseif(strtolower($pet->species) === 'anjing' || strtolower($pet->species) === 'dog') 🐶
                        @elseif(strtolower($pet->species) === 'burung' || strtolower($pet->species) === 'bird') 🦜
                        @elseif(strtolower($pet->species) === 'kelinci' || strtolower($pet->species) === 'rabbit') 🐰
                        @else 🐾
                        @endif
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-cyan-800 truncate">{{ $pet->name }}</p>
                        <p class="text-[10px] text-cyan-600 truncate">{{ $pet->species }}{{ $pet->breed ? ', ' . $pet->breed : '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="border-t border-gray-100 mt-2"></div>
        @endif

        {{-- List Sesi --}}
        <div class="flex-1 overflow-y-auto px-3 py-2 space-y-1">
            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-1 pt-1">Riwayat Percakapan</p>
            @forelse($sessions as $session)
            <div class="group relative">
                <a href="{{ route('chatbot.index', ['session' => $session->session_id]) }}"
                    class="block p-3 rounded-xl text-sm transition pr-8
                    {{ $session->session_id === $activeSession
                        ? 'bg-cyan-50 border border-cyan-200 text-cyan-700'
                        : 'hover:bg-gray-50 text-gray-700 border border-transparent' }}">
                    <div class="flex items-start gap-2">
                        <svg class="w-3.5 h-3.5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        <div class="min-w-0 flex-1">
                            <p class="font-medium truncate text-xs">
                                {{ $session->first_message ? \Str::limit($session->first_message, 28) : 'Percakapan' }}
                            </p>
                            <p class="text-[10px] text-gray-400 mt-0.5">
                                {{ \Carbon\Carbon::parse($session->last_at)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>
                <div class="absolute right-2 top-2 hidden group-hover:flex">
                    <button
                        onclick="deleteSession('{{ $session->session_id }}')"
                        class="w-6 h-6 bg-red-50 hover:bg-red-100 text-red-400 rounded-lg flex items-center justify-center transition"
                        title="Hapus percakapan">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-6">
                <p class="text-2xl mb-1">💬</p>
                <p class="text-xs text-gray-400">Belum ada riwayat</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Chat Area --}}
    <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">

        {{-- Chat Header --}}
        <div class="p-4 border-b border-gray-100 bg-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-xl shadow-sm flex-shrink-0">
                        🐾
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">IngonCare AI Assistant</h3>
                        <p class="text-xs text-gray-500">Asisten Kesehatan Hewan 24/7</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 bg-green-50 border border-green-100 rounded-full px-3 py-1.5">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-xs text-green-600 font-medium">Online</span>
                </div>
            </div>
        </div>

        {{-- Messages Area --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messagesContainer">

            @if($history->isEmpty())
            {{-- Welcome Screen (saat belum ada chat) --}}
            <div class="flex flex-col items-center justify-center h-full text-center px-4" id="welcomeScreen">
                <div class="text-6xl mb-4 animate-bounce" style="animation-duration: 2s;">🐾</div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Halo, {{ auth()->user()->name }}! 👋
                </h2>
                <p class="text-gray-500 mt-2 max-w-sm">
                    Tanyakan apa saja tentang kesehatan dan perawatan hewan peliharaan Anda.
                </p>

                @if($pets->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-2 justify-center">
                    @foreach($pets as $pet)
                    <span class="bg-cyan-50 text-cyan-700 border border-cyan-100 rounded-full px-3 py-1 text-sm">
                        @if(strtolower($pet->species) === 'kucing' || strtolower($pet->species) === 'cat') 🐱
                        @elseif(strtolower($pet->species) === 'anjing' || strtolower($pet->species) === 'dog') 🐶
                        @elseif(strtolower($pet->species) === 'burung' || strtolower($pet->species) === 'bird') 🦜
                        @elseif(strtolower($pet->species) === 'kelinci' || strtolower($pet->species) === 'rabbit') 🐰
                        @else 🐾
                        @endif
                        {{ $pet->name }}
                    </span>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mt-2">AI sudah mengenal hewan peliharaan Anda ✨</p>
                @endif

                {{-- Quick Action Cards --}}
                @php
                $suggestions = [
                    [
                        'icon' => '⚕️',
                        'text' => 'Apa tanda umum hewan sedang sakit?',
                        'color' => 'from-cyan-50 to-blue-50 border-cyan-100 hover:border-cyan-300'
                    ],
                    [
                        'icon' => '💉',
                        'text' => 'Kapan jadwal vaksinasi yang dianjurkan?',
                        'color' => 'from-blue-50 to-indigo-50 border-blue-100 hover:border-blue-300'
                    ],
                ];

                if($pets->isNotEmpty()) {
                    foreach($pets as $pet) {
                        $species = strtolower($pet->species);

                        if(str_contains($species, 'kucing') || str_contains($species, 'cat')) {
                            $suggestions[] = [
                                'icon' => '🐱',
                                'text' => 'Apa gejala kucing yang sakit?',
                                'color' => 'from-orange-50 to-red-50 border-orange-100 hover:border-orange-300'
                            ];
                            $suggestions[] = [
                                'icon' => '🍗',
                                'text' => 'Makanan terbaik untuk kucing?',
                                'color' => 'from-yellow-50 to-orange-50 border-yellow-100 hover:border-yellow-300'
                            ];
                        } elseif(str_contains($species, 'anjing') || str_contains($species, 'dog')) {
                            $suggestions[] = [
                                'icon' => '🐶',
                                'text' => 'Kapan vaksinasi anjing dilakukan?',
                                'color' => 'from-green-50 to-emerald-50 border-green-100 hover:border-green-300'
                            ];
                            $suggestions[] = [
                                'icon' => '🏃',
                                'text' => 'Berapa aktivitas yang dibutuhkan anjing setiap hari?',
                                'color' => 'from-lime-50 to-green-50 border-lime-100 hover:border-lime-300'
                            ];
                        } elseif(str_contains($species, 'burung') || str_contains($species, 'bird')) {
                            $suggestions[] = [
                                'icon' => '🦜',
                                'text' => 'Apa tanda burung sedang sakit?',
                                'color' => 'from-sky-50 to-cyan-50 border-sky-100 hover:border-sky-300'
                            ];
                            $suggestions[] = [
                                'icon' => '🥜',
                                'text' => 'Makanan yang baik untuk burung peliharaan?',
                                'color' => 'from-blue-50 to-sky-50 border-blue-100 hover:border-blue-300'
                            ];
                        } elseif(str_contains($species, 'kelinci') || str_contains($species, 'rabbit')) {
                            $suggestions[] = [
                                'icon' => '🐰',
                                'text' => 'Apa makanan terbaik untuk kelinci?',
                                'color' => 'from-pink-50 to-rose-50 border-pink-100 hover:border-pink-300'
                            ];
                            $suggestions[] = [
                                'icon' => '🌿',
                                'text' => 'Bagaimana menjaga kesehatan kelinci?',
                                'color' => 'from-emerald-50 to-green-50 border-emerald-100 hover:border-emerald-300'
                            ];
                        } else {
                            $suggestions[] = [
                                'icon' => '🐾',
                                'text' => 'Bagaimana menjaga kesehatan hewan peliharaan saya?',
                                'color' => 'from-purple-50 to-violet-50 border-purple-100 hover:border-purple-300'
                            ];
                            $suggestions[] = [
                                'icon' => '❤️',
                                'text' => 'Apa tanda umum hewan peliharaan sedang sakit?',
                                'color' => 'from-rose-50 to-pink-50 border-rose-100 hover:border-rose-300'
                            ];
                        }
                    }
                    $suggestions = collect($suggestions)->unique('text')->take(4)->values();
                }
                @endphp

                <div class="grid grid-cols-2 gap-3 mt-8 w-full max-w-4xl">
                    @foreach($suggestions as $card)
                    <button
                        onclick="useSuggestion('{{ $card['text'] }}')"
                        class="bg-gradient-to-br {{ $card['color'] }} border rounded-2xl p-4 text-left transition group shadow-sm hover:shadow-md">
                        <span class="text-2xl block mb-2">{{ $card['icon'] }}</span>
                        <p class="text-sm text-gray-700 font-medium group-hover:text-gray-900 leading-snug">
                            {{ $card['text'] }}
                        </p>
                    </button>
                    @endforeach
                </div>
            </div>

            @else
            {{-- Load existing history --}}
            @foreach($history as $chat)
                @if($chat->role === 'user')
                <div class="flex gap-3 justify-end">
                    <div class="flex flex-col items-end gap-1">
                        <div class="bg-gradient-to-br from-cyan-500 to-blue-500 text-white rounded-2xl rounded-tr-sm px-4 py-3 max-w-[75%] shadow-sm">
                            <p class="text-sm leading-relaxed">{{ $chat->message }}</p>
                        </div>
                        <p class="text-[10px] text-gray-400 mr-1">
                            {{ \Carbon\Carbon::parse($chat->created_at)->format('H:i') }}
                        </p>
                    </div>
                    <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 mt-1">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center text-xs">👤</div>
                        @endif
                    </div>
                </div>
                @else
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-base flex-shrink-0 mt-1">
                        🐾
                    </div>
                    <div class="flex flex-col gap-1">
                        <div class="bg-gradient-to-br from-cyan-50 to-blue-50 border border-cyan-100 rounded-2xl rounded-tl-sm px-4 py-3 max-w-[75%] shadow-sm">
                            <p class="text-sm text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $chat->message }}</p>
                        </div>
                        <p class="text-[10px] text-gray-400 ml-1">
                            {{ \Carbon\Carbon::parse($chat->created_at)->format('H:i') }}
                        </p>
                    </div>
                </div>
                @endif
            @endforeach
            @endif

            {{-- Loading indicator (di luar if/else, selalu ada, dikontrol JS) --}}
            <div id="loadingIndicator" class="hidden flex gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-base flex-shrink-0">
                    🐾
                </div>
                <div class="bg-gradient-to-br from-cyan-50 to-blue-50 border border-cyan-100 rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm">
                    <div class="flex items-center gap-2">
                        <div class="flex gap-1">
                            <span class="w-2 h-2 bg-cyan-400 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                            <span class="w-2 h-2 bg-cyan-400 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                            <span class="w-2 h-2 bg-cyan-400 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                        </div>
                        <p class="text-xs text-cyan-500">IngonCare sedang menganalisis...</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            <div class="relative bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden focus-within:border-cyan-400 focus-within:ring-2 focus-within:ring-cyan-100 transition">
                <textarea
                    id="messageInput"
                    placeholder="Tanya tentang kesehatan hewan peliharaan Anda..."
                    class="w-full px-4 pt-3.5 pb-12 text-sm text-gray-800 placeholder-gray-400 resize-none focus:outline-none bg-transparent"
                    rows="1"
                    onkeydown="handleKeyDown(event)"
                    oninput="autoResize(this)"></textarea>
                <div class="absolute bottom-3 right-3 flex items-center gap-2">
                    <p class="text-[11px] text-gray-400 hidden sm:block">Enter untuk kirim</p>
                    <button
                        id="sendButton"
                        onclick="sendMessage()"
                        class="w-9 h-9 bg-gradient-to-br from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white rounded-xl flex items-center justify-center transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </div>
            </div>
            <p class="text-[11px] text-gray-400 mt-2 text-center">
                ⚕️ AI dapat membuat kesalahan. Konsultasikan masalah serius ke dokter hewan.
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

window.addEventListener('load', () => scrollToBottom());

function scrollToBottom() {
    container.scrollTop = container.scrollHeight;
}

function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 140) + 'px';
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

function getNow() {
    const now = new Date();
    return now.getHours().toString().padStart(2,'0') + ':' + now.getMinutes().toString().padStart(2,'0');
}

function appendMessage(role, text) {
    const isUser = role === 'user';
    const welcomeScreen = document.getElementById('welcomeScreen');
    if (welcomeScreen) welcomeScreen.remove();

    const time = getNow();
    const div = document.createElement('div');
    div.className = 'flex gap-3' + (isUser ? ' justify-end' : '');

    if (isUser) {
        div.innerHTML = `
            <div class="flex flex-col items-end gap-1">
                <div class="bg-gradient-to-br from-cyan-500 to-blue-500 text-white rounded-2xl rounded-tr-sm px-4 py-3 max-w-[75%] shadow-sm">
                    <p class="text-sm leading-relaxed">${escapeHtml(text)}</p>
                </div>
                <p class="text-[10px] text-gray-400 mr-1">${time}</p>
            </div>
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center text-xs flex-shrink-0 mt-1">👤</div>
        `;
    } else {
        div.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-base flex-shrink-0 mt-1">🐾</div>
            <div class="flex flex-col gap-1">
                <div class="bg-gradient-to-br from-cyan-50 to-blue-50 border border-cyan-100 rounded-2xl rounded-tl-sm px-4 py-3 max-w-[75%] shadow-sm">
                    <p class="text-sm text-gray-800 whitespace-pre-wrap leading-relaxed">${escapeHtml(text)}</p>
                </div>
                <p class="text-[10px] text-gray-400 ml-1">${time}</p>
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

    appendMessage('user', msg);
    input.value = '';
    input.style.height = 'auto';
    sendBtn.disabled = true;

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

async function deleteSession(sessionId) {
    if (!confirm('Yakin ingin menghapus percakapan ini?')) return;

    try {
        const res = await fetch(`/chatbot/session/${sessionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const data = await res.json();

        if (data.success) {
            if (sessionId === '{{ $activeSession }}') {
                window.location.href = '{{ route("chatbot.new-session") }}';
            } else {
                window.location.reload();
            }
        }
    } catch (err) {
        alert('Gagal menghapus percakapan');
    }
}
</script>
@endsection