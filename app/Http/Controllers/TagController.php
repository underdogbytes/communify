<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Community;

class TagController extends Controller
{
    /**
     * Exibe conteúdo global baseado em uma Tag
     */
    public function show($tag)
    {
        // Limpeza básica da tag (remove # se vier na URL)
        $tag = str_replace('#', '', $tag);

        // 1. Buscar Posts que contenham essa tag
        // Como salvamos em JSON/Array, usamos o operador JSON do MySQL/Postgres
        // OU, como salvamos como texto simples no MVP, usamos LIKE.
        // Assumindo que no PostController salvamos como JSON casted array:
        
        $posts = Post::whereJsonContains('tags', $tag)
            ->where('status', 'published')
            ->where('visibility', 'public') // Só posts públicos aparecem na busca global
            ->with(['user', 'community'])
            ->latest()
            ->paginate(20);

        // 2. (Opcional) Buscar Comunidades da categoria x (se bater com a tag)
        $communities = Community::where('category', $tag)
            ->orWhere('description', 'like', "%#{$tag}%")
            ->take(3)
            ->get();

        return view('tags.show', compact('tag', 'posts', 'communities'));
    }
}