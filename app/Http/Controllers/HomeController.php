<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');

        // Query dasar untuk comments
        $query = Comment::whereNull('parent_id')
                    ->with('user', 'likes', 'replies');

        // Jika ada search query
        if ($search) {
            $query->where(function($q) use ($search) {
                // Cari di content
                $q->where('content', 'LIKE', "%{$search}%")
                  // Cari di title
                  ->orWhere('title', 'LIKE', "%{$search}%")
                  // Cari di nama user
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $comments = $query->latest()->get();

        // Jika user login, ambil hewan peliharaan
        $pets = $user ? $user->pets()->get() : collect();

        // Tren diskusi REAL: Ambil 3 diskusi dengan interaksi terbanyak dalam 7 hari terakhir
        $trending = Comment::whereNull('parent_id')
                    ->where('created_at', '>=', Carbon::now()->subDays(7))
                    ->withCount([
                        'likes as likes_count',
                        'replies as replies_count'
                    ])
                    ->orderByRaw('(likes_count + replies_count) DESC')
                    ->limit(3)
                    ->get();

        return view('home', compact('comments', 'pets', 'trending', 'search'));
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        
        if (empty($search)) {
            return redirect()->route('home');
        }

        // Cari di comments/diskusi
        $comments = Comment::whereNull('parent_id')
                        ->where(function($q) use ($search) {
                            $q->where('content', 'LIKE', "%{$search}%")
                              ->orWhere('title', 'LIKE', "%{$search}%")
                              ->orWhereHas('user', function($q2) use ($search) {
                                  $q2->where('name', 'LIKE', "%{$search}%");
                              });
                        })
                        ->with('user', 'likes', 'replies')
                        ->latest()
                        ->paginate(10);

        // Cari user yang cocok
        $users = User::where('name', 'LIKE', "%{$search}%")
                    ->limit(5)
                    ->get();

        return view('search.results', compact('comments', 'users', 'search'));
    }
}