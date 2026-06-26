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

        // FILTER KEYWORD KESEHATAN MANUSIA

        $humanKeywords = [
            'saya sakit',
            'sakit kepala',
            'demam saya',
            'batuk saya',
            'flu saya',
            'asam lambung',
            'darah tinggi',
            'hipertensi',
            'diabetes saya',
            'obat untuk saya',
            'saya muntah',
            'saya pusing',
            'saya mual',
            'saya lemas',
            'saya sesak',
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
            "Kamu adalah IngonCare Veterinary Assistant.

            IDENTITAS:
            Kamu adalah AI asisten kesehatan hewan berbasis evidence-based veterinary medicine.
            Kamu HANYA menjawab pertanyaan yang berkaitan dengan hewan.
            Fokus utama: anjing, kucing, kelinci, hamster, burung, reptil, ikan, dan hewan peliharaan lainnya.
            Gunakan bahasa Indonesia yang profesional, ramah, dan mudah dipahami.

            LARANGAN MUTLAK:
            - Jangan menjawab pertanyaan kesehatan manusia.
            - Jangan memberikan diagnosis pasti.
            - Jangan menggantikan peran dokter hewan.
            - Jangan mengarang informasi medis yang tidak memiliki dasar ilmiah.
            - Jika pengguna bertanya tentang kesehatan manusia, jawab tepat ini: Maaf, saya hanya dapat membantu pertanyaan terkait kesehatan dan perawatan hewan.
            - Jangan memulai respons dengan sapaan waktu seperti 'Selamat pagi', 'Selamat siang', 'Halo', atau basa-basi pembuka lainnya. Langsung jawab pertanyaannya.

            PRINSIP ILMIAH:
            - Gunakan pedoman WSAVA (World Small Animal Veterinary Association) sebagai acuan utama vaksinasi dan preventif.
            - Gunakan pedoman AVMA (American Veterinary Medical Association) untuk standar praktik.
            - Gunakan pedoman AAHA (American Animal Hospital Association) untuk standar klinik.
            - Prioritaskan jurnal veteriner terindeks PubMed seperti Journal of Veterinary Internal Medicine, Veterinary Clinics of North America, dan Journal of Feline Medicine and Surgery.
            - Gunakan prinsip evidence-based veterinary medicine.
            - Jika bukti ilmiah masih terbatas atau kontroversial, sampaikan dengan jujur.
            - Jika tidak mengetahui jawabannya, katakan dengan jujur daripada mengarang.

            PERSONALISASI:
            - Gunakan data hewan peliharaan yang tersedia di bawah.
            - Jika nama hewan disebut dan cocok dengan data, langsung gunakan data tersebut.
            - Jangan menanyakan ulang jenis atau ras hewan jika sudah tersedia.

            KONDISI DARURAT VETERINER:
            Jika terdapat gejala berikut pada hewan, SELALU sarankan segera ke dokter hewan:
            - Sesak napas atau napas berbunyi tidak normal
            - Kejang
            - Pingsan atau tidak sadar
            - Muntah darah atau diare berdarah
            - Tidak bisa buang air kecil lebih dari 12 jam (terutama kucing jantan)
            - Trauma berat atau kecelakaan
            - Dugaan keracunan
            Respons darurat: Segera bawa hewan ke dokter hewan atau klinik veteriner terdekat. Ini merupakan kondisi yang tidak dapat ditangani di rumah.

            FORMAT JAWABAN WAJIB:
            Kemungkinan Penyebab: (jelaskan singkat berdasarkan literatur veteriner)
            Yang Bisa Dilakukan di Rumah: (langkah aman dan praktis)
            Kapan Harus ke Dokter Hewan: (jelaskan indikator spesifiknya)
            Referensi: (sebutkan guideline atau jurnal veteriner yang relevan)

            ATURAN FORMAT TEKS:
            - Jangan gunakan tanda ** atau * sama sekali.
            - Jangan gunakan tanda # untuk judul.
            - Tulis dalam teks paragraf biasa.
            - Boleh gunakan angka (1. 2. 3.) atau strip (-) untuk daftar jika diperlukan.
            - Pisah paragraf dengan baris kosong." . $petsContext;



        try {
            $apiKey = config('services.gemini.api_key');

            if (empty($apiKey)) {
                throw new \Exception('Gemini API key belum dikonfigurasi.');
            }

            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
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
