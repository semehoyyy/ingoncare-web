<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F7FAF8] min-h-screen relative overflow-x-hidden">

    <!-- LOGO -->
    <a href="{{ route('welcome') }}"
        class="absolute top-4 left-4 z-50">

        <img src="/img/header.png"
            alt="logo"
            class="w-16 md:w-20">
    </a>

    <!-- CONTENT -->
    <div class="flex items-center justify-center min-h-screen px-5 py-10">

        <!-- CARD -->
        <div class="w-full max-w-sm bg-white border border-[#DDF4F3] rounded-[32px] px-7 py-10 shadow-sm">

            <!-- BACK -->
            <a href="{{ route('register') }}"
                class="inline-flex items-center gap-2 text-[#6F7B77] text-[15px] font-semibold hover:opacity-80 transition">

                ← Kembali

            </a>

            <!-- ICON -->
            <div class="flex justify-center mt-5">

                <div class="w-24 h-24 rounded-full bg-[#EFFFFE] flex items-center justify-center">

                    <div class="w-14 h-14 rounded-[20px] bg-[#33E4DB] flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 text-white"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18V8H3v8z" />
                        </svg>

                    </div>
                </div>

            </div>

            <!-- TITLE -->
            <h1 class="mt-6 text-[32px] font-bold text-[#1F2D2B] text-center">
                Verifikasi Email
            </h1>

            <p class="mt-3 text-[16px] text-[#75827D] text-center leading-7">
                Kami mengirimkan kode 6 digit ke
            </p>

            <!-- EMAIL -->
            <div class="mt-5 flex justify-center">

                <div class="inline-flex items-center gap-2 bg-[#EFFFFE] px-5 py-3 rounded-full border border-[#CFF8F6]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 text-[#33E4DB]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 12H8m8 4H8m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>

                    <span class="text-[#159E98] text-[14px] font-semibold">
                        {{ \App\Models\User::find(session('otp_user_id'))->email ?? '' }}
                    </span>

                </div>

            </div>

            <!-- ERROR -->
            @if(session('error'))
                <div class="mt-5 bg-red-100 border border-red-300 text-red-600 px-4 py-3 rounded-xl text-sm text-center">
                    {{ session('error') }}
                </div>
            @endif

            <!-- FORM -->
            <form method="POST"
                action="{{ route('verify.otp.post') }}"
                class="mt-9">

                @csrf

                <!-- OTP -->
                <div class="flex justify-center gap-2">

                    <input type="text" maxlength="1"
                        class="otp-input w-[48px] h-[58px] border-2 border-[#DCE3DF] rounded-2xl text-center text-lg font-semibold focus:outline-none focus:border-[#33E4DB]">

                    <input type="text" maxlength="1"
                        class="otp-input w-[48px] h-[58px] border-2 border-[#DCE3DF] rounded-2xl text-center text-lg font-semibold focus:outline-none focus:border-[#33E4DB]">

                    <input type="text" maxlength="1"
                        class="otp-input w-[48px] h-[58px] border-2 border-[#DCE3DF] rounded-2xl text-center text-lg font-semibold focus:outline-none focus:border-[#33E4DB]">

                    <input type="text" maxlength="1"
                        class="otp-input w-[48px] h-[58px] border-2 border-[#DCE3DF] rounded-2xl text-center text-lg font-semibold focus:outline-none focus:border-[#33E4DB]">

                    <input type="text" maxlength="1"
                        class="otp-input w-[48px] h-[58px] border-2 border-[#DCE3DF] rounded-2xl text-center text-lg font-semibold focus:outline-none focus:border-[#33E4DB]">

                    <input type="text" maxlength="1"
                        class="otp-input w-[48px] h-[58px] border-2 border-[#DCE3DF] rounded-2xl text-center text-lg font-semibold focus:outline-none focus:border-[#33E4DB]">

                </div>

                <!-- hidden OTP -->
                <input type="hidden" name="otp" id="otp">

                <!-- TIMER -->
                <div class="mt-7 text-center">

                    <p class="text-[#A0AAA5] text-[14px]">
                        Kode berlaku selama
                        <span id="timer"
                            class="text-[#33E4DB] font-bold">
                            05:00
                        </span>
                    </p>

                    <p class="mt-4 text-[#A0AAA5] text-[14px]">
                        Tidak terima kode?

                        <a href="{{ route('resend.otp') }}"
                            class="text-[#33E4DB] font-semibold hover:underline">

                            Kirim ulang

                        </a>

                    </p>

                </div>

                <!-- BUTTON -->
                <button type="submit"
                    class="w-full mt-9 h-[60px] rounded-[22px] bg-[#33E4DB] text-white text-[18px] font-bold shadow-sm hover:opacity-90 transition">

                    Verifikasi & Lanjutkan

                </button>

            </form>

        </div>

    </div>

    <!-- SCRIPT -->
    <script>

        const inputs = document.querySelectorAll('.otp-input');
        const hiddenOtp = document.getElementById('otp');

        inputs.forEach((input, index) => {

            input.addEventListener('input', (e) => {

                e.target.value = e.target.value.replace(/[^0-9]/g, '');

                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                gabungOtp();

            });

            input.addEventListener('keydown', (e) => {

                if (e.key === 'Backspace' && input.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }

            });

        });

        function gabungOtp() {

            let otp = '';

            inputs.forEach(input => {
                otp += input.value;
            });

            hiddenOtp.value = otp;

        }

        // TIMER
        let time = 300;

        const timer = document.getElementById('timer');

        const countdown = setInterval(() => {

            let minutes = Math.floor(time / 60);
            let seconds = time % 60;

            seconds = seconds < 10 ? '0' + seconds : seconds;

            timer.innerHTML = `${minutes}:${seconds}`;

            if (time <= 0) {

                clearInterval(countdown);
                timer.innerHTML = "Expired";

            }

            time--;

        }, 1000);

    </script>

</body>
</html>