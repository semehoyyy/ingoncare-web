{{-- resources/views/chatbot/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Chatbot Kesehatan Hewan')

@section('content')
<div class="flex gap-4 h-[calc(100vh-160px)] lg:h-[calc(100vh-200px)]">

    {{-- SIDEBAR (hidden on mobile) --}}
    <div class="hidden lg:flex w-72 flex-shrink-0 bg-white rounded-2xl flex-col overflow-hidden"
         style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">

        {{-- Sidebar Header --}}
        <div class="p-4" style="border-bottom:1.5px solid #EDE4F5;">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);">
                    <i class="ti ti-paw text-white" style="font-size:18px;" aria-hidden="true"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm" style="color:#2D1B69;">IngonCare AI</h3>
                    <p class="text-xs" style="color:#9ca3af;">Asisten Kesehatan Hewan</p>
                </div>
            </div>

            <a href="{{ route('chatbot.new-session') }}"
               class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-semibold text-white transition"
               style="background:#9F86C0;"
               onmouseover="this.style.background='#5E4B8B'"
               onmouseout="this.style.background='#9F86C0'">
                <i class="ti ti-plus" style="font-size:16px;" aria-hidden="true"></i>
                Percakapan Baru
            </a>
        </div>

        {{-- Pet Info Cards --}}
        @if(isset($pets) && $pets->isNotEmpty())
        <div class="px-3 pt-3 pb-2">
            <p class="text-[10px] font-semibold uppercase tracking-wider mb-2 px-1" style="color:#9ca3af;">
                Hewan Peliharaan Anda
            </p>
            <div class="space-y-1.5">
                @foreach($pets->take(3) as $pet)
                @php
                    $s = strtolower($pet->species ?? '');
                    if (str_contains($s, 'kucing') || str_contains($s, 'cat'))         $icon = 'ti-cat';
                    elseif (str_contains($s, 'anjing') || str_contains($s, 'dog'))     $icon = 'ti-dog';
                    elseif (str_contains($s, 'burung') || str_contains($s, 'bird'))    $icon = 'ti-feather';
                    elseif (str_contains($s, 'kelinci') || str_contains($s, 'rabbit')) $icon = 'ti-bunny';
                    elseif (str_contains($s, 'ikan') || str_contains($s, 'fish'))      $icon = 'ti-fish';
                    elseif (str_contains($s, 'hamster'))                               $icon = 'ti-hamster';
                    elseif (str_contains($s, 'kura'))                                  $icon = 'ti-turtle';
                    else                                                                $icon = 'ti-paw';
                @endphp
                <div class="flex items-center gap-2 px-3 py-2 rounded-xl"
                     style="background:#F5F0FA; border:1.5px solid #EDE4F5;">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                         style="background:#EDE4F5;">
                        <i class="ti {{ $icon }}" style="font-size:15px; color:#9F86C0;" aria-hidden="true"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold truncate" style="color:#2D1B69;">{{ $pet->name }}</p>
                        <p class="text-[10px] truncate" style="color:#9ca3af;">
                            {{ $pet->species }}{{ $pet->breed ? ', ' . $pet->breed : '' }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div style="border-top:1.5px solid #EDE4F5;" class="mt-2"></div>
        @endif

        {{-- List Sesi --}}
        <div class="flex-1 overflow-y-auto px-3 py-2 space-y-1">
            <p class="text-[10px] font-semibold uppercase tracking-wider mb-2 px-1 pt-1" style="color:#9ca3af;">
                Riwayat Percakapan
            </p>

            @forelse($sessions as $session)
            <div class="group relative">
                <a href="{{ route('chatbot.index', ['session' => $session->session_id]) }}"
                   class="block p-3 rounded-xl text-sm transition pr-8"
                   style="{{ $session->session_id === $activeSession
                       ? 'background:#EDE4F5; border:1.5px solid #CDB4DB; color:#5E4B8B;'
                       : 'border:1.5px solid transparent; color:#5E4B8B;' }}"
                   onmouseover="if('{{ $session->session_id }}' !== '{{ $activeSession }}') this.style.background='#F5F0FA'"
                   onmouseout="if('{{ $session->session_id }}' !== '{{ $activeSession }}') this.style.background=''">
                    <div class="flex items-start gap-2">
                        <i class="ti ti-message-dots flex-shrink-0 mt-0.5" style="font-size:14px; color:#CDB4DB;" aria-hidden="true"></i>
                        <div class="min-w-0 flex-1">
                            <p class="font-medium truncate text-xs" style="color:#2D1B69;">
                                {{ $session->first_message ? \Str::limit($session->first_message, 28) : 'Percakapan' }}
                            </p>
                            <p class="text-[10px] mt-0.5" style="color:#9ca3af;">
                                {{ \Carbon\Carbon::parse($session->last_at)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>

                <div class="absolute right-2 top-2 hidden group-hover:flex">
                    <button onclick="deleteSession('{{ $session->session_id }}')"
                            class="w-6 h-6 rounded-lg flex items-center justify-center transition"
                            style="background:#fef2f2; border:1px solid #fecaca;"
                            onmouseover="this.style.background='#fee2e2'"
                            onmouseout="this.style.background='#fef2f2'"
                            title="Hapus percakapan">
                        <i class="ti ti-trash" style="font-size:12px; color:#ef4444;" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            @empty
            <div class="text-center py-8">
                <div class="w-12 h-12 rounded-full mx-auto mb-2 flex items-center justify-center"
                     style="background:#EDE4F5;">
                    <i class="ti ti-message-off" style="font-size:22px; color:#CDB4DB;" aria-hidden="true"></i>
                </div>
                <p class="text-xs" style="color:#9ca3af;">Belum ada riwayat</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- CHAT AREA --}}
    <div class="flex-1 bg-white rounded-2xl flex flex-col overflow-hidden"
         style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">

        {{-- Chat Header --}}
        <div class="p-4 bg-white" style="border-bottom:1.5px solid #EDE4F5;">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center flex-shrink-0"
                         style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);">
                        <i class="ti ti-paw text-white" style="font-size:22px;" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h3 class="font-bold" style="color:#2D1B69;">IngonCare AI Assistant</h3>
                        <p class="text-xs" style="color:#9ca3af;">Asisten Kesehatan Hewan 24/7</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full"
                     style="background:#f0fdf4; border:1px solid #bbf7d0;">
                    <span class="w-2 h-2 rounded-full animate-pulse" style="background:#22c55e;"></span>
                    <span class="text-xs font-medium" style="color:#15803d;">Online</span>
                </div>
            </div>
        </div>

        {{-- Messages Area --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messagesContainer">

            @if($history->isEmpty())
            {{-- Welcome Screen --}}
            <div class="flex flex-col items-center justify-center h-full text-center px-4" id="welcomeScreen">

                <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-4"
                     style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);">
                    <i class="ti ti-paw text-white" style="font-size:38px;" aria-hidden="true"></i>
                </div>

                <h2 class="text-2xl font-bold mb-2" style="color:#2D1B69;">
                    Halo, {{ auth()->user()->name }}!
                </h2>
                <p class="text-sm max-w-sm" style="color:#9ca3af;">
                    Tanyakan apa saja tentang kesehatan dan perawatan hewan peliharaan Anda.
                </p>

                @if($pets->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-2 justify-center">
                    @foreach($pets as $pet)
                    @php
                        $s = strtolower($pet->species ?? '');
                        if (str_contains($s, 'kucing') || str_contains($s, 'cat'))         $wi = 'ti-cat';
                        elseif (str_contains($s, 'anjing') || str_contains($s, 'dog'))     $wi = 'ti-dog';
                        elseif (str_contains($s, 'burung') || str_contains($s, 'bird'))    $wi = 'ti-feather';
                        elseif (str_contains($s, 'kelinci') || str_contains($s, 'rabbit')) $wi = 'ti-bunny';
                        else                                                                $wi = 'ti-paw';
                    @endphp
                    <span class="flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium"
                          style="background:#EDE4F5; color:#5E4B8B; border:1.5px solid #CDB4DB;">
                        <i class="ti {{ $wi }}" style="font-size:14px;" aria-hidden="true"></i>
                        {{ $pet->name }}
                    </span>
                    @endforeach
                </div>
                <p class="text-xs mt-2 flex items-center gap-1" style="color:#9ca3af;">
                    <i class="ti ti-sparkles" style="font-size:13px; color:#9F86C0;" aria-hidden="true"></i>
                    AI sudah mengenal hewan peliharaan Anda
                </p>
                @endif

                {{-- Quick Suggestions --}}
                @php
                $suggestions = [
                    ['icon' => 'ti-stethoscope', 'text' => 'Apa tanda umum hewan sedang sakit?'],
                    ['icon' => 'ti-vaccine',     'text' => 'Kapan jadwal vaksinasi yang dianjurkan?'],
                ];
                if ($pets->isNotEmpty()) {
                    foreach ($pets as $pet) {
                        $sp = strtolower($pet->species ?? '');
                        if (str_contains($sp, 'kucing') || str_contains($sp, 'cat')) {
                            $suggestions[] = ['icon' => 'ti-cat',      'text' => 'Apa gejala kucing yang sakit?'];
                            $suggestions[] = ['icon' => 'ti-bowl',     'text' => 'Makanan terbaik untuk kucing?'];
                        } elseif (str_contains($sp, 'anjing') || str_contains($sp, 'dog')) {
                            $suggestions[] = ['icon' => 'ti-dog',      'text' => 'Kapan vaksinasi anjing dilakukan?'];
                            $suggestions[] = ['icon' => 'ti-run',      'text' => 'Berapa aktivitas yang dibutuhkan anjing setiap hari?'];
                        } elseif (str_contains($sp, 'burung') || str_contains($sp, 'bird')) {
                            $suggestions[] = ['icon' => 'ti-feather',  'text' => 'Apa tanda burung sedang sakit?'];
                            $suggestions[] = ['icon' => 'ti-leaf',     'text' => 'Makanan yang baik untuk burung peliharaan?'];
                        } elseif (str_contains($sp, 'kelinci') || str_contains($sp, 'rabbit')) {
                            $suggestions[] = ['icon' => 'ti-bunny',    'text' => 'Apa makanan terbaik untuk kelinci?'];
                            $suggestions[] = ['icon' => 'ti-plant',    'text' => 'Bagaimana menjaga kesehatan kelinci?'];
                        } else {
                            $suggestions[] = ['icon' => 'ti-paw',      'text' => 'Bagaimana menjaga kesehatan hewan peliharaan saya?'];
                            $suggestions[] = ['icon' => 'ti-heart',    'text' => 'Apa tanda umum hewan peliharaan sedang sakit?'];
                        }
                    }
                    $suggestions = collect($suggestions)->unique('text')->take(4)->values()->toArray();
                }
                @endphp

                <div class="grid grid-cols-2 gap-3 mt-6 w-full max-w-xl">
                    @foreach($suggestions as $card)
                    <button onclick="useSuggestion('{{ $card['text'] }}')"
                            class="text-left p-4 rounded-2xl transition"
                            style="background:#F5F0FA; border:1.5px solid #EDE4F5;"
                            onmouseover="this.style.background='#EDE4F5'; this.style.borderColor='#CDB4DB';"
                            onmouseout="this.style.background='#F5F0FA'; this.style.borderColor='#EDE4F5';">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center mb-2"
                             style="background:#EDE4F5;">
                            <i class="ti {{ $card['icon'] }}" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                        </div>
                        <p class="text-sm font-medium leading-snug" style="color:#2D1B69;">{{ $card['text'] }}</p>
                    </button>
                    @endforeach
                </div>
            </div>

            @else
            {{-- Existing History --}}
            @foreach($history as $chat)
                @if($chat->role === 'user')
                <div class="flex gap-3 justify-end">
                    <div class="flex flex-col items-end gap-1">
                        <div class="text-white rounded-2xl rounded-tr-sm px-4 py-3 max-w-[75%]"
                             style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);">
                            <p class="text-sm leading-relaxed">{{ $chat->message }}</p>
                        </div>
                        <p class="text-[10px] mr-1" style="color:#9ca3af;">
                            {{ \Carbon\Carbon::parse($chat->created_at)->format('H:i') }}
                        </p>
                    </div>
                    <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 mt-1 flex items-center justify-center"
                         style="background:#EDE4F5; border:1.5px solid #CDB4DB;">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                 class="w-full h-full object-cover">
                        @else
                            <i class="ti ti-user" style="font-size:14px; color:#9F86C0;" aria-hidden="true"></i>
                        @endif
                    </div>
                </div>

                @else
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1"
                         style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);">
                        <i class="ti ti-paw text-white" style="font-size:14px;" aria-hidden="true"></i>
                    </div>
                    <div class="flex flex-col gap-1">
                        <div class="rounded-2xl rounded-tl-sm px-4 py-3 max-w-[75%]"
                             style="background:#F5F0FA; border:1.5px solid #EDE4F5;">
                            <p class="text-sm leading-relaxed whitespace-pre-wrap" style="color:#2D1B69;">{{ $chat->message }}</p>
                        </div>
                        <p class="text-[10px] ml-1" style="color:#9ca3af;">
                            {{ \Carbon\Carbon::parse($chat->created_at)->format('H:i') }}
                        </p>
                    </div>
                </div>
                @endif
            @endforeach
            @endif

            {{-- Loading Indicator --}}
            <div id="loadingIndicator" class="hidden flex gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                     style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);">
                    <i class="ti ti-paw text-white" style="font-size:14px;" aria-hidden="true"></i>
                </div>
                <div class="rounded-2xl rounded-tl-sm px-4 py-3"
                     style="background:#F5F0FA; border:1.5px solid #EDE4F5;">
                    <div class="flex items-center gap-2">
                        <div class="flex gap-1">
                            <span class="w-2 h-2 rounded-full animate-bounce"
                                  style="background:#9F86C0; animation-delay:0ms"></span>
                            <span class="w-2 h-2 rounded-full animate-bounce"
                                  style="background:#9F86C0; animation-delay:150ms"></span>
                            <span class="w-2 h-2 rounded-full animate-bounce"
                                  style="background:#9F86C0; animation-delay:300ms"></span>
                        </div>
                        <p class="text-xs" style="color:#9F86C0;">IngonCare sedang menganalisis...</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="p-4" style="border-top:1.5px solid #EDE4F5; background:#FDFAFF;">
            <div class="relative bg-white rounded-2xl overflow-hidden transition"
                 style="border:1.5px solid #CDB4DB;"
                 onfocusin="this.style.borderColor='#9F86C0'; this.style.boxShadow='0 0 0 3px rgba(159,134,192,0.15)'"
                 onfocusout="this.style.borderColor='#CDB4DB'; this.style.boxShadow='none'">
                <textarea id="messageInput"
                          placeholder="Tanya tentang kesehatan hewan peliharaan Anda..."
                          class="w-full px-4 pt-3.5 pb-12 text-sm resize-none focus:outline-none bg-transparent"
                          style="color:#2D1B69;"
                          rows="1"
                          onkeydown="handleKeyDown(event)"
                          oninput="autoResize(this)"></textarea>
                <div class="absolute bottom-3 right-3 flex items-center gap-2">
                    <p class="text-[11px] hidden sm:block" style="color:#9ca3af;">Enter untuk kirim</p>
                    <button id="sendButton"
                            onclick="sendMessage()"
                            class="w-9 h-9 rounded-xl flex items-center justify-center transition text-white disabled:opacity-50 disabled:cursor-not-allowed"
                            style="background:#9F86C0;"
                            onmouseover="this.style.background='#5E4B8B'"
                            onmouseout="this.style.background='#9F86C0'">
                        <i class="ti ti-send" style="font-size:16px;" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <p class="text-[11px] mt-2 text-center flex items-center justify-center gap-1" style="color:#9ca3af;">
                <i class="ti ti-stethoscope" style="font-size:12px; color:#CDB4DB;" aria-hidden="true"></i>
                AI dapat membuat kesalahan. Konsultasikan masalah serius ke dokter hewan.
            </p>
        </div>

    </div>
</div>

<script>
const sessionId = '{{ $activeSession }}';
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const container = document.getElementById('messagesContainer');
const input     = document.getElementById('messageInput');
const sendBtn   = document.getElementById('sendButton');
const loadingEl = document.getElementById('loadingIndicator');

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
                <div class="text-white rounded-2xl rounded-tr-sm px-4 py-3 max-w-[75%]"
                     style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);">
                    <p class="text-sm leading-relaxed">${escapeHtml(text)}</p>
                </div>
                <p class="text-[10px] mr-1" style="color:#9ca3af;">${time}</p>
            </div>
            <div class="w-8 h-8 rounded-full flex-shrink-0 mt-1 flex items-center justify-center"
                 style="background:#EDE4F5; border:1.5px solid #CDB4DB;">
                <i class="ti ti-user" style="font-size:14px; color:#9F86C0;"></i>
            </div>
        `;
    } else {
        div.innerHTML = `
            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1"
                 style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);">
                <i class="ti ti-paw text-white" style="font-size:14px;"></i>
            </div>
            <div class="flex flex-col gap-1">
                <div class="rounded-2xl rounded-tl-sm px-4 py-3 max-w-[75%]"
                     style="background:#F5F0FA; border:1.5px solid #EDE4F5;">
                    <p class="text-sm leading-relaxed whitespace-pre-wrap" style="color:#2D1B69;">${escapeHtml(text)}</p>
                </div>
                <p class="text-[10px] ml-1" style="color:#9ca3af;">${time}</p>
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