<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8F8F8] min-h-screen flex items-center justify-center px-4">

<div class="w-full max-w-xl bg-white border rounded-[30px] p-10">

    <h1 class="text-3xl font-bold text-center mb-10">
        Reset Password
    </h1>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <input type="email" name="email" placeholder="Email"
            class="w-full mb-4 p-4 bg-blue-50 rounded-xl">

        <input type="password" name="password" placeholder="New Password"
            class="w-full mb-4 p-4 bg-blue-50 rounded-xl">

        <input type="password" name="password_confirmation" placeholder="Confirm Password"
            class="w-full mb-6 p-4 bg-blue-50 rounded-xl">

        <button class="w-full bg-teal-300 text-white py-4 rounded-xl text-xl">
            Reset Password
        </button>

    </form>

</div>

</body>
</html>