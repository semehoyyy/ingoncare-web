<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IngonCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>

<body style="background: linear-gradient(135deg, #EDE4F5 0%, #CDB4DB 100%); min-height:100vh;" class="flex flex-col items-center justify-center">

    <img src="/img/logo.png" alt="IngonCare logo" class="w-48 mb-6">

    <p class="text-center max-w-xs mb-10 text-base leading-relaxed" style="color:#5E4B8B;">
        Perawatan hewan yang lebih sederhana.<br>
        Agar mereka tetap sehat dan nyaman.
    </p>

    <a href="{{ route('login') }}"
       class="w-48 text-center py-3 font-semibold rounded-full shadow-md transition text-white mb-3"
       style="background:#9F86C0;"
       onmouseover="this.style.background='#5E4B8B'"
       onmouseout="this.style.background='#9F86C0'">
        Masuk
    </a>

    <a href="{{ route('register') }}"
       class="w-48 text-center py-3 font-semibold rounded-full shadow transition"
       style="background:white; color:#9F86C0; border:2px solid #CDB4DB;">
        Daftar
    </a>

</body>
</html>