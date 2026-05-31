<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>

<body style="background:#F5F0FA; min-height:100vh;" class="flex items-center justify-center px-4 relative">

    <!-- LOGO -->
    <a href="{{ route('welcome') }}" class="absolute top-4 left-4 z-50">
        <img src="/img/header.png" alt="logo" class="w-20 cursor-pointer hover:scale-105 transition">
    </a>

    <!-- CARD -->
    <div class="w-full max-w-xl bg-white rounded-3xl p-8 md:p-10" style="border:1.5px solid #EDE4F5; box-shadow:0 8px 32px rgba(159,134,192,0.12);">

        <!-- ICON -->
        <div class="flex justify-center mb-8">
            <div class="w-20 h-20 rounded-3xl flex items-center justify-center" style="background:#9F86C0;">
                <i class="ti ti-paw text-white" style="font-size:32px;" aria-hidden="true"></i>
            </div>
        </div>

        <!-- TAB -->
        <div class="flex rounded-2xl overflow-hidden mb-8" style="border:1.5px solid #EDE4F5;">
            <a href="{{ route('login') }}"
               class="w-1/2 py-3 text-center text-lg font-medium transition"
               style="background:#EDE4F5; color:#5E4B8B;">
                Masuk
            </a>
            <a href="{{ route('register') }}"
               class="w-1/2 py-3 text-center text-lg font-medium transition"
               style="color:#9ca3af; border-left:1.5px solid #EDE4F5;"
               onmouseover="this.style.background='#F5F0FA'"
               onmouseout="this.style.background=''">
                Daftar
            </a>
        </div>

        <!-- TITLE -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2" style="color:#2D1B69;">Selamat datang kembali</h1>
            <p style="color:#9ca3af;">Masuk ke akun IngonCare Anda</p>
        </div>

        <!-- FORM -->
        <form action="{{ route('proseslogin') }}" method="POST">
            @csrf

            <!-- EMAIL -->
            <div class="mb-5">
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                    Email atau Nomor Telepon
                </label>
                <div class="relative">
                    <i class="ti ti-mail absolute left-4 top-1/2 -translate-y-1/2" style="font-size:18px; color:#CDB4DB;" aria-hidden="true"></i>
                    <input type="text" name="login"
                        placeholder="email@gmail.com"
                        value="{{ old('login') }}"
                        class="w-full rounded-xl py-4 pl-12 pr-4 text-base focus:outline-none focus:ring-2"
                        style="border:1.5px solid #CDB4DB; focus-ring-color:#9F86C0; background:#FDFAFF;">
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="mb-2">
                <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                    Kata Sandi
                </label>
                <div class="relative">
                    <i class="ti ti-lock absolute left-4 top-1/2 -translate-y-1/2" style="font-size:18px; color:#CDB4DB;" aria-hidden="true"></i>
                    <input type="password" name="password"
                        placeholder="••••••••"
                        class="w-full rounded-xl py-4 pl-12 pr-4 text-base focus:outline-none focus:ring-2"
                        style="border:1.5px solid #CDB4DB; background:#FDFAFF;">
                </div>
            </div>

            @if(session('error'))
                <p class="text-sm text-red-500 mt-2">{{ session('error') }}</p>
            @endif

            <!-- LUPA PASSWORD -->
            <div class="text-right mt-3">
                <a href="{{ route('password.request') }}"
                   class="text-sm hover:underline" style="color:#9F86C0;">
                    Lupa kata sandi?
                </a>
            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full mt-6 rounded-xl py-4 text-xl font-semibold text-white transition"
                style="background:#9F86C0;"
                onmouseover="this.style.background='#5E4B8B'"
                onmouseout="this.style.background='#9F86C0'">
                Masuk
            </button>
        </form>

        <p class="text-center mt-6" style="color:#9ca3af;">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold hover:underline" style="color:#9F86C0;">
                Daftar sekarang
            </a>
        </p>

    </div>

</body>
</html>