<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    /**
     * ===============================
     * SIMPAN KOMENTAR / BALASAN
     * + NOTIFIKASI KOMENTAR
     * ===============================
     */
    public function store(Request $request)
    {
        $request->validate([
            'content'   => 'required|string|max:1000',
            'title'     => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:comments,id',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Upload image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('comments', 'public');
        }

        // Simpan komentar
        $comment = Comment::create([
            'user_id'   => Auth::id(),
            'content'   => $request->content,
            'title'     => $request->title,
            'image'     => $imagePath,
            'parent_id' => $request->parent_id,
        ]);

        /**
         * ===============================
         * NOTIFIKASI KOMENTAR
         * ===============================
         */

        // 🔹 BALASAN KOMENTAR
        if ($request->parent_id) {
            $parent = Comment::find($request->parent_id);

            if ($parent && $parent->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $parent->user_id,
                    'type'    => 'comment',
                    'title'   => 'Balasan Komentar',
                    'message' => Auth::user()->name . ' membalas komentar Anda',
                    'link'    => '/forum/' . ($parent->parent_id ?? $parent->id),
                    'is_read' => false,
                ]);
            }

            return redirect()
                ->route('forum.show', $parent->parent_id ?? $parent->id)
                ->with('success', 'Balasan berhasil ditambahkan!');
        }

        // 🔹 KOMENTAR BARU DI FORUM
        if ($comment->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $comment->user_id,
                'type'    => 'comment',
                'title'   => 'Komentar Baru',
                'message' => Auth::user()->name . ' mengomentari forum Anda',
                'link'    => '/forum/' . $comment->id,
                'is_read' => false,
            ]);
        }

        return back()->with('success', 'Diskusi berhasil diposting!');
    }

    /**
     * ===============================
     * LIKE / UNLIKE KOMENTAR
     * + NOTIFIKASI LIKE
     * ===============================
     */
    public function like($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $user = Auth::user();

            // Toggle like
            if ($comment->likes()->where('user_id', $user->id)->exists()) {
                $comment->likes()->detach($user->id);
                $isLiked = false;
            } else {
                $comment->likes()->attach($user->id);
                $isLiked = true;

                /**
                 * NOTIFIKASI LIKE
                 */
                // Tentukan pemilik forum utama
                if ($comment->parent_id) {
                    $root = Comment::find($comment->parent_id);
                    $ownerId = $root->user_id;
                    $linkId  = $root->id;
                } else {
                    $ownerId = $comment->user_id;
                    $linkId  = $comment->id;
                }

                // Jangan notif diri sendiri
                if ($ownerId !== $user->id) {
                    Notification::create([
                        'user_id' => $ownerId,
                        'type'    => 'like',
                        'title'   => 'Like Forum',
                        'message' => $user->name . ' menyukai postingan Anda',
                        'link'    => '/forum/' . $linkId,
                        'is_read' => false,
                    ]);
                }
            }

            return response()->json([
                'success'     => true,
                'likes_count' => $comment->likes()->count(),
                'is_liked'    => $isLiked,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ===============================
     * HAPUS KOMENTAR
     * ===============================
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $this->deleteCommentAndReplies($comment);

        return response()->json([
            'success' => true,
            'message' => 'Diskusi berhasil dihapus!'
        ]);
    }

    /**
     * ===============================
     * HAPUS KOMENTAR & BALASAN
     * ===============================
     */
    private function deleteCommentAndReplies($comment)
    {
        foreach (Comment::where('parent_id', $comment->id)->get() as $reply) {
            $this->deleteCommentAndReplies($reply);
        }

        if ($comment->image) {
            Storage::disk('public')->delete($comment->image);
        }

        $comment->delete();
    }
}
