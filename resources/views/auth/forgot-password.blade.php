<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">

<div class="max-w-md mx-auto mt-10 px-5">

    <h2 class="text-2xl font-bold mb-5">Forgot Password</h2>

    {{-- Success Message --}}
    @if (session('status'))
        <p class="text-green-600 text-sm mb-3">{{ session('status') }}</p>
    @endif

    {{-- Error Message --}}
    @error('email')
        <p class="text-red-500 text-sm mb-3">{{ $message }}</p>
    @enderror

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <label class="block mb-3">
            <span class="text-gray-800 font-semibold">Email</span>
            <input 
                type="email" 
                name="email"
                value="{{ old('email') }}"
                class="w-full mt-2 p-3 bg-[#EAF7FF] rounded-xl"
                placeholder="Your Email"
                required
            >
        </label>

        <button class="w-full bg-[#33E4DB] text-white py-3 rounded-xl mt-3">
            Send Reset Link
        </button>
    </form>

</div>

</body>
</html>
