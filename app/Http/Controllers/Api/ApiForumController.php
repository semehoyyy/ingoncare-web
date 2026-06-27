<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ApiForumController extends Controller
{
    /**
     * LIST POSTS
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'terbaru');
        $perPage = 10;

        $query = Comment::whereNull('parent_id')
            ->with(['user', 'likes', 'replies']);

        switch ($filter) {
            case 'trending':
                $query->where('created_at', '>=', Carbon::now()->subDays(7))
                    ->withCount(['likes', 'replies'])
                    ->orderByRaw('(likes_count + replies_count) DESC');
                break;

            case 'populer':
                $query->withCount(['likes', 'replies'])
                    ->orderByRaw('(likes_count + replies_count) DESC');
                break;

            default:
                $query->latest();
        }

        $posts = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'posts' => $posts->map(function ($post) use ($request) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'content' => $post->content,
                    'image' => $post->image ? asset('storage/' . $post->image) : null,
                    'user' => [
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                        'profile_photo' => $post->user->profile_photo
                            ? asset('storage/' . $post->user->profile_photo)
                            : null,
                    ],
                    'likes_count' => $post->likes->count(),
                    'replies_count' => $post->replies->count(),
                    'is_liked' => $request->user()
                        ? $post->likes->contains('user_id', $request->user()->id)
                        : false,
                    'is_own' => $request->user()
                        ? $post->user_id === $request->user()->id
                        : false,
                    'created_at' => $post->created_at,
                    'time_ago' => $post->created_at->diffForHumans(),
                ];
            }),
        ]);
    }

    /**
     * DETAIL THREAD
     */
    public function show(Request $request, $id)
    {
        $post = Comment::with([
            'user',
            'likes',
            'replies.user',
            'replies.likes',
            'replies.replyTo.user'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'image' => $post->image ? asset('storage/' . $post->image) : null,
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->name,
                    'profile_photo' => $post->user->profile_photo
                        ? asset('storage/' . $post->user->profile_photo)
                        : null,
                ],
                'likes_count' => $post->likes->count(),
                'is_liked' => $request->user()
                    ? $post->likes->contains('user_id', $request->user()->id)
                    : false,
                'time_ago' => $post->created_at->diffForHumans(),
                'replies' => $this->buildReplies($post->replies, $request->user()),
            ]
        ]);
    }

    /**
     * RECURSIVE REPLIES BUILDER
     */
    private function buildReplies($replies, $currentUser)
    {
        return $replies->map(function ($reply) use ($currentUser) {
            return [
                'id' => $reply->id,
                'content' => $reply->content,
                'image' => $reply->image ? asset('storage/' . $reply->image) : null,
                'reply_to_id' => $reply->reply_to_id,
                'mention' => $reply->replyTo?->user?->name,
                'user' => [
                    'id' => $reply->user->id,
                    'name' => $reply->user->name,
                    'profile_photo' => $reply->user->profile_photo
                        ? asset('storage/' . $reply->user->profile_photo)
                        : null,
                ],
                'likes_count' => $reply->likes->count(),
                'is_liked' => $currentUser
                    ? $reply->likes->contains('user_id', $currentUser->id)
                    : false,
                'time_ago' => $reply->created_at->diffForHumans(),
                'replies' => $this->buildReplies($reply->replies, $currentUser),
            ];
        });
    }

    /**
     * CREATE COMMENT / REPLY
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'required|exists:comments,id',
            'reply_to_id' => 'nullable|exists:comments,id',
        ]);

        $parentId = $request->parent_id;
        if ($request->reply_to_id) {
            $commentYangDibalas = Comment::find($request->reply_to_id);
            if ($commentYangDibalas && $commentYangDibalas->parent_id) {
                $parentId = $commentYangDibalas->parent_id;
            } else {
                $parentId = $request->reply_to_id;
            }
        }

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'content' => $request->content,
            'parent_id' => $parentId,
            'reply_to_id' => $request->reply_to_id,
        ]);

        $comment->load(['user', 'replyTo.user']);

        // ✅ Notifikasi komentar/balasan
        $parent = Comment::find($parentId);
        if ($parent && $parent->user_id !== $request->user()->id) {
            $owner = User::find($parent->user_id);

            Notification::create([
                'user_id' => $owner->id,
                'type'    => 'comment',
                'title'   => 'Komentar Baru',
                'message' => $request->user()->name . ' mengomentari postingan Anda',
                'link'    => '/forum/' . $parent->id,
                'is_read' => false,
            ]);

            // ✅ Kirim push notif ke HP
            FcmService::sendToUser(
                $owner,
                'Komentar Baru',
                $request->user()->name . ' mengomentari postingan Anda',
                ['link' => '/forum/' . $parent->id]
            );
        }

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'reply_to_id' => $comment->reply_to_id,
                'mention' => $comment->replyTo?->user?->name,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                ],
                'likes_count' => 0,
                'is_liked' => false,
                'time_ago' => '1 second ago',
                'replies' => []
            ],
        ], 201);
    }

    /**
     * LIKE / UNLIKE
     */
    public function like(Request $request, $id)
    {
        $user = $request->user();
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Komentar tidak ditemukan'], 404);
        }

        $result = $comment->likes()->toggle($user->id);
        $isLiked = count($result['attached']) > 0;

        // ✅ Notifikasi & push notif saat like
        if ($isLiked && $comment->user_id !== $user->id) {
            $owner = User::find($comment->user_id);

            if ($owner) {
                // Tentukan link ke forum utama
                $linkId = $comment->parent_id ?? $comment->id;

                Notification::create([
                    'user_id' => $owner->id,
                    'type'    => 'like',
                    'title'   => 'Like Baru',
                    'message' => $user->name . ' menyukai postingan Anda',
                    'link'    => '/forum/' . $linkId,
                    'is_read' => false,
                ]);

                // ✅ Kirim push notif ke HP
                FcmService::sendToUser(
                    $owner,
                    'Like Baru',
                    $user->name . ' menyukai postingan Anda',
                    ['link' => '/forum/' . $linkId]
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => $isLiked ? 'Komentar disukai' : 'Like dibatalkan',
            'liked' => $isLiked
        ]);
    }

    /**
     * DELETE COMMENT + ALL REPLIES
     */
    public function deleteCommentAndReplies($comment)
    {
        foreach (Comment::where('parent_id', $comment->id)->get() as $reply) {
            $this->deleteCommentAndReplies($reply);
        }

        if ($comment->image) {
            Storage::disk('public')->delete($comment->image);
        }

        $comment->delete();
    }

    /**
     * DESTROY
     */
    public function destroy(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $this->deleteCommentAndReplies($comment);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil dihapus!',
        ]);
    }

    /**
     * SEARCH
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (!$query) {
            return response()->json([
                'success' => true,
                'posts' => [],
            ]);
        }

        $posts = Comment::whereNull('parent_id')
            ->where(function ($q) use ($query) {
                $q->where('content', 'like', "%$query%")
                  ->orWhere('title', 'like', "%$query%");
            })
            ->with(['user', 'likes', 'replies'])
            ->latest()
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'posts' => $posts,
        ]);
    }
}