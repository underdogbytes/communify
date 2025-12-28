<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $query = Community::with('user')->withCount('followers');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        $communities = $query->orderByDesc('followers_count')
                             ->latest()
                             ->paginate(12);

        return view('community.index', compact('communities'));
    }

    public function show($slug)
    {
        // Aqui usamos uma Closure (função anônima) dentro do with
        // Isso filtra os posts DENTRO da comunidade
        $community = Community::where('slug', $slug)
            ->with(['posts' => function($query) {
                $query->published() // Scope do Model Post
                      ->latest()
                      ->with(['user', 'likes', 'comments.user']); // Carrega user dos comentários
            }, 'products.baseProduct', 'user'])
            ->firstOrFail();

        return view('community.show', compact('community'));
    }

    public function follow(Request $request, Community $community)
    {
        $user = auth()->user();
        $user->follows()->toggle($community->id);
        return back();
    }
}