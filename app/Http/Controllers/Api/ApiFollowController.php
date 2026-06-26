<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follow;
use App\Models\Comment;
use App\Models\Notification;

class ApiFollowController extends Controller
{
    /**
     * Follow user
     */
    public function follow(Request $request, $userId)
    {
        $user = $request->user();
        $target = User::find($userId);

        if (!$target) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        if ($user->id === $target->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa follow diri sendiri.',
            ], 400);
        }

        if ($user->isFollowing($target)) {
            return response()->json([
                'success' => false,
                'message' => 'Sudah follow user ini.',
            ], 400);
        }

        Follow::create([
            'follower_id'  => $user->id,
            'following_id' => $target->id,
        ]);

        // =========================
        // Tambahkan notifikasi follow
        // =========================
        Notification::create([
            'user_id'   => $target->id,
            'type'      => 'follow',
            'title'     => 'Pengikut Baru',
            'message'   => $user->name . ' mulai mengikuti Anda.',
            'link'      => '/profile/' . $user->id,
            'notify_at' => now(),
            'is_read'   => false,
        ]);

        return response()->json([
            'success'         => true,
            'message'         => 'Berhasil follow ' . $target->name,
            'followers_count' => $target->followers()->count(),
        ]);
    }

    /**
     * Unfollow user
     */
    public function unfollow(Request $request, $userId)
    {
        $user = $request->user();
        $target = User::find($userId);

        if (!$target) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        Follow::where('follower_id', $user->id)
            ->where('following_id', $target->id)
            ->delete();

        return response()->json([
            'success'         => true,
            'message'         => 'Berhasil unfollow ' . $target->name,
            'followers_count' => $target->followers()->count(),
        ]);
    }

    /**
     * List followers
     */
    public function followers(Request $request, $userId)
    {
        $user = User::find($userId) ?? $request->user();

        $followers = $user->followers()
            ->select('users.id', 'users.name', 'users.email', 'users.profile_photo')
            ->get();

        return response()->json([
            'success'   => true,
            'followers' => $followers->map(function ($f) use ($request) {
                return [
                    'id'            => $f->id,
                    'name'          => $f->name,
                    'email'         => $f->email,
                    'profile_photo' => $f->profile_photo ? asset('storage/' . $f->profile_photo) : null,
                    'is_followed'   => $request->user()->isFollowing($f),
                    'is_self'       => $request->user()->id === $f->id,
                ];
            }),
            'total' => $followers->count(),
        ]);
    }

    /**
     * List following
     */
    public function following(Request $request, $userId)
    {
        $user = User::find($userId) ?? $request->user();

        $following = $user->following()
            ->select('users.id', 'users.name', 'users.email', 'users.profile_photo')
            ->get();

        return response()->json([
            'success'   => true,
            'following' => $following->map(function ($f) use ($request) {
                return [
                    'id'            => $f->id,
                    'name'          => $f->name,
                    'email'         => $f->email,
                    'profile_photo' => $f->profile_photo ? asset('storage/' . $f->profile_photo) : null,
                    'is_followed'   => $request->user()->isFollowing($f),
                    'is_self'       => $request->user()->id === $f->id,
                ];
            }),
            'total' => $following->count(),
        ]);
    }

    /**
     * Profil user lain (+ postingannya)
     */
    public function userProfile(Request $request, $userId)
    {
        $target = User::find($userId);

        if (!$target) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        $posts = Comment::where('user_id', $target->id)
            ->whereNull('parent_id')
            ->with(['likes', 'replies'])
            ->latest()
            ->get()
            ->map(function ($post) use ($request) {
                return [
                    'id'            => $post->id,
                    'title'         => $post->title,
                    'content'       => $post->content,
                    'image'         => $post->image ? asset('storage/' . $post->image) : null,
                    'likes_count'   => $post->likes->count(),
                    'replies_count' => $post->replies->count(),
                    'is_liked'      => $request->user()
                        ? $post->likes->contains('id', $request->user()->id)
                        : false,
                    'created_at' => $post->created_at,
                    'time_ago'   => $post->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'user'    => [
                'id'              => $target->id,
                'name'            => $target->name,
                'email'           => $target->email,
                'address'         => $target->address,
                'profile_photo'   => $target->profile_photo ? asset('storage/' . $target->profile_photo) : null,
                'followers_count' => $target->followers()->count(),
                'following_count' => $target->following()->count(),
                'posts_count'     => $posts->count(),
                'is_followed'     => $request->user()->isFollowing($target),
                'is_self'         => $request->user()->id === $target->id,
            ],
            'posts' => $posts,
        ]);
    }

    /**
     * Search users
     */
    public function searchUsers(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'users' => [],
            ]);
        }

        $users = User::where('id', '!=', $request->user()->id)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users->map(function ($user) use ($request) {
                return [
                    'id'              => $user->id,
                    'name'            => $user->name,
                    'email'           => $user->email,
                    'profile_photo'   => $user->profile_photo ? asset('storage/' . $user->profile_photo) : null,
                    'followers_count' => $user->followers()->count(),
                    'is_followed'     => $request->user()->isFollowing($user),
                ];
            }),
        ]);
    }
}