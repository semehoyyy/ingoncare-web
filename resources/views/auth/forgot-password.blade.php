<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>

<body style="background:#F5F0FA; min-height:100vh;" class="flex items-center justify-center px-4">

    <div class="w-full max-w-xl bg-white rounded-3xl p-10" style="border:1.5px solid #EDE4F5; box-shadow:0 8px 32px rgba(159,134,192,0.12);">

        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background:#EDE4F5;">
                <i class="ti ti-lock-open" style="font-size:28px; color:#9F86C0;" aria-hidden="true"></i>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-center mb-2" style="color:#2D1B69;">Lupa Kata Sandi</h1>
        <p class="text-center text-sm mb-8" style="color:#9ca3af;">Masukkan email untuk menerima link reset</p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Email</label>
            <div class="relative mb-5">
                <i class="ti ti-mail absolute left-4 top-1/2 -translate-y-1/2" style="font-size:18px; color:#CDB4DB;" aria-hidden="true"></i>
                <input type="email" name="email" required placeholder="email@gmail.com"
                    class="w-full rounded-xl py-4 pl-12 pr-4 text-base focus:outline-none"
                    style="border:1.5px solid #CDB4DB; background:#FDFAFF;">
            </div>

            <button type="submit"
                class="w-full py-4 rounded-xl text-xl font-semibold text-white transition"
                style="background:#9F86C0;"
                onmouseover="this.style.background='#5E4B8B'"
                onmouseout="this.style.background='#9F86C0'">
                Kirim Reset Link
            </button>

            <a href="{{ url()->previous() }}"
               class="w-full flex items-center justify-center gap-2 mt-4 py-4 rounded-xl font-medium transition"
               style="border:1.5px solid #CDB4DB; color:#9F86C0;"
               onmouseover="this.style.background='#EDE4F5'"
               onmouseout="this.style.background=''">
                <i class="ti ti-arrow-left" style="font-size:16px;" aria-hidden="true"></i>
                Kembali
            </a>

            @if(session('status'))
                <p class="text-center mt-4 text-sm" style="color:#9F86C0;">{{ session('status') }}</p>
            @endif
            @error('email')
                <p class="text-center mt-4 text-sm text-red-500">{{ $message }}</p>
            @enderror

        </form>

    </div>

</body>
</html>