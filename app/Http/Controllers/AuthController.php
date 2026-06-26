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
            //atur waktu kadaluarsa OTP, misalnya 5 menit dari sekarang
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
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak ditemukan.',
                ], 404);
            }

            return back()->with('error', 'Email tidak ditemukan.');
        }

        $token = Str::random(40);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $token,
                'created_at' => now(),
            ]
        );

        $resetLink = 'ingoncare://reset-password?email=' . urlencode($user->email) . '&token=' . $token;

        Mail::send([], [], function ($message) use ($user, $resetLink) {
            $message->to($user->email)
                ->subject('Reset Password IngonCare')
                ->html('
                    <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                        <h2>Reset Password IngonCare</h2>

                        <p>Halo,</p>

                        <p>Kami menerima permintaan untuk reset password akun IngonCare Anda.</p>

                        <p>Klik tombol di bawah ini untuk membuat password baru:</p>

                        <p style="margin: 24px 0;">
                            <a href="' . $resetLink . '"
                               style="
                                    background-color: #4CAF50;
                                    color: white;
                                    padding: 12px 20px;
                                    text-decoration: none;
                                    border-radius: 8px;
                                    display: inline-block;
                                    font-weight: bold;
                               ">
                                Reset Password
                            </a>
                        </p>

                        <p>Jika tombol tidak bisa diklik, salin link berikut:</p>

                        <p style="word-break: break-all;">
                            ' . $resetLink . '
                        </p>

                        <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>

                        <p>Terima kasih,<br>IngonCare</p>
                    </div>
                ');
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Link reset password berhasil dikirim ke email.',
            ]);
        }

        return back()->with('success', 'Link reset password berhasil dikirim ke email.');
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
            'token' => 'required',
            'email' => 'nullable|email',
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
            'password.regex'     => 'Password harus mengandung huruf besar, angka, dan karakter spesial.',
            'password.confirmed' => 'Password dan Confirm Password tidak sama!',
        ]);

        $resetQuery = DB::table('password_reset_tokens')
            ->where('token', $request->token);

        if ($request->email) {
            $resetQuery->where('email', $request->email);
        }

        $reset = $resetQuery->first();

        if (!$reset) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token reset tidak valid atau sudah kedaluwarsa.',
                ], 400);
            }

            return back()->with('error', 'Token tidak valid atau sudah kedaluwarsa.');
        }

        $user = User::where('email', $reset->email)->first();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.',
                ], 404);
            }

            return back()->with('error', 'User tidak ditemukan.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')
            ->where('email', $reset->email)
            ->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diperbarui.',
            ]);
        }

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui.');
    }

    // ================================
    //  OTP
    // ================================
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

        $otp = rand(100000, 999999);

        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        Mail::to($user->email)->send(new SendOtpMail($otp));

        return back()->with('success', 'OTP berhasil dikirim ulang.');
    }
}