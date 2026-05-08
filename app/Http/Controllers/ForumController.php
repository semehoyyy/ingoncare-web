<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Carbon\Carbon;

class ForumController extends Controller
{
    /**
     * Halaman Forum Utama dengan Filter
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'terbaru'); // Default: terbaru
        
        $query = Comment::whereNull('parent_id')
                        ->with(['user', 'likes', 'replies']);
        
        switch ($filter) {
            case 'trending':
                // Trending: Diskusi dengan interaksi terbanyak dalam 7 hari terakhir
                $query->where('created_at', '>=', Carbon::now()->subDays(7))
                      ->withCount([
                          'likes as likes_count',
                          'replies as replies_count'
                      ])
                      ->orderByRaw('(likes_count + replies_count) DESC');
                break;
                
            case 'populer':
                // Populer: Total interaksi terbanyak sepanjang waktu
                $query->withCount([
                    'likes as likes_count',
                    'replies as replies_count'
                ])
                ->orderByRaw('(likes_count + replies_count) DESC');
                break;
                
            case 'terbaru':
            default:
                // Terbaru: Urut berdasarkan waktu posting
                $query->latest();
                break;
        }
        
        $comments = $query->paginate(10);
        
        // Data statistik untuk tampilan
        $stats = [
            'total_diskusi' => Comment::whereNull('parent_id')->count(),
            'total_komentar' => Comment::whereNotNull('parent_id')->count(),
            'trending_count' => Comment::whereNull('parent_id')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count(),
        ];
        
        return view('forum.index', compact('comments', 'filter', 'stats'));
    }

    /**
     * SHOW THREAD: 1 comment + semua balasan (NESTED RECURSIVE)
     */
    public function show(Comment $comment)
    {
        // Load relations recursively
        $comment->load([
            'user',
            'likes',
            'replies' => function($query) {
                $query->with(['user', 'likes', 'replies.user', 'replies.likes'])
                      ->orderBy('created_at', 'asc');
            }
        ]);

        return view('forum.show', compact('comment'));
    }
}