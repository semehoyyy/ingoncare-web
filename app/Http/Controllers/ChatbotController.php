<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Halaman utama chatbot
     */
    public function index()
    {
        $user = Auth::user();
        $pets = $user->pets ?? collect();

        // Ambil semua sesi unik user ini
        $sessions = ChatbotHistory::where('user_id', $user->id)
            ->select('session_id')
            ->selectRaw('MIN(created_at) as started_at')
            ->selectRaw('MAX(created_at) as last_at')
            ->groupBy('session_id')
            ->orderByDesc('last_at')
            ->get();

        // Session aktif = sesi terbaru atau buat baru
        $activeSession = $sessions->first()?->session_id ?? Str::uuid();

        $history = ChatbotHistory::where('user_id', $user->id)
            ->where('session_id', $activeSession)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chatbot.index', compact('pets', 'sessions', 'activeSession', 'history'));
    }

    /**
     * Kirim pesan ke chatbot (Gemini API)
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message'    => 'required|string|max:1000',
            'session_id' => 'required|string',
        ]);

        $user       = Auth::user();
        $sessionId  = $request->session_id;
        $userMsg    = $request->message;

        // Simpan pesan user
        ChatbotHistory::create([
            'user_id'    => $user->id,
            'session_id' => $sessionId,
            'role'       => 'user',
            'message'    => $userMsg,
        ]);

        // Buat context hewan user untuk prompt
        $petsContext = '';
        $pets = $user->pets ?? collect();
        if ($pets->isNotEmpty()) {
            $petsContext = "Data hewan peliharaan user:\n";
            foreach ($pets as $pet) {
                $petsContext .= "- {$pet->name} ({$pet->species}" . ($pet->breed ? ", {$pet->breed}" : '') . ", {$pet->gender}" . ($pet->age ? ", {$pet->age}" : '') . ")\n";
            }
        }

        // Ambil history percakapan untuk context
        $chatHistory = ChatbotHistory::where('user_id', $user->id)
            ->where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Build conversation untuk Gemini
        $contents = [];
        foreach ($chatHistory as $chat) {
            $contents[] = [
                'role'  => $chat->role === 'user' ? 'user' : 'model',
                'parts' => [['text' => $chat->message]],
            ];
        }

        // System prompt
        $systemPrompt = "Kamu adalah IngonCare Assistant, asisten kesehatan hewan peliharaan yang ramah dan berpengetahuan. " .
            "Kamu membantu pemilik hewan dengan pertanyaan seputar kesehatan, nutrisi, vaksinasi, grooming, dan perawatan hewan peliharaan. " .
            "Berikan jawaban yang informatif namun mudah dipahami. Selalu sarankan konsultasi dokter hewan untuk masalah kesehatan serius. " .
            "Gunakan bahasa Indonesia yang ramah dan mudah dimengerti.\n\n" .
            ($petsContext ? $petsContext : "User belum mendaftarkan hewan peliharaan.");

        // Tambahkan system prompt sebagai pesan pertama jika belum ada
        if (empty($contents) || $contents[0]['role'] !== 'user') {
            array_unshift($contents, [
                'role'  => 'user',
                'parts' => [['text' => $systemPrompt]],
            ]);
            array_splice($contents, 1, 0, [[
                'role'  => 'model',
                'parts' => [['text' => 'Halo! Saya IngonCare Assistant. Saya siap membantu pertanyaan seputar kesehatan dan perawatan hewan peliharaan Anda. Ada yang bisa saya bantu?']],
            ]]);
        }

        try {
            $apiKey = config('services.gemini.api_key');

            if (empty($apiKey)) {
                throw new \Exception('Gemini API key belum dikonfigurasi.');
            }

            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}",
                ['contents' => $contents]
            );

            if ($response->successful()) {
                $botMessage = $response->json('candidates.0.content.parts.0.text')
                    ?? 'Maaf, saya tidak dapat memproses pesan Anda saat ini.';
            } else {
                Log::error('Gemini API error', ['status' => $response->status(), 'body' => $response->body()]);
                $botMessage = 'Maaf, terjadi kesalahan saat menghubungi AI. Silakan coba lagi.';
            }
        } catch (\Exception $e) {
            Log::error('Gemini exception', ['message' => $e->getMessage()]);
            $botMessage = 'Maaf, layanan chatbot sedang tidak tersedia. Silakan coba beberapa saat lagi.';
        }

        // Simpan balasan bot
        ChatbotHistory::create([
            'user_id'    => $user->id,
            'session_id' => $sessionId,
            'role'       => 'bot',
            'message'    => $botMessage,
        ]);

        return response()->json([
            'success' => true,
            'message' => $botMessage,
        ]);
    }

    /**
     * Ambil riwayat percakapan satu sesi
     */
    public function history(Request $request)
    {
        $sessionId = $request->get('session_id');
        $user      = Auth::user();

        $history = ChatbotHistory::where('user_id', $user->id)
            ->where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'history' => $history,
        ]);
    }

    /**
     * Buat sesi baru
     */
    public function newSession()
    {
        return redirect()->route('chatbot.index', ['session' => Str::uuid()]);
    }
}