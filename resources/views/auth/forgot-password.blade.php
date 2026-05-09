<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8F8F8] min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-xl bg-white border border-gray-200 rounded-[30px] p-10 shadow-sm">

        <h1 class="text-3xl font-bold text-center mb-10">
            Lupa Kata Sandi
        </h1>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <label class="block text-lg mb-2">Email</label>

            <input type="email"
                name="email"
                required
                placeholder="Your Email"
                class="w-full bg-blue-50 rounded-2xl px-5 py-4 mb-5 focus:outline-none focus:ring-2 focus:ring-teal-300">

            <button type="submit"
                class="w-full bg-teal-300 hover:bg-teal-400 text-white py-4 rounded-2xl text-xl">
                Send Reset Link
            </button>

            <!-- Tombol Kembali -->
            <a href="{{ url()->previous() }}"
                class="w-full block text-center mt-4 border border-gray-300 text-gray-600 py-4 rounded-2xl hover:bg-gray-100 transition">
                Kembali
            </a>

            @if(session('status'))
                <p class="text-green-500 text-center mt-4">
                    {{ session('status') }}
                </p>
            @endif

            @error('email')
                <p class="text-red-500 text-center mt-4">{{ $message }}</p>
            @enderror

        </form>

    </div>

</body>
</html>