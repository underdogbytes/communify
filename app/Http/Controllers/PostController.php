<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * (J2-03) Salvar Comentário
     */
    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Comentário enviado!');
    }

    /**
     * Exibe a tela de criar artigo
     */
    public function createArticle(Community $community)
    {
        if ($community->user_id !== auth()->id()) {
            abort(403);
        }

        return view('community.posts.create', compact('community'));
    }

    /**
     * Salva um Post (Híbrido: Short ou Article)
     */
    public function store(Request $request, Community $community)
    {
        if ($community->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para postar nesta comunidade.');
        }

        $rules = [
            'content' => 'required|string',
        ];

        if ($request->input('type') === 'article') {
            $rules['title'] = 'required|string|max:255';
            $rules['content'] = 'required|string'; 
        } else {
            $rules['content'] = 'required|string|max:1000';
        }

        $validated = $request->validate($rules);

        $data = [
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'type' => $request->input('type', 'short'),
            'status' => 'published',
            'visibility' => 'public',
        ];

        if ($request->input('type') === 'article') {
            $data['title'] = $validated['title'];
            $data['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        }

        $community->posts()->create($data);

        return redirect()->route('community.show', $community->slug)
            ->with('success', 'Publicado com sucesso!');
    }

    /**
     * Exibe um Artigo Completo (Leitura)
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $post->load(['community', 'user']);

        $htmlContent = Str::markdown($post->content);

        return view('community.posts.show', compact('post', 'htmlContent'));
    }

    // --- MÉTODOS DE HIGIENE (QUE ESTAVAM FALTANDO) ---

    /**
     * Apagar Post
     */
    public function destroy(Post $post)
    {
        // Verifica se é o dono
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Você não é o dono deste post.');
        }

        $post->delete();

        return back()->with('success', 'Post removido com sucesso.');
    }

    /**
     * Tela de Edição (Apenas para Artigos)
     */
    public function edit(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        // Short posts não editam (regra de negócio), apenas deletam
        if ($post->type === 'short') {
            return back()->with('error', 'Posts curtos não podem ser editados, apenas excluídos.');
        }

        return view('community.posts.edit', compact('post'));
    }

    /**
     * Salvar a Edição
     */
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        // Redireciona para a leitura do artigo atualizado
        return redirect()->route('post.show', $post->slug)->with('success', 'Artigo atualizado!');
    }
}