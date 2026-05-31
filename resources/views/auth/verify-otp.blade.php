<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>

<body style="background:#F5F0FA; min-height:100vh;" class="relative overflow-x-hidden">

    <!-- LOGO -->
    <a href="{{ route('welcome') }}" class="absolute top-4 left-4 z-50">
        <img src="/img/header.png" alt="logo" class="w-16 md:w-20">
    </a>

    <div class="flex items-center justify-center min-h-screen px-5 py-10">

        <div class="w-full max-w-sm bg-white rounded-3xl px-7 py-10" style="border:1.5px solid #EDE4F5; box-shadow:0 8px 32px rgba(159,134,192,0.12);">

            <!-- BACK -->
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-sm font-semibold hover:opacity-80 transition" style="color:#9F86C0;">
                <i class="ti ti-arrow-left" style="font-size:16px;" aria-hidden="true"></i>
                Kembali
            </a>

            <!-- ICON -->
            <div class="flex justify-center mt-5">
                <div class="w-24 h-24 rounded-full flex items-center justify-center" style="background:#EDE4F5;">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background:#9F86C0;">
                        <i class="ti ti-mail text-white" style="font-size:24px;" aria-hidden="true"></i>
                    </div>
                </div>
            </div>

            <!-- TITLE -->
            <h1 class="mt-6 text-3xl font-bold text-center" style="color:#2D1B69;">Verifikasi Email</h1>
            <p class="mt-3 text-base text-center" style="color:#9ca3af;">Kami mengirimkan kode 6 digit ke</p>

            <!-- EMAIL BADGE -->
            <div class="mt-5 flex justify-center">
                <div class="inline-flex items-center gap-2 px-5 py-3 rounded-full" style="background:#EDE4F5; border:1.5px solid #CDB4DB;">
                    <i class="ti ti-mail" style="font-size:14px; color:#9F86C0;" aria-hidden="true"></i>
                    <span class="text-sm font-semibold" style="color:#5E4B8B;">
                        {{ \App\Models\User::find(session('otp_user_id'))->email ?? '' }}
                    </span>
                </div>
            </div>

            @if(session('error'))
                <div class="mt-5 px-4 py-3 rounded-xl text-sm text-center" style="background:#fef2f2; border:1px solid #fecaca; color:#ef4444;">
                    {{ session('error') }}
                </div>
            @endif

            <!-- FORM -->
            <form method="POST" action="{{ route('verify.otp.post') }}" class="mt-9">
                @csrf

                <!-- OTP INPUTS -->
                <div class="flex justify-center gap-2">
                    @for($i = 0; $i < 6; $i++)
                    <input type="text" maxlength="1"
                        class="otp-input w-12 h-14 rounded-2xl text-center text-lg font-semibold focus:outline-none transition"
                        style="border:2px solid #CDB4DB; background:#FDFAFF; color:#5E4B8B;"
                        onfocus="this.style.borderColor='#9F86C0'"
                        onblur="this.style.borderColor='#CDB4DB'">
                    @endfor
                </div>

                <input type="hidden" name="otp" id="otp">

                <!-- TIMER -->
                <div class="mt-7 text-center">
                    <p class="text-sm" style="color:#9ca3af;">
                        Kode berlaku selama
                        <span id="timer" class="font-bold" style="color:#9F86C0;">05:00</span>
                    </p>
                    <p class="mt-4 text-sm" style="color:#9ca3af;">
                        Tidak terima kode?
                        <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color:#9F86C0;">Kirim ulang</a>
                    </p>
                </div>

                <!-- BUTTON -->
                <button type="submit"
                    class="w-full mt-9 h-14 rounded-2xl text-white text-lg font-bold transition"
                    style="background:#9F86C0;"
                    onmouseover="this.style.background='#5E4B8B'"
                    onmouseout="this.style.background='#9F86C0'">
                    Verifikasi & Lanjutkan
                </button>

            </form>

        </div>

    </div>

    <script>
        const inputs = document.querySelectorAll('.otp-input');
        const hiddenOtp = document.getElementById('otp');

        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                if (e.target.value.length === 1 && index < inputs.length - 1) inputs[index + 1].focus();
                gabungOtp();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '' && index > 0) inputs[index - 1].focus();
            });
        });

        function gabungOtp() {
            let otp = '';
            inputs.forEach(input => otp += input.value);
            hiddenOtp.value = otp;
        }

        let time = 300;
        const timer = document.getElementById('timer');
        const countdown = setInterval(() => {
            let minutes = Math.floor(time / 60);
            let seconds = time % 60;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            timer.innerHTML = `${minutes}:${seconds}`;
            if (time <= 0) { clearInterval(countdown); timer.innerHTML = "Expired"; }
            time--;
        }, 1000);
    </script>

</body>
</html>