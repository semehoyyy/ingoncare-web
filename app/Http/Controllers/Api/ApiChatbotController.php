<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatbotHistory;
use App\Models\Pet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiChatbotController extends Controller
{
    /**
     * List semua session chatbot user
     */
    public function sessions(Request $request)
    {
        $user = $request->user();

        $sessions = ChatbotHistory::where('user_id', $user->id)
            ->select('session_id')
            ->selectRaw('MIN(created_at) as started_at')
            ->selectRaw('MAX(created_at) as last_at')
            ->selectRaw('COUNT(*) as message_count')
            ->groupBy('session_id')
            ->orderByDesc('last_at')
            ->get();

        return response()->json([
            'success'  => true,
            'sessions' => $sessions,
        ]);
    }

    /**
     * Ambil history satu session
     */
    public function history(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        $history = ChatbotHistory::where('user_id', $request->user()->id)
            ->where('session_id', $request->session_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'history' => $history,
        ]);
    }

    /**
     * Buat session baru
     */
    public function newSession(Request $request)
    {
        return response()->json([
            'success'    => true,
            'session_id' => (string) Str::uuid(),
        ]);
    }

    /**
     * Kirim pesan ke chatbot
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message'    => 'required|string|max:1000',
            'session_id' => 'required|string',
        ]);

        $user      = $request->user();
        $sessionId = $request->session_id;
        $userMsg   = $request->message;

        // Simpan pesan user
        ChatbotHistory::create([
            'user_id'    => $user->id,
            'session_id' => $sessionId,
            'role'       => 'user',
            'message'    => $userMsg,
        ]);

        // Filter keyword kesehatan manusia
        $humanKeywords = [
            'saya sakit', 'sakit kepala', 'demam saya', 'batuk saya',
            'flu saya', 'asam lambung', 'darah tinggi', 'hipertensi',
            'diabetes saya', 'obat untuk saya', 'saya muntah',
            'saya pusing', 'saya mual', 'saya lemas', 'saya sesak',
            'sakit perut saya',
        ];

        foreach ($humanKeywords as $keyword) {
            if (str_contains(strtolower($userMsg), $keyword)) {
                $botMessage = 'Maaf, IngonCare hanya dapat membantu pertanyaan terkait kesehatan dan perawatan hewan. Untuk masalah kesehatan Anda sendiri, silakan konsultasikan ke dokter atau tenaga medis.';

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
        }

        // Context hewan user
        $petsContext = '';
        $pets = $user->pets ?? collect();
        if ($pets->isNotEmpty()) {
            $petsContext = "PENTING - Data hewan peliharaan milik user ini:\n";
            foreach ($pets as $pet) {
                $petsContext .= "- Nama: {$pet->name} | Jenis: {$pet->species}" .
                    ($pet->breed ? " ({$pet->breed})" : '') .
                    " | Kelamin: {$pet->gender}" .
                    ($pet->age ? " | Umur: {$pet->age}" : '') .
                    ($pet->weight ? " | Berat: {$pet->weight}kg" : '') .
                    ($pet->allergies ? " | Alergi: {$pet->allergies}" : '') .
                    ($pet->health_notes ? " | Kondisi khusus: {$pet->health_notes}" : '') .
                    "\n";
            }
        } else {
            $petsContext = "User belum mendaftarkan hewan peliharaan.\n";
        }

        // History percakapan
        $chatHistory = ChatbotHistory::where('user_id', $user->id)
            ->where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        $contents = [];
        foreach ($chatHistory as $chat) {
            $contents[] = [
                'role'  => $chat->role === 'user' ? 'user' : 'model',
                'parts' => [['text' => $chat->message]],
            ];
        }

        // System prompt
        $systemPrompt = "Kamu adalah IngonCare Veterinary Assistant. AI asisten kesehatan hewan berbasis evidence-based veterinary medicine. Kamu HANYA menjawab pertanyaan yang berkaitan dengan hewan. Gunakan bahasa Indonesia yang profesional dan ramah. Jangan menjawab pertanyaan kesehatan manusia. Jangan memberikan diagnosis pasti. Jangan menggantikan peran dokter hewan.\n\n" . $petsContext;

        try {
            $apiKey = config('services.gemini.api_key');

            if (empty($apiKey)) {
                throw new \Exception('Gemini API key belum dikonfigurasi.');
            }

            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key={$apiKey}",
                [
                    'systemInstruction' => [
                        'parts' => [['text' => $systemPrompt]],
                    ],
                    'contents' => $contents,
                ]
            );

            if ($response->successful()) {
                $botMessage = $response->json('candidates.0.content.parts.0.text')
                    ?? 'Maaf, saya tidak dapat memproses pesan Anda saat ini.';

                // Clean markdown
                $botMessage = $this->cleanMarkdown($botMessage);
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
     * Hapus session
     */
    public function deleteSession(Request $request, $sessionId)
    {
        ChatbotHistory::where('user_id', $request->user()->id)
            ->where('session_id', $sessionId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Session berhasil dihapus.',
        ]);
    }

    /**
     * Clean markdown dari response AI
     */
    private function cleanMarkdown(string $text): string
    {
        $text = preg_replace('/\*\*(.*?)\*\*/s', '$1', $text);
        $text = preg_replace('/\*(.*?)\*/s', '$1', $text);
        $text = preg_replace('/^#{1,6}\s+/m', '', $text);
        $text = preg_replace('/^---+$/m', '', $text);
        $text = preg_replace('/`(.*?)`/s', '$1', $text);
        $text = preg_replace('/\*+/', '', $text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text);
        return trim($text);
    }
}
