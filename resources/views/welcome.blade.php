<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IngonCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white flex flex-col items-center justify-center min-h-screen">

    <!-- Logo -->
    <img src="/img/logo.png" alt="logo" class="w-48 mb-6">

    <!-- Deskripsi -->
    <p class="text-center text-gray-700 max-w-xs mb-10">
        Perawatan hewan yang lebih sederhana.<br>
        Agar mereka tetap sehat dan nyaman.
    </p>

    <!-- Tombol Login -->
    <a href="{{ route('login') }}"
       class="w-48 text-center py-3 bg-[#33E4DB] text-white font-semibold rounded-full shadow-md hover:opacity-90">
        Masuk
    </a>

    <!-- Tombol Sign Up -->
    <a href="{{ route('register') }}"
       class="w-48 text-center py-3 mt-4 bg-[#EAF7FF] text-[#33E4DB] font-semibold rounded-full shadow">
        Daftar
    </a>

</body>
</html>
