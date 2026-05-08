<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;

class AuthController extends Controller
{
    // ================================
    //  LOGIN
    // ================================
    public function showLogin()
    {
        return view('auth.login');
    }

    public function prosesLogin(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('phone', $request->login)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {

            $otp = rand(100000, 999999);

            $user->otp = $otp;
            $user->otp_expires_at = now()->addMinutes(5);
            $user->save();

            Mail::to($user->email)->send(new SendOtpMail($otp));

            session([
                'otp_user_id' => $user->id
            ]);

            return redirect()->route('verify.otp');
        }

        return redirect()->route('login')->with('error', 'Email/Phone atau Password salah');
    }

    // ================================
    //  REGISTER
    // ================================
    public function showRegister()
    {
        return view('auth.register');
    }

    public function prosesRegister(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name'      => 'required|string|max:255',
        'email'     => 'required|string|email|max:255|unique:users',
        'phone'     => 'required|unique:users',
        'password'  => [
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/[A-Z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*#?&]/',
        ],
    ], [
        'password.min'       => 'Password minimal 8 karakter.',
        'password.regex'     => 'Password harus mengandung huruf besar, angka, dan karakter spesial.',
        'password.confirmed' => 'Password dan Confirm Password tidak sama!',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    User::create([
        'name'      => $request->name,
        'email'     => $request->email,
        'phone'     => $request->phone,
        'password'  => Hash::make($request->password),
    ]);

    return redirect()->route('login')->with('success', 'Akun berhasil dibuat!');
}

    // ================================
    //  LOGOUT
    // ================================
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // ================================
    //  FORGOT PASSWORD
    // ================================
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan.');
        }

        $token = Str::random(40);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Untuk sementara tanpa email, langsung ke halaman reset
        return redirect()->route('password.reset', $token)
            ->with('success', 'Silakan buat password baru.');
    }

    // ================================
    //  RESET PASSWORD
    // ================================
    public function showResetForm($token)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/'
            ]
        ], [
            'password.min'       => 'Password minimal 8 karakter.',
            'password.regex'     => 'Password harus mengandung huruf besar, angka, dan karakter.',
            'password.confirmed' => 'Password dan Confirm Password tidak sama!',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->with('error', 'Token tidak valid.');
        }

        $user = User::where('email', $reset->email)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $reset->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui.');
    }
    public function showVerifyOtp()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Session OTP tidak ditemukan.');
        }

        if (
            $user->otp == $request->otp &&
            now()->lt($user->otp_expires_at)
        ) {

            Auth::login($user);

            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            session()->forget('otp_user_id');

            return redirect()->route('dashboard');
        }

        return back()->with('error', 'OTP salah atau sudah expired.');
    }

    public function resendOtp(Request $request)
{
    $user = User::find(session('otp_user_id'));

    if (!$user) {
        return redirect()->route('login')
            ->with('error', 'Session OTP tidak ditemukan.');
    }

    // generate OTP baru
    $otp = rand(100000, 999999);

    $user->otp = $otp;
    $user->otp_expires_at = now()->addMinutes(5);
    $user->save();

    // kirim ulang email OTP
    Mail::to($user->email)->send(new SendOtpMail($otp));

    return back()->with('success', 'OTP berhasil dikirim ulang.');
}
}
