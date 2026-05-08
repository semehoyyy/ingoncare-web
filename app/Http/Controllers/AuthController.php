<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
            Auth::login($user);
            return redirect()->route('dashboard');
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
            'min:8',                    // minimal 8 karakter
            'confirmed',
            'regex:/[A-Z]/',            // harus ada huruf besar
            'regex:/[0-9]/',            // harus ada angka
            'regex:/[@$!%*#?&]/',       // karakter spesial
        ],
        'dob'       => 'required|date',
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
        'dob'       => $request->dob,
        'password'  => Hash::make($request->password),
    ]);

    return redirect()->route('login')->with('success', 'Akun berhasil dibuat!');
}

    // ================================
    //  LOGOUT
    // ================================
    public function logout()
    {
        auth()->logout();
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
}
