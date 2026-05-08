<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white relative">

    <!-- LOGO KIRI ATAS -->
    <a href="{{ route('welcome') }}" class="absolute top-3 left-4 z-50">
        <img src="/img/header.png" alt="logo" class="w-20 cursor-pointer hover:scale-105 transition">
    </a>

    <!-- Header -->
    <div class="w-full bg-[#33E4DB] py-10 flex justify-center">
        <h1 class="text-white text-4xl font-bold">Masuk</h1>
    </div>

    <div class="max-w-md mx-auto mt-10 px-5">

        <!-- FORM LOGIN -->
        <form action="{{ route('proseslogin') }}" method="POST">
            @csrf

            {{-- Email atau Nomor Telepon --}}
            <label class="block mb-3">
                <span class="text-gray-800 font-semibold text-lg">Email atau Nomor Telepon</span>
                <input type="text" name="login"
                    class="w-full mt-2 p-4 bg-[#EAF7FF] rounded-xl text-gray-700 focus:outline-none"
                    placeholder="Email atau Nomor Telepon" value="{{ old('login') }}">
            </label>

            {{-- Kata Sandi --}}
            <label class="block mb-2">
                <span class="text-gray-800 font-semibold text-lg">Kata Sandi</span>
                <input type="password" name="password"
                    class="w-full p-4 bg-[#EAF7FF] rounded-xl focus:outline-none"
                    placeholder="************">
            </label>

            {{-- Pesan Error --}}
            @if(session('error'))
                <p class="text-xs text-red-600 mt-1">{{ session('error') }}</p>
            @endif

            <div class="text-right mt-1">
                <a href="{{ route('password.request') }}" class="text-[#33E4DB] text-sm">
                    Lupa Kata Sandi?
                </a>
            </div>

            {{-- Tombol Masuk --}}
            <button type="submit"
                class="w-full mt-5 py-3 rounded-full bg-[#33E4DB] text-white text-lg font-semibold">
                Masuk
            </button>
        </form>

        {{-- Daftar --}}
        <p class="text-center text-gray-600 mt-6 mb-10">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-[#33E4DB] font-semibold">Daftar</a>
        </p>

    </div>
</body>
</html>
