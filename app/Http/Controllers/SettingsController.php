<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UserSetting;

class SettingsController extends Controller
{
    /**
     * Tampilkan halaman settings utama
     */
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    /**
     * Halaman Notifikasi Settings
     */
    public function notifications()
    {
        $user = Auth::user();
        $settings = $user->settings ?? UserSetting::create(['user_id' => $user->id]);
        return view('settings.notifications', compact('user', 'settings'));
    }

    /**
     * Update Notifikasi Preferences
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        $settings = $user->settings ?? UserSetting::create(['user_id' => $user->id]);
        
        $settings->update([
            'push_enabled' => $request->has('push_enabled'),
            'notif_likes' => $request->has('notif_likes'),
            'notif_comments' => $request->has('notif_comments'),
            'notif_reminders' => $request->has('notif_reminders'),
            'email_weekly' => $request->has('email_weekly'),
            'email_tips' => $request->has('email_tips'),
        ]);

        return redirect()->route('settings.notifications')
            ->with('success', 'Pengaturan notifikasi berhasil diperbarui!');
    }

    /**
     * Halaman Keamanan
     */
    public function security()
    {
        $user = Auth::user();
        return view('settings.security', compact('user'));
    }

    /**
     * Update Password dari Settings
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'password.required' => 'Password baru wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('settings.security')
            ->with('success', 'Password berhasil diubah!');
    }

    /**
     * Halaman Privasi
     */
    public function privacy()
    {
        $user = Auth::user();
        $settings = $user->settings ?? UserSetting::create(['user_id' => $user->id]);
        return view('settings.privacy', compact('user', 'settings'));
    }

    /**
     * Update Privacy Settings
     */
    public function updatePrivacy(Request $request)
    {
        $user = Auth::user();
        $settings = $user->settings ?? UserSetting::create(['user_id' => $user->id]);
        
        $settings->update([
            'profile_public' => $request->has('profile_public'),
            'show_email' => $request->has('show_email'),
            'show_online_status' => $request->has('show_online_status'),
        ]);

        return redirect()->route('settings.privacy')
            ->with('success', 'Pengaturan privasi berhasil diperbarui!');
    }

    /**
     * Halaman Bahasa
     */
    
       /**
     * Halaman Bantuan & Dukungan
     */
    public function help()
    {
        $user = Auth::user();
        return view('settings.help', compact('user'));
    }

    /**
     * Download User Data
     */
    public function downloadData()
    {
        $user = Auth::user();
        
        // Compile user data
        $data = [
            'user' => $user->toArray(),
            'pets' => $user->pets->toArray(),
            'posts' => $user->comments()->whereNull('parent_id')->get()->toArray(),
            'replies' => $user->comments()->whereNotNull('parent_id')->get()->toArray(),
            'settings' => $user->settings ? $user->settings->toArray() : [],
        ];

        $filename = 'ingoncare_data_' . $user->id . '_' . date('Y-m-d') . '.json';
        
        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

}