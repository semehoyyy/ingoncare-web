<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ApiForumController extends Controller
{
    /**
     * List forum posts (dengan filter)
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'terbaru');
        $page   = $request->get('page', 1);
        $perPage = 10;

        $query = Comment::whereNull('parent_id')
            ->with(['user', 'likes', 'replies']);

        switch ($filter) {
            case 'trending':
                $query->where('created_at', '>=', Carbon::now()->subDays(7))
                    ->withCount(['likes as likes_count', 'replies as replies_count'])
                    ->orderByRaw('(likes_count + replies_count) DESC');
                break;

            case 'populer':
                $query->withCount(['likes as likes_count', 'replies as replies_count'])
                    ->orderByRaw('(likes_count + replies_count) DESC');
                break;

            case 'terbaru':
            default:
                $query->latest();
                break;
        }

        $posts = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'filter'  => $filter,
            'posts'   => $posts->map(function ($post) use ($request) {
                return [
                    'id'            => $post->id,
                    'title'         => $post->title,
                    'content'       => $post->content,
                    'image'         => $post->image ? asset('storage/' . $post->image) : null,
                    'user'          => [
                        'id'            => $post->user->id,
                        'name'          => $post->user->name,
                        'profile_photo' => $post->user->profile_photo ? asset('storage/' . $post->user->profile_photo) : null,
                    ],
                    'likes_count'   => $post->likes->count(),
                    'replies_count' => $post->replies->count(),
                    'is_liked'      => $request->user() ? $post->likes->contains('id', $request->user()->id) : false,
                    'is_own'        => $request->user() ? $post->user_id === $request->user()->id : false,
                    'created_at'    => $post->created_at,
                    'time_ago'      => $post->created_at->diffForHumans(),
                ];
            }),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'last_page'    => $posts->lastPage(),
                'total'        => $posts->total(),
            ],
        ]);
    }

    /**
     * Detail thread (post + replies)
     */
    public function show(Request $request, $id)
    {
        $post = Comment::with([
            'user',
            'likes',
            'replies' => function ($q) {
                $q->with(['user', 'likes', 'replies.user', 'replies.likes'])
                    ->orderBy('created_at', 'asc');
            }
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'post'    => [
                'id'            => $post->id,
                'title'         => $post->title,
                'content'       => $post->content,
                'image'         => $post->image ? asset('storage/' . $post->image) : null,
                'user'          => [
                    'id'            => $post->user->id,
                    'name'          => $post->user->name,
                    'profile_photo' => $post->user->profile_photo ? asset('storage/' . $post->user->profile_photo) : null,
                ],
                'likes_count'   => $post->likes->count(),
                'is_liked'      => $request->user() ? $post->likes->contains('id', $request->user()->id) : false,
                'is_own'        => $request->user() ? $post->user_id === $request->user()->id : false,
                'created_at'    => $post->created_at,
                'time_ago'      => $post->created_at->diffForHumans(),
                'replies'       => $post->replies->map(function ($reply) use ($request) {
                    return [
                        'id'          => $reply->id,
                        'content'     => $reply->content,
                        'image'       => $reply->image ? asset('storage/' . $reply->image) : null,
                        'user'        => [
                            'id'            => $reply->user->id,
                            'name'          => $reply->user->name,
                            'profile_photo' => $reply->user->profile_photo ? asset('storage/' . $reply->user->profile_photo) : null,
                        ],
                        'likes_count' => $reply->likes->count(),
                        'is_liked'    => $request->user() ? $reply->likes->contains('id', $request->user()->id) : false,
                        'is_own'      => $request->user() ? $reply->user_id === $request->user()->id : false,
                        'created_at'  => $reply->created_at,
                        'time_ago'    => $reply->created_at->diffForHumans(),
                    ];
                }),
            ],
        ]);
    }

    /**
     * Buat post / reply baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'content'   => 'required|string|max:1000',
            'title'     => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:comments,id',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('comments', 'public');
        }

        $comment = Comment::create([
            'user_id'   => $request->user()->id,
            'content'   => $request->content,
            'title'     => $request->title,
            'image'     => $imagePath,
            'parent_id' => $request->parent_id,
        ]);

        // Notifikasi untuk reply
        if ($request->parent_id) {
            $parent = Comment::find($request->parent_id);
            if ($parent && $parent->user_id !== $request->user()->id) {
                Notification::create([
                    'user_id' => $parent->user_id,
                    'type'    => 'comment',
                    'title'   => 'Balasan Komentar',
                    'message' => $request->user()->name . ' membalas komentar Anda',
                    'link'    => '/forum/' . ($parent->parent_id ?? $parent->id),
                    'is_read' => false,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => $request->parent_id ? 'Balasan berhasil ditambahkan!' : 'Diskusi berhasil diposting!',
            'comment' => [
                'id'         => $comment->id,
                'content'    => $comment->content,
                'title'      => $comment->title,
                'image'      => $comment->image ? asset('storage/' . $comment->image) : null,
                'parent_id'  => $comment->parent_id,
                'created_at' => $comment->created_at,
            ],
        ], 201);
    }

    /**
     * Like / Unlike
     */
    public function like(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $user    = $request->user();

        if ($comment->likes()->where('user_id', $user->id)->exists()) {
            $comment->likes()->detach($user->id);
            $isLiked = false;
        } else {
            $comment->likes()->attach($user->id);
            $isLiked = true;

            // Notifikasi like
            $ownerId = $comment->parent_id
                ? (Comment::find($comment->parent_id)->user_id ?? $comment->user_id)
                : $comment->user_id;

            if ($ownerId !== $user->id) {
                Notification::create([
                    'user_id' => $ownerId,
                    'type'    => 'like',
                    'title'   => 'Like Forum',
                    'message' => $user->name . ' menyukai postingan Anda',
                    'link'    => '/forum/' . ($comment->parent_id ?? $comment->id),
                    'is_read' => false,
                ]);
            }
        }

        return response()->json([
            'success'     => true,
            'likes_count' => $comment->likes()->count(),
            'is_liked'    => $isLiked,
        ]);
    }

    /**
     * Hapus post/comment
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
     * Search forum
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json([
                'success' => true,
                'posts'   => [],
            ]);
        }

        $posts = Comment::whereNull('parent_id')
            ->where(function ($q) use ($query) {
                $q->where('content', 'like', "%{$query}%")
                    ->orWhere('title', 'like', "%{$query}%");
            })
            ->with(['user', 'likes', 'replies'])
            ->latest()
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'posts'   => $posts->map(function ($post) use ($request) {
                return [
                    'id'            => $post->id,
                    'title'         => $post->title,
                    'content'       => $post->content,
                    'user'          => [
                        'id'   => $post->user->id,
                        'name' => $post->user->name,
                    ],
                    'likes_count'   => $post->likes->count(),
                    'replies_count' => $post->replies->count(),
                    'created_at'    => $post->created_at,
                    'time_ago'      => $post->created_at->diffForHumans(),
                ];
            }),
        ]);
    }

    /**
     * Recursive delete
     */
    private function deleteCommentAndReplies($comment)
    {
        foreach (Comment::where('parent_id', $comment->id)->get() as $reply) {
            $this->deleteCommentAndReplies($reply);
        }

        if ($comment->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($comment->image);
        }

        $comment->delete();
    }
}
