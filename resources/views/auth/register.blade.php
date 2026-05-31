<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>

<body style="background:#F5F0FA; min-height:100vh;" class="py-10 px-4 relative overflow-x-hidden">

    <!-- LOGO -->
    <a href="{{ route('welcome') }}" class="absolute top-4 left-4 z-50">
        <img src="/img/header.png" alt="logo" class="w-20 cursor-pointer hover:scale-105 transition">
    </a>

    <!-- CARD -->
    <div class="w-full max-w-xl mx-auto bg-white rounded-3xl p-8 md:p-10" style="border:1.5px solid #EDE4F5; box-shadow:0 8px 32px rgba(159,134,192,0.12);">

        <!-- ICON -->
        <div class="flex justify-center mb-8">
            <div class="w-20 h-20 rounded-3xl flex items-center justify-center" style="background:#9F86C0;">
                <i class="ti ti-paw text-white" style="font-size:32px;" aria-hidden="true"></i>
            </div>
        </div>

        <!-- TAB -->
        <div class="flex rounded-2xl overflow-hidden mb-8" style="border:1.5px solid #EDE4F5;">
            <a href="{{ route('login') }}"
               class="w-1/2 py-3 text-center text-lg font-medium"
               style="color:#9ca3af; border-right:1.5px solid #EDE4F5;"
               onmouseover="this.style.background='#F5F0FA'"
               onmouseout="this.style.background=''">
                Masuk
            </a>
            <a href="{{ route('register') }}"
               class="w-1/2 py-3 text-center text-lg font-medium"
               style="background:#EDE4F5; color:#5E4B8B;">
                Daftar
            </a>
        </div>

        <!-- TITLE -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold" style="color:#2D1B69;">Buat akun baru IngonCare</h1>
        </div>

        <!-- FORM -->
        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <!-- NAMA -->
            <div class="mb-5">
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Nama Pengguna</label>
                <div class="relative">
                    <i class="ti ti-user absolute left-4 top-1/2 -translate-y-1/2" style="font-size:18px; color:#CDB4DB;" aria-hidden="true"></i>
                    <input type="text" name="name" placeholder="Nama Pengguna" value="{{ old('name') }}"
                        class="w-full rounded-xl py-4 pl-12 pr-4 text-base focus:outline-none"
                        style="border:1.5px solid #CDB4DB; background:#FDFAFF;">
                </div>
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- EMAIL -->
            <div class="mb-5">
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Email</label>
                <div class="relative">
                    <i class="ti ti-mail absolute left-4 top-1/2 -translate-y-1/2" style="font-size:18px; color:#CDB4DB;" aria-hidden="true"></i>
                    <input type="email" name="email" placeholder="email@gmail.com" value="{{ old('email') }}"
                        class="w-full rounded-xl py-4 pl-12 pr-4 text-base focus:outline-none"
                        style="border:1.5px solid #CDB4DB; background:#FDFAFF;">
                </div>
                @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- PASSWORD -->
            <div class="mb-5">
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Kata Sandi</label>
                <div class="relative">
                    <i class="ti ti-lock absolute left-4 top-1/2 -translate-y-1/2" style="font-size:18px; color:#CDB4DB;" aria-hidden="true"></i>
                    <input type="password" name="password" id="password" placeholder="••••••••"
                        onfocus="showPasswordRule()" onblur="hidePasswordRule()"
                        class="w-full rounded-xl py-4 pl-12 pr-4 text-base focus:outline-none"
                        style="border:1.5px solid #CDB4DB; background:#FDFAFF;">
                </div>
                <div id="passwordRule" class="hidden mt-3 p-4 rounded-xl text-sm" style="background:#EDE4F5; color:#5E4B8B;">
                    <p class="font-semibold mb-2">Password harus:</p>
                    <ul class="list-disc ml-5 space-y-1">
                        <li>Minimal 8 karakter</li>
                        <li>Mengandung huruf besar (A-Z)</li>
                        <li>Mengandung angka (0-9)</li>
                        <li>Mengandung karakter spesial (@$!%*#?&)</li>
                    </ul>
                </div>
                @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- KONFIRMASI PASSWORD -->
            <div class="mb-5">
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Konfirmasi Kata Sandi</label>
                <div class="relative">
                    <i class="ti ti-lock absolute left-4 top-1/2 -translate-y-1/2" style="font-size:18px; color:#CDB4DB;" aria-hidden="true"></i>
                    <input type="password" name="password_confirmation" placeholder="••••••••"
                        class="w-full rounded-xl py-4 pl-12 pr-4 text-base focus:outline-none"
                        style="border:1.5px solid #CDB4DB; background:#FDFAFF;">
                </div>
            </div>

            <!-- PHONE -->
            <div class="mb-5">
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Nomor Telepon</label>
                <div class="relative">
                    <i class="ti ti-phone absolute left-4 top-1/2 -translate-y-1/2" style="font-size:18px; color:#CDB4DB;" aria-hidden="true"></i>
                    <input type="text" name="phone" placeholder="08123456789" value="{{ old('phone') }}"
                        class="w-full rounded-xl py-4 pl-12 pr-4 text-base focus:outline-none"
                        style="border:1.5px solid #CDB4DB; background:#FDFAFF;">
                </div>
                @error('phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- TERMS -->
            <p class="text-center text-sm leading-relaxed mb-6" style="color:#9ca3af;">
                Dengan melanjutkan, Anda menyetujui
                <button type="button" onclick="openModal('termsModal')" class="font-semibold hover:underline" style="color:#9F86C0;">Syarat Penggunaan</button>
                dan
                <button type="button" onclick="openModal('privacyModal')" class="font-semibold hover:underline" style="color:#9F86C0;">Kebijakan Privasi</button>
            </p>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full py-4 rounded-xl text-xl font-semibold text-white transition"
                style="background:#9F86C0;"
                onmouseover="this.style.background='#5E4B8B'"
                onmouseout="this.style.background='#9F86C0'">
                Daftar
            </button>
        </form>

        <p class="text-center mt-6" style="color:#9ca3af;">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color:#9F86C0;">Masuk</a>
        </p>

    </div>

    <!-- MODAL TERMS -->
    <div id="termsModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50 px-4">
        <div class="bg-white rounded-2xl p-6 w-11/12 max-w-2xl max-h-[85vh] overflow-y-auto">
            <h2 class="text-2xl font-bold mb-4" style="color:#9F86C0;">Syarat Penggunaan</h2>
            <div class="text-gray-700 space-y-4">
                <p>Syarat Penggunaan ini mengatur akses dan penggunaan aplikasi serta layanan kami.</p>
                <h3 class="text-xl font-semibold" style="color:#5E4B8B;">1. Penerimaan Syarat</h3>
                <p>Dengan mendaftar, Anda mengakui bahwa telah membaca dan memahami syarat ini.</p>
                <h3 class="text-xl font-semibold" style="color:#5E4B8B;">2. Tanggung Jawab Pengguna</h3>
                <ul class="list-disc ml-6"><li>Memberikan informasi yang akurat dan benar.</li><li>Menjaga keamanan akun dan kredensial.</li><li>Tidak menyalahgunakan atau mengganggu layanan.</li></ul>
                <h3 class="text-xl font-semibold" style="color:#5E4B8B;">3. Aktivitas yang Dilarang</h3>
                <ul class="list-disc ml-6"><li>Meretas atau mencoba melewati sistem keamanan.</li><li>Mengunggah konten berbahaya.</li><li>Menggunakan platform untuk tujuan ilegal.</li></ul>
            </div>
            <button onclick="closeModal('termsModal')" class="mt-6 w-full py-3 rounded-xl font-semibold text-white" style="background:#9F86C0;">Tutup</button>
        </div>
    </div>

    <!-- MODAL PRIVACY -->
    <div id="privacyModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50 px-4">
        <div class="bg-white rounded-2xl p-6 w-11/12 max-w-2xl max-h-[85vh] overflow-y-auto">
            <h2 class="text-2xl font-bold mb-4" style="color:#9F86C0;">Kebijakan Privasi</h2>
            <div class="text-gray-700 space-y-4">
                <p>Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan dan melindungi informasi pribadi Anda.</p>
                <h3 class="text-xl font-semibold" style="color:#5E4B8B;">1. Informasi yang Kami Kumpulkan</h3>
                <ul class="list-disc ml-6"><li>Nama, email, dan nomor telepon.</li><li>Informasi login seperti alamat IP dan perangkat.</li></ul>
                <h3 class="text-xl font-semibold" style="color:#5E4B8B;">2. Penggunaan Informasi</h3>
                <ul class="list-disc ml-6"><li>Mengelola dan memverifikasi akun pengguna.</li><li>Meningkatkan performa dan keamanan aplikasi.</li></ul>
                <h3 class="text-xl font-semibold" style="color:#5E4B8B;">3. Perlindungan Data</h3>
                <p>Kami menerapkan standar keamanan industri untuk melindungi data Anda.</p>
            </div>
            <button onclick="closeModal('privacyModal')" class="mt-6 w-full py-3 rounded-xl font-semibold text-white" style="background:#9F86C0;">Tutup</button>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
        function showPasswordRule() { document.getElementById('passwordRule').classList.remove('hidden'); }
        function hidePasswordRule() { document.getElementById('passwordRule').classList.add('hidden'); }
    </script>

</body>
</html>