<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">

<div class="max-w-md mx-auto mt-10 px-5">

    <h2 class="text-2xl font-bold mb-5">Reset Password</h2>

    {{-- Error Messages --}}
    @if (session('error'))
        <p class="text-red-500 text-sm mb-3">{{ session('error') }}</p>
    @endif

    @error('email')
        <p class="text-red-500 text-sm mb-3">{{ $message }}</p>
    @enderror

    <form action="{{ route('password.update') }}" method="POST">
        @csrf

        {{-- Hidden Token --}}
        <input type="hidden" name="token" value="{{ $token }}">

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

        <label class="block mb-3">
            <span class="text-gray-800 font-semibold">New Password</span>
            <input 
                type="password" 
                name="password"
                class="w-full mt-2 p-3 bg-[#EAF7FF] rounded-xl"
                required
            >
        </label>

        <label class="block mb-3">
            <span class="text-gray-800 font-semibold">Confirm Password</span>
            <input 
                type="password" 
                name="password_confirmation"
                class="w-full mt-2 p-3 bg-[#EAF7FF] rounded-xl"
                required
            >
        </label>

        <button class="w-full bg-[#33E4DB] text-white py-3 rounded-xl mt-3">
            Update Password
        </button>
    </form>

</div>

</body>
</html>
