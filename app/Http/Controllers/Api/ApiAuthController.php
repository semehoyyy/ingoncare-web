<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\SendOtpMail;

class ApiAuthController extends Controller
{
    /**
     * Register
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'phone'    => 'required|unique:users',
            'password' => [
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
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Silakan login.',
        ], 201);
    }

    /**
     * Login — kirim OTP ke email
     */
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('phone', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email/Phone atau Password salah.',
            ], 401);
        }

        $otp = rand(100000, 999999);

        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        try {
            Mail::to($user->email)->send(new SendOtpMail($otp));
        } catch (\Exception $e) {
            // silent for development
        }

        return response()->json([
            'success'      => true,
            'message'      => 'OTP telah dikirim ke email Anda.',
            'user_id'      => $user->id,
            'requires_otp' => true,
        ]);
    }

    /**
     * Verify OTP — return token jika valid
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'otp'     => 'required|string',
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        if ($user->otp != $request->otp || now()->gt($user->otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP salah atau sudah expired.',
            ], 401);
        }

        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        $token = $user->createToken('ingoncare-mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil!',
            'token'   => $token,
            'user'    => [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone,
                'dob'           => $user->dob,
                'address'       => $user->address,
                'profile_photo' => $user->profile_photo,
            ],
        ]);
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        $otp = rand(100000, 999999);

        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        try {
            Mail::to($user->email)->send(new SendOtpMail($otp));
        } catch (\Exception $e) {
            // silent
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP berhasil dikirim ulang.',
        ]);
    }

    /**
     * Login tanpa OTP untuk development/testing
     */
    public function loginDirect(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('phone', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email/Phone atau Password salah.',
            ], 401);
        }

        $token = $user->createToken('ingoncare-mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil!',
            'token'   => $token,
            'user'    => [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone,
                'dob'           => $user->dob,
                'address'       => $user->address,
                'profile_photo' => $user->profile_photo,
            ],
        ]);
    }

    /**
     * Forgot Password — kirim link deep link ke Flutter
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan atau tidak terdaftar.',
            ], 404);
        }

        $token = Str::random(40);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $token,
                'created_at' => now(),
            ]
        );

        $resetLink = url('/open-reset-password') . '?email=' . urlencode($user->email) . '&token=' . $token;

        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email reset password.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Link reset kata sandi telah dikirim ke email Anda.',
        ]);
    }

    /**
     * Reset Password — pakai email dan token dari deep link
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'token'    => 'required|string',
            'password' => [
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
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return response()->json([
                'success' => false,
                'message' => 'Token reset tidak valid atau sudah kedaluwarsa.',
            ], 400);
        }

        $createdAt = $reset->created_at ? \Carbon\Carbon::parse($reset->created_at) : null;

        if ($createdAt && $createdAt->lt(now()->subMinutes(60))) {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return response()->json([
                'success' => false,
                'message' => 'Token reset sudah kedaluwarsa.',
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.',
        ]);
    }

    /**
     * Reset Password Direct — tanpa email token, untuk development/testing
     */
    public function resetPasswordDirect(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan.',
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.',
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * Get current user
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'user'    => [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone,
                'dob'           => $user->dob,
                'address'       => $user->address,
                'profile_photo' => $user->profile_photo,
            ],
        ]);
    }

    /**
     * ✅ TAMBAHAN: Update FCM Token untuk Push Notification Flutter
     */
    public function updateFcmToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi token gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        if ($user) {
            $user->update([
                'fcm_token' => $request->fcm_token
            ]);

            return response()->json([
                'success' => true,
                'message' => 'FCM Token berhasil diperbarui.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User tidak terautentikasi.',
        ], 401);
    }
}