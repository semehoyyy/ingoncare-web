<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isOwnProfile = true;
        return $this->showProfile($user, $request, $isOwnProfile);
    }

    public function show(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $isOwnProfile = Auth::id() === $user->id;
        return $this->showProfile($user, $request, $isOwnProfile);
    }

    private function showProfile($user, $request, $isOwnProfile)
    {
        $tab = $request->get('tab', 'postingan');

        $stats = [
            'total_posts'   => $user->comments()->whereNull('parent_id')->count(),
            'total_replies' => $user->comments()->whereNotNull('parent_id')->count(),
            'total_likes'   => $user->comments()->withCount('likes')->get()->sum('likes_count'),
        ];

        $content = [];

        switch ($tab) {
            case 'postingan':
                $content = $user->comments()
                    ->whereNull('parent_id')
                    ->with(['likes', 'replies', 'user'])
                    ->latest()
                    ->paginate(10);
                break;

            case 'balasan':
                $content = $user->comments()
                    ->whereNotNull('parent_id')
                    ->with(['likes', 'replies', 'user', 'parent', 'parent.user'])
                    ->latest()
                    ->paginate(10);
                break;

            case 'suka':
                $content = $user->likes()
                    ->with(['likes', 'replies', 'user'])
                    ->latest('comment_user_likes.created_at')
                    ->paginate(10);
                break;
        }

        return view('profile.index', compact('user', 'stats', 'content', 'tab', 'isOwnProfile'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'phone'   => 'required|unique:users,phone,' . $user->id,
            'dob'     => 'nullable|date',
            'address' => 'nullable|string|max:500',
            // profile_photo is optional — could come as file or base64
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // ── 1. Cropped Base64 Upload (from crop modal) ──────────────────
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            // Normal file upload (fallback)
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $photoPath;
        }

        // ── 2. Update basic info ─────────────────────────────────────────
        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->phone   = $request->phone;
        $user->dob     = $request->dob;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'password.required'         => 'Password baru wajib diisi',
            'password.confirmed'        => 'Konfirmasi password tidak cocok',
            'password.min'              => 'Password minimal 8 karakter',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah!');
    }
}