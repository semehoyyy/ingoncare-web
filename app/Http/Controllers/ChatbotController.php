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
    public function index(Request $request)
    {
        $user = Auth::user();
        $pets = $user->pets ?? collect();

        $sessions = ChatbotHistory::where('user_id', $user->id)
            ->select('session_id')
            ->selectRaw('MIN(created_at) as started_at')
            ->selectRaw('MAX(created_at) as last_at')
            ->groupBy('session_id')
            ->orderByDesc('last_at')
            ->get();

        // Ambil session dari URL, kalau tidak ada buat baru
        $activeSession = $request->get('session')
            ?? $sessions->first()?->session_id
            ?? Str::uuid();

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

        $user      = Auth::user();
        $sessionId = $request->session_id;
        $userMsg   = $request->message;

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
            $petsContext .= "\nJika user menyebut nama hewan di atas, LANGSUNG asumsikan itu adalah hewan peliharaan mereka. " .
                "Jangan tanyakan lagi jenis hewannya karena data sudah tersedia di atas.\n";
        } else {
            $petsContext = "User belum mendaftarkan hewan peliharaan.\n";
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
        $systemPrompt =
            "Kamu adalah IngonCare Assistant, asisten kesehatan hewan peliharaan yang ramah dan berpengetahuan. " .
            "Kamu membantu pemilik hewan dengan pertanyaan seputar kesehatan, nutrisi, vaksinasi, grooming, dan perawatan hewan peliharaan. " .
            "Selalu sarankan konsultasi dokter hewan untuk masalah kesehatan serius. " .
            "Gunakan bahasa Indonesia yang ramah dan mudah dimengerti.\n\n" .

            "FORMAT WAJIB - TIDAK BOLEH DILANGGAR:\n" .
            "- JANGAN gunakan tanda ** atau * sama sekali\n" .
            "- JANGAN gunakan tanda # untuk judul\n" .
            "- Tulis dalam teks biasa saja\n" .
            "- Boleh pakai angka (1. 2. 3.) atau strip (-) untuk daftar\n" .
            "- Pisah paragraf dengan baris kosong\n\n" .

            "ATURAN PENTING TENTANG NAMA HEWAN:\n" .
            "- Jika user menyebut nama yang cocok dengan nama hewan peliharaan mereka, LANGSUNG gunakan data hewan tersebut\n" .
            "- Jangan pernah bertanya 'apakah itu kucing atau anjing?' jika datanya sudah ada\n" .
            "- Personalisasi jawaban berdasarkan data hewan yang sudah terdaftar\n\n" .

            "GUNAKAN PENGETAHUAN BERDASARKAN REFERENSI ILMIAH BERIKUT:\n\n" .

            "VAKSINASI:\n" .
            "- Vaksinasi inti anjing meliputi Distemper, Parvovirus, Adenovirus diberikan mulai usia 6-8 minggu, diulang tiap 3-4 minggu hingga usia 16 minggu (WSAVA Vaccination Guidelines, 2022).\n" .
            "- Vaksinasi inti kucing meliputi Feline Panleukopenia, Herpesvirus, Calicivirus diberikan mulai usia 6-8 minggu (WSAVA, 2022).\n" .
            "- Vaksin rabies wajib diberikan pada usia 12 minggu dan diulang setiap 1-3 tahun.\n\n" .

            "NUTRISI:\n" .
            "- Kucing dewasa membutuhkan 40-45 kkal/kg berat badan per hari dengan protein minimal 26% (NRC, 2006).\n" .
            "- Anjing dewasa membutuhkan 30-40 kkal/kg berat badan per hari dengan protein minimal 18% (AAFCO, 2021).\n" .
            "- Makanan berbahaya untuk kucing dan anjing: coklat, bawang, anggur, xylitol, alkohol (Brutlag & Flint, 2021).\n\n" .

            "PENYAKIT UMUM KUCING:\n" .
            "- Feline Upper Respiratory Infection (URI) disebabkan Herpesvirus dan Calicivirus, ditandai bersin, mata berair, hidung meler (Thiry et al., 2009).\n" .
            "- Feline Lower Urinary Tract Disease (FLUTD) lebih sering pada kucing jantan, ditandai susah buang air kecil (Bartges & Polzin, 2011).\n" .
            "- Panleukopenia kucing sangat menular, angka kematian tinggi pada anak kucing yang belum divaksin.\n\n" .

            "PENYAKIT UMUM ANJING:\n" .
            "- Canine Parvovirus menyerang anak anjing, angka kematian 91% jika tidak ditangani, gejala muntah dan diare berdarah (Goddard & Leisewitz, 2010).\n" .
            "- Canine Distemper menyerang sistem pernapasan, pencernaan, dan saraf (Martella et al., 2008).\n" .
            "- Heartworm (Dirofilaria immitis) ditularkan nyamuk, pencegahan dengan obat rutin bulanan.\n\n" .

            "GROOMING:\n" .
            "- Kucing ras panjang perlu disisir minimal 2-3 kali seminggu untuk mencegah bulu kusut (Palmeiro & Morris, 2011).\n" .
            "- Anjing perlu mandi setiap 4-6 minggu menggunakan sampo khusus hewan untuk menjaga pH kulit.\n" .
            "- Kuku hewan perlu dipotong setiap 3-4 minggu untuk mencegah infeksi.\n\n" .

            "KESEHATAN UMUM:\n" .
            "- Pemeriksaan rutin ke dokter hewan dianjurkan minimal 1-2 kali per tahun untuk hewan sehat (AVMA, 2022).\n" .
            "- Sterilisasi pada kucing dan anjing mengurangi risiko kanker reproduksi dan masalah perilaku (Spain et al., 2004).\n" .
            "- Obesitas pada hewan peliharaan meningkatkan risiko diabetes, arthritis, dan penyakit jantung (German, 2006).\n\n" .

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

                // Bersihkan markdown yang masih tersisa
                $botMessage = $this->cleanMarkdown($botMessage);

            } else {
                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'body'   => $response->body()
                ]);
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
     * Bersihkan sisa-sisa markdown dari jawaban AI
     */
    private function cleanMarkdown(string $text): string
    {
        // Hapus bold **teks**
        $text = preg_replace('/\*\*(.*?)\*\*/s', '$1', $text);

        // Hapus italic *teks*
        $text = preg_replace('/\*(.*?)\*/s', '$1', $text);

        // Hapus heading # ## ###
        $text = preg_replace('/^#{1,6}\s+/m', '', $text);

        // Hapus horizontal rule ---
        $text = preg_replace('/^---+$/m', '', $text);

        // Hapus backtick code `teks`
        $text = preg_replace('/`(.*?)`/s', '$1', $text);

        // Hapus sisa asterisk/bintang yang masih ada
        $text = preg_replace('/\*+/', '', $text);

        // Rapikan baris kosong berlebih
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        return trim($text);
    }

    /**
     * Hapus satu sesi percakapan
     */
    public function deleteSession($sessionId)
    {
        $user = Auth::user();

        ChatbotHistory::where('user_id', $user->id)
            ->where('session_id', $sessionId)
            ->delete();

        return response()->json(['success' => true]);
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
        $newSessionId = Str::uuid();
         return redirect('/chatbot?session=' . $newSessionId);
    }
}