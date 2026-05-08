<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi OTP</title>
</head>
<body>

<h2>Masukkan Kode OTP</h2>

@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

<form method="POST" action="{{ route('verify.otp.post') }}">
    @csrf

    <input type="text" name="otp" placeholder="Masukkan OTP">

    <button type="submit">
        Verifikasi
    </button>
</form>

</body>
</html>