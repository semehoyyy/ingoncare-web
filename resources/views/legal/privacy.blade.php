<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebijakan Privasi - IngonCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>
<body style="background:#F5F0FA; min-height:100vh;">

    {{-- HEADER --}}
    <header style="background:white; border-bottom:1.5px solid #EDE4F5; box-shadow:0 2px 8px rgba(159,134,192,0.08);" class="sticky top-0 z-10">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center gap-4">
            <a href="{{ url()->previous() }}"
               class="w-9 h-9 rounded-full flex items-center justify-center transition"
               style="background:#EDE4F5;"
               onmouseover="this.style.background='#CDB4DB'"
               onmouseout="this.style.background='#EDE4F5'">
                <i class="ti ti-arrow-left" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
            </a>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#EDE4F5;">
                    <i class="ti ti-paw" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                </div>
                <span class="font-bold" style="color:#2D1B69;">IngonCare</span>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-6 py-10">

        {{-- HERO --}}
        <div class="rounded-2xl p-8 mb-8 text-white"
             style="background:linear-gradient(135deg,#2D1B69 0%,#9F86C0 100%);">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
                     style="background:rgba(255,255,255,0.2);">
                    <i class="ti ti-shield-lock text-white" style="font-size:26px;" aria-hidden="true"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1">Kebijakan Privasi</h1>
                    <p style="color:#EDE4F5; font-size:14px;">Terakhir diperbarui: Januari 2025</p>
                </div>
            </div>
        </div>

        {{-- INTRO --}}
        <div class="bg-white rounded-2xl p-6 mb-6" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <p style="color:#4b5563; line-height:1.7;">
                <strong style="color:#5E4B8B;">IngonCare</strong> berkomitmen untuk melindungi privasi dan data pribadi Anda.
                Kebijakan ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda saat menggunakan layanan kami.
            </p>
        </div>

        {{-- SECTIONS --}}
        @php
        $sections = [
            ['icon' => 'ti-database', 'title' => '1. Data yang Kami Kumpulkan', 'list' => [
                'Informasi akun: nama, alamat email, dan nomor telepon.',
                'Data login: alamat IP, jenis perangkat, dan waktu akses.',
                'Informasi hewan peliharaan yang Anda masukkan ke aplikasi.',
                'Konten yang Anda buat seperti postingan forum dan komentar.',
                'Data penggunaan aplikasi untuk meningkatkan layanan.',
            ]],
            ['icon' => 'ti-settings', 'title' => '2. Penggunaan Data', 'list' => [
                'Mengelola, memverifikasi, dan mengamankan akun pengguna.',
                'Menyediakan dan meningkatkan fitur layanan IngonCare.',
                'Mengirimkan notifikasi penting terkait akun dan layanan.',
                'Menganalisis pola penggunaan untuk pengembangan fitur baru.',
                'Memenuhi kewajiban hukum yang berlaku.',
            ]],
            ['icon' => 'ti-lock', 'title' => '3. Perlindungan Data', 'content' => 'Kami menerapkan standar keamanan industri untuk melindungi data Anda dari akses tidak sah, perubahan, pengungkapan, atau penghancuran. Sistem kami dienkripsi menggunakan teknologi terkini dan kami secara rutin mengevaluasi praktik keamanan kami.'],
            ['icon' => 'ti-share', 'title' => '4. Berbagi Data', 'content' => 'Kami tidak menjual, menyewakan, atau membagikan data pribadi Anda kepada pihak ketiga untuk tujuan komersial. Data hanya dapat dibagikan jika diwajibkan oleh hukum atau atas permintaan otoritas berwenang.'],
            ['icon' => 'ti-cookie', 'title' => '5. Cookie & Teknologi Serupa', 'content' => 'Kami menggunakan cookie dan teknologi serupa untuk meningkatkan pengalaman pengguna, menyimpan preferensi, dan menganalisis penggunaan layanan. Anda dapat mengatur preferensi cookie melalui pengaturan browser Anda.'],
            ['icon' => 'ti-user-cog', 'title' => '6. Hak Pengguna', 'list' => [
                'Mengakses, memperbarui, atau menghapus data pribadi Anda.',
                'Mengunduh salinan data yang kami simpan tentang Anda.',
                'Menolak penggunaan data untuk tujuan tertentu.',
                'Mengajukan keluhan kepada otoritas perlindungan data.',
            ]],
            ['icon' => 'ti-refresh', 'title' => '7. Perubahan Kebijakan', 'content' => 'Kebijakan privasi ini dapat diperbarui sewaktu-waktu. Perubahan signifikan akan diberitahukan melalui email atau notifikasi dalam aplikasi. Penggunaan layanan yang berkelanjutan setelah perubahan dianggap sebagai penerimaan kebijakan baru.'],
        ];
        @endphp

        <div class="space-y-4">
            @foreach($sections as $section)
            <div class="bg-white rounded-2xl overflow-hidden" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
                <div class="p-5 flex items-center gap-3" style="border-bottom:1.5px solid #EDE4F5; background:#FDFAFF;">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#EDE4F5;">
                        <i class="ti {{ $section['icon'] }}" style="font-size:18px; color:#9F86C0;" aria-hidden="true"></i>
                    </div>
                    <h2 class="font-bold text-base" style="color:#2D1B69;">{{ $section['title'] }}</h2>
                </div>
                <div class="p-5">
                    @if(isset($section['content']) && $section['content'])
                        <p style="color:#4b5563; line-height:1.7; font-size:14px;">{{ $section['content'] }}</p>
                    @endif
                    @if(isset($section['list']))
                        <ul class="space-y-2">
                            @foreach($section['list'] as $item)
                            <li class="flex items-start gap-2 text-sm" style="color:#4b5563;">
                                <i class="ti ti-point-filled flex-shrink-0 mt-0.5" style="font-size:12px; color:#9F86C0;" aria-hidden="true"></i>
                                {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- KONTAK --}}
        <div class="mt-6 p-5 rounded-2xl" style="background:#EDE4F5; border:1.5px solid #CDB4DB;">
            <div class="flex items-center gap-3">
                <i class="ti ti-mail" style="font-size:20px; color:#9F86C0;" aria-hidden="true"></i>
                <div>
                    <p class="font-semibold text-sm" style="color:#5E4B8B;">Ada pertanyaan tentang privasi Anda?</p>
                    <p class="text-sm" style="color:#9ca3af;">Hubungi kami di <span style="color:#9F86C0; font-weight:600;">privacy@ingoncare.com</span></p>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="mt-8 text-center">
            <p class="text-sm" style="color:#9ca3af;">
                Dengan menggunakan IngonCare, Anda menyetujui kebijakan privasi ini.
            </p>
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center gap-2 mt-4 px-6 py-3 rounded-xl font-semibold text-sm text-white transition"
               style="background:#9F86C0;"
               onmouseover="this.style.background='#5E4B8B'"
               onmouseout="this.style.background='#9F86C0'">
                <i class="ti ti-arrow-left" style="font-size:15px;" aria-hidden="true"></i>
                Kembali
            </a>
        </div>
    </div>

</body>
</html>