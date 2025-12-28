<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCommunities = Community::inRandomOrder()->take(3)->get();
        return view('welcome', compact('featuredCommunities'));
    }

    public function dashboard()
    {
        $user = auth()->user();

        // 1. Pega os IDs das comunidades que sigo
        $followedCommunityIds = $user->follows()->pluck('communities.id');

        // 2. Pega os posts (CORRIGIDO: Removido o '...' e colocada a array correta)
        $posts = Post::whereIn('community_id', $followedCommunityIds)
                     ->published() // Usa o Scope do Model
                     ->with(['user', 'community', 'likes', 'comments.user']) // Carrega dados vitais
                     ->latest()
                     ->get();

        return view('dashboard', compact('posts'));
    }
}