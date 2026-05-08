<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ICON --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>

<body class="bg-[#F8F8F8] min-h-screen flex items-center justify-center px-4 relative">

    <!-- LOGO KIRI ATAS -->
    <a href="{{ route('welcome') }}" class="absolute top-4 left-4 z-50">
        <img src="/img/header.png" alt="logo"
            class="w-20 cursor-pointer hover:scale-105 transition">
    </a>

    <!-- CARD -->
    <div class="w-full max-w-xl bg-white border border-gray-300 rounded-[35px] p-8 md:p-10 shadow-sm">

        <!-- ICON -->
        <div class="flex justify-center mb-8">
            <div class="w-20 h-20 bg-[#8DE7E0] rounded-3xl flex items-center justify-center">
                <i class="fa-solid fa-paw text-white text-3xl"></i>
            </div>
        </div>

        <!-- TAB -->
        <div class="flex border border-gray-300 rounded-2xl overflow-hidden mb-10">
            <a href="{{ route('login') }}"
                class="w-1/2 py-3 text-center bg-[#F2F1EC] text-black text-2xl font-medium">
                Masuk
            </a>

            <a href="{{ route('register') }}"
                class="w-1/2 py-3 text-center bg-[#F2F1EC] border-l border-gray-300 text-black text-2xl font-medium">
                Daftar
            </a>
        </div>

        <!-- TITLE -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-black mb-2">
                Selamat datang kembali
            </h1>

            <p class="text-gray-600 text-lg">
                Masuk ke akun IngonCare Anda
            </p>
        </div>

        <!-- GARIS -->
        <div class="flex items-center gap-4 mb-8">
            <div class="flex-1 h-px bg-gray-300"></div>
            <div class="flex-1 h-px bg-gray-300"></div>
        </div>

        <!-- FORM -->
        <form action="{{ route('proseslogin') }}" method="POST">
            @csrf

            <!-- EMAIL -->
            <div class="mb-5">
                <label class="block text-sm font-semibold tracking-wide text-gray-800 mb-2 uppercase">
                    Email atau Nomor Telepon
                </label>

                <div class="relative">
                    <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                    <input type="text" name="login"
                        placeholder="email@gmail.com"
                        value="{{ old('login') }}"
                        class="w-full border border-gray-300 rounded-xl py-4 pl-12 pr-4 text-lg focus:outline-none focus:ring-2 focus:ring-[#8DE7E0]">
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="mb-2">
                <label class="block text-sm font-semibold tracking-wide text-gray-800 mb-2 uppercase">
                    Kata Sandi
                </label>

                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                    <input type="password" name="password"
                        placeholder="••••••••"
                        class="w-full border border-gray-300 rounded-xl py-4 pl-12 pr-4 text-lg focus:outline-none focus:ring-2 focus:ring-[#8DE7E0]">
                </div>
            </div>

            <!-- ERROR -->
            @if(session('error'))
                <p class="text-sm text-red-500 mt-2">
                    {{ session('error') }}
                </p>
            @endif

            <!-- LUPA PASSWORD -->
            <div class="text-right mt-3">
                <a href="{{ route('password.request') }}"
                    class="text-[#8DE7E0] text-base hover:underline">
                    Lupa kata sandi?
                </a>
            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full mt-6 border border-gray-300 rounded-xl py-4 text-2xl font-medium hover:bg-[#8DE7E0] hover:text-white transition">
                Masuk
            </button>
        </form>

        <!-- REGISTER -->
        <p class="text-center text-gray-600 mt-6 text-lg">
            Belum punya akun?
            <a href="{{ route('register') }}"
                class="text-[#8DE7E0] font-semibold hover:underline">
                Daftar sekarang
            </a>
        </p>

    </div>

</body>
</html>