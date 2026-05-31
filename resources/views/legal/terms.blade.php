<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat Penggunaan - IngonCare</title>
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
                    <i class="ti ti-file-text text-white" style="font-size:26px;" aria-hidden="true"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1">Syarat Penggunaan</h1>
                    <p style="color:#EDE4F5; font-size:14px;">Terakhir diperbarui: Januari 2025</p>
                </div>
            </div>
        </div>

        {{-- INTRO --}}
        <div class="bg-white rounded-2xl p-6 mb-6" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <p style="color:#4b5563; line-height:1.7;">
                Selamat datang di <strong style="color:#5E4B8B;">IngonCare</strong>. Dengan menggunakan layanan kami,
                Anda menyetujui ketentuan-ketentuan berikut. Harap baca dengan seksama sebelum melanjutkan penggunaan aplikasi.
            </p>
        </div>

        {{-- SECTIONS --}}
        @php
        $sections = [
            ['icon' => 'ti-check-circle', 'title' => '1. Penerimaan Syarat', 'content' => 'Dengan mendaftar dan menggunakan layanan IngonCare, Anda mengakui bahwa telah membaca, memahami, dan menyetujui syarat penggunaan ini. Jika Anda tidak menyetujui, harap hentikan penggunaan layanan kami.'],
            ['icon' => 'ti-user-check', 'title' => '2. Tanggung Jawab Pengguna', 'content' => null, 'list' => ['Memberikan informasi yang akurat, lengkap, dan benar saat mendaftar.', 'Menjaga keamanan akun, termasuk kerahasiaan kata sandi.', 'Tidak menyalahgunakan fitur atau melakukan tindakan yang merugikan pengguna lain.', 'Bertanggung jawab atas seluruh aktivitas yang dilakukan melalui akun Anda.']],
            ['icon' => 'ti-ban', 'title' => '3. Aktivitas yang Dilarang', 'content' => null, 'list' => ['Meretas atau mencoba melewati sistem keamanan IngonCare.', 'Mengunggah konten berbahaya, menyesatkan, atau melanggar hak orang lain.', 'Menggunakan platform untuk tujuan ilegal atau penipuan.', 'Melakukan spam atau penyebaran informasi palsu.', 'Mengumpulkan data pengguna lain tanpa izin.']],
            ['icon' => 'ti-refresh', 'title' => '4. Pembaruan Layanan', 'content' => 'Kami berhak melakukan pembaruan fitur, perubahan tampilan, atau modifikasi syarat penggunaan sewaktu-waktu tanpa pemberitahuan sebelumnya. Kami akan berusaha menginformasikan perubahan penting kepada pengguna.'],
            ['icon' => 'ti-scale', 'title' => '5. Batasan Tanggung Jawab', 'content' => 'IngonCare menyediakan layanan "sebagaimana adanya". Kami tidak bertanggung jawab atas kerugian yang timbul akibat penggunaan atau ketidakmampuan menggunakan layanan, termasuk kehilangan data atau gangguan layanan.'],
            ['icon' => 'ti-gavel', 'title' => '6. Hukum yang Berlaku', 'content' => 'Syarat penggunaan ini diatur oleh hukum Republik Indonesia. Setiap sengketa yang timbul akan diselesaikan melalui musyawarah atau pengadilan yang berwenang di Indonesia.'],
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

        {{-- FOOTER --}}
        <div class="mt-8 text-center">
            <p class="text-sm" style="color:#9ca3af;">
                Dengan menggunakan IngonCare, Anda menyetujui syarat penggunaan ini.
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