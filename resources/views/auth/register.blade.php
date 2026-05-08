<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ICON --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>

<body class="bg-[#F8F8F8] min-h-screen py-10 px-4 relative overflow-x-hidden">

    <!-- LOGO -->
    <a href="{{ route('welcome') }}" class="absolute top-4 left-4 z-50">
        <img src="/img/header.png" alt="logo"
            class="w-20 cursor-pointer hover:scale-105 transition">
    </a>

    <!-- CARD -->
    <div class="w-full max-w-xl mx-auto bg-white border border-gray-300 rounded-[35px] p-8 md:p-10 shadow-sm">

        <!-- ICON -->
        <div class="flex justify-center mb-8">
            <div class="w-20 h-20 bg-[#8DE7E0] rounded-3xl flex items-center justify-center">
                <i class="fa-solid fa-paw text-white text-3xl"></i>
            </div>
        </div>

        <!-- TAB -->
        <div class="flex border border-gray-300 rounded-2xl overflow-hidden mb-10">
            <a href="{{ route('login') }}"
                class="w-1/2 py-3 text-center bg-[#F2F1EC] text-black text-2xl font-medium border-r border-gray-300">
                Masuk
            </a>

            <a href="{{ route('register') }}"
                class="w-1/2 py-3 text-center bg-[#F2F1EC] text-black text-2xl font-medium">
                Daftar
            </a>
        </div>

        <!-- TITLE -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-black mb-2">
                Buat akun baru IngonCare
            </h1>
        </div>

        <!-- GARIS -->
        <div class="flex items-center gap-4 mb-8">
            <div class="flex-1 h-px bg-gray-300"></div>
            <div class="flex-1 h-px bg-gray-300"></div>
        </div>

        <!-- FORM -->
        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <!-- NAMA -->
            <div class="mb-5">
                <label class="block text-sm font-semibold tracking-wide text-gray-800 mb-2 uppercase">
                    Nama Pengguna
                </label>

                <div class="relative">
                    <i class="fa-regular fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                    <input type="text" name="name"
                        placeholder="Nama Pengguna"
                        value="{{ old('name') }}"
                        class="w-full border border-gray-300 rounded-xl py-4 pl-12 pr-4 text-lg focus:outline-none focus:ring-2 focus:ring-[#8DE7E0]">
                </div>

                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- EMAIL -->
            <div class="mb-5">
                <label class="block text-sm font-semibold tracking-wide text-gray-800 mb-2 uppercase">
                    Email
                </label>

                <div class="relative">
                    <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                    <input type="email" name="email"
                        placeholder="email@gmail.com"
                        value="{{ old('email') }}"
                        class="w-full border border-gray-300 rounded-xl py-4 pl-12 pr-4 text-lg focus:outline-none focus:ring-2 focus:ring-[#8DE7E0]">
                </div>

                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div class="mb-5">
                <label class="block text-sm font-semibold tracking-wide text-gray-800 mb-2 uppercase">
                    Kata Sandi
                </label>

                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                    <input type="password" name="password" id="password"
                        placeholder="••••••••"
                        onfocus="showPasswordRule()"
                        onblur="hidePasswordRule()"
                        class="w-full border border-gray-300 rounded-xl py-4 pl-12 pr-4 text-lg focus:outline-none focus:ring-2 focus:ring-[#8DE7E0]">
                </div>

                <!-- RULE PASSWORD -->
                <div id="passwordRule"
                    class="hidden mt-3 p-4 bg-gray-100 rounded-xl text-sm text-gray-700">
                    <p class="font-semibold mb-2">Password harus:</p>

                    <ul class="list-disc ml-5 space-y-1">
                        <li>Minimal 8 karakter</li>
                        <li>Mengandung huruf besar (A-Z)</li>
                        <li>Mengandung angka (0-9)</li>
                        <li>Mengandung karakter spesial (@$!%*#?&)</li>
                    </ul>
                </div>

                @error('password')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- KONFIRMASI PASSWORD -->
            <div class="mb-5">
                <label class="block text-sm font-semibold tracking-wide text-gray-800 mb-2 uppercase">
                    Konfirmasi Kata Sandi
                </label>

                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                    <input type="password" name="password_confirmation"
                        placeholder="••••••••"
                        class="w-full border border-gray-300 rounded-xl py-4 pl-12 pr-4 text-lg focus:outline-none focus:ring-2 focus:ring-[#8DE7E0]">
                </div>
            </div>

            <!-- PHONE -->
            <div class="mb-5">
                <label class="block text-sm font-semibold tracking-wide text-gray-800 mb-2 uppercase">
                    Nomor Telepon
                </label>

                <div class="relative">
                    <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                    <input type="text" name="phone"
                        placeholder="08123456789"
                        value="{{ old('phone') }}"
                        class="w-full border border-gray-300 rounded-xl py-4 pl-12 pr-4 text-lg focus:outline-none focus:ring-2 focus:ring-[#8DE7E0]">
                </div>

                @error('phone')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- TERMS -->
            <p class="text-center text-gray-500 text-sm leading-relaxed mb-6">
                Dengan melanjutkan, Anda menyetujui <br>

                <button type="button"
                    onclick="openModal('termsModal')"
                    class="text-[#8DE7E0] font-semibold hover:underline">
                    Syarat Penggunaan
                </button>

                dan

                <button type="button"
                    onclick="openModal('privacyModal')"
                    class="text-[#8DE7E0] font-semibold hover:underline">
                    Kebijakan Privasi
                </button>
            </p>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full py-4 rounded-xl border border-gray-300 text-2xl font-medium hover:bg-[#8DE7E0] hover:text-white transition">
                Daftar
            </button>
        </form>

        <!-- LOGIN -->
        <p class="text-center text-gray-600 mt-6 text-lg">
            Sudah memiliki akun?

            <a href="{{ route('login') }}"
                class="text-[#8DE7E0] font-semibold hover:underline">
                Masuk
            </a>
        </p>

    </div>

    <!-- ============================= -->
    <!-- MODAL: Terms of Use -->
    <!-- ============================= -->
    <div id="termsModal"
        class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50 px-4">

        <div class="bg-white rounded-2xl p-6 w-11/12 max-w-2xl max-h-[85vh] overflow-y-auto">

            <h2 class="text-2xl font-bold mb-4 text-[#33E4DB]">
                Syarat Penggunaan
            </h2>

            <div class="text-gray-700 space-y-4">

                <p>
                    Syarat Penggunaan ini mengatur akses dan penggunaan aplikasi serta layanan kami.
                    Dengan membuat akun atau terus menggunakan platform ini, Anda menyetujui semua
                    ketentuan yang tercantum di sini.
                </p>

                <h3 class="text-xl font-semibold">
                    1. Penerimaan Syarat
                </h3>

                <p>
                    Dengan mendaftar, Anda mengakui bahwa telah membaca dan memahami syarat ini.
                    Perubahan di masa depan dapat dilakukan tanpa pemberitahuan sebelumnya,
                    dan penggunaan berkelanjutan menandakan persetujuan Anda.
                </p>

                <h3 class="text-xl font-semibold">
                    2. Tanggung Jawab Pengguna
                </h3>

                <ul class="list-disc ml-6">
                    <li>Memberikan informasi yang akurat dan benar.</li>
                    <li>Menjaga keamanan akun dan kredensial.</li>
                    <li>Tidak menyalahgunakan atau mengganggu layanan.</li>
                </ul>

                <h3 class="text-xl font-semibold">
                    3. Aktivitas yang Dilarang
                </h3>

                <ul class="list-disc ml-6">
                    <li>Meretas atau mencoba melewati sistem keamanan.</li>
                    <li>Mengunggah konten berbahaya atau berunsur malware.</li>
                    <li>Meniru identitas pengguna lain.</li>
                    <li>Menggunakan platform untuk tujuan ilegal.</li>
                </ul>

                <h3 class="text-xl font-semibold">
                    4. Penghentian Akun
                </h3>

                <p>
                    Kami berhak menangguhkan atau menghapus akun yang melanggar ketentuan ini,
                    dengan atau tanpa pemberitahuan sebelumnya.
                </p>

                <h3 class="text-xl font-semibold">
                    5. Perubahan Syarat
                </h3>

                <p>
                    Syarat penggunaan dapat diperbarui secara berkala.
                    Penggunaan layanan secara berkelanjutan berarti Anda menyetujui perubahan tersebut.
                </p>

            </div>

            <button onclick="closeModal('termsModal')"
                class="mt-6 w-full bg-[#33E4DB] text-white py-3 rounded-xl font-semibold">
                Tutup
            </button>

        </div>
    </div>

    <!-- ============================= -->
    <!-- MODAL: Privacy Policy -->
    <!-- ============================= -->
    <div id="privacyModal"
        class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50 px-4">

        <div class="bg-white rounded-2xl p-6 w-11/12 max-w-2xl max-h-[85vh] overflow-y-auto">

            <h2 class="text-2xl font-bold mb-4 text-[#33E4DB]">
                Kebijakan Privasi
            </h2>

            <div class="text-gray-700 space-y-4">

                <p>
                    Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan,
                    menyimpan, dan melindungi informasi pribadi Anda.
                    Dengan menggunakan layanan kami, Anda menyetujui kebijakan ini.
                </p>

                <h3 class="text-xl font-semibold">
                    1. Informasi yang Kami Kumpulkan
                </h3>

                <ul class="list-disc ml-6">
                    <li>Nama, email, dan nomor telepon.</li>
                    <li>Informasi login seperti alamat IP dan perangkat.</li>
                    <li>Data penggunaan untuk keperluan analitik.</li>
                </ul>

                <h3 class="text-xl font-semibold">
                    2. Penggunaan Informasi
                </h3>

                <ul class="list-disc ml-6">
                    <li>Mengelola dan memverifikasi akun pengguna.</li>
                    <li>Meningkatkan performa dan keamanan aplikasi.</li>
                    <li>Memberikan dukungan dan pengalaman pengguna yang lebih baik.</li>
                </ul>

                <h3 class="text-xl font-semibold">
                    3. Perlindungan Data
                </h3>

                <p>
                    Kami menerapkan standar keamanan industri untuk melindungi data Anda,
                    namun tidak ada sistem digital yang sepenuhnya aman.
                </p>

                <h3 class="text-xl font-semibold">
                    4. Pembagian Informasi
                </h3>

                <p>
                    Kami tidak menjual atau menyewakan data pribadi pengguna.
                    Informasi hanya dibagikan kepada pihak ketiga terpercaya untuk operasional layanan.
                </p>

                <h3 class="text-xl font-semibold">
                    5. Hak Pengguna
                </h3>

                <ul class="list-disc ml-6">
                    <li>Memperbarui atau mengoreksi data pribadi.</li>
                    <li>Meminta penghapusan data.</li>
                    <li>Menarik persetujuan pemrosesan data.</li>
                </ul>

                <h3 class="text-xl font-semibold">
                    6. Perubahan Kebijakan
                </h3>

                <p>
                    Kebijakan ini dapat diperbarui dari waktu ke waktu.
                    Penggunaan layanan secara berkelanjutan berarti Anda menyetujui kebijakan terbaru.
                </p>

            </div>

            <button onclick="closeModal('privacyModal')"
                class="mt-6 w-full bg-[#33E4DB] text-white py-3 rounded-xl font-semibold">
                Tutup
            </button>

        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function showPasswordRule() {
            document.getElementById('passwordRule').classList.remove('hidden');
        }

        function hidePasswordRule() {
            document.getElementById('passwordRule').classList.add('hidden');
        }
    </script>

</body>
</html>