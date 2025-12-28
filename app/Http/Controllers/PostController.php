<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\NewInteraction;
use App\Models\Comment; // Não esqueça de importar!

class PostController extends Controller
{
    /**
     * (J2-03) Salvar Comentário
     */
    public function storeComment(Request $request, Post $post)
    {
        $request->validate(['content' => 'required|string|max:500']);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        // --- NOTIFICAÇÃO (Adicione isso) ---
        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new NewInteraction(auth()->user(), $post, 'comment'));
        }
        // -----------------------------------

        return back()->with('success', 'Comentário enviado!');
    }

    /**
     * Exibe a tela de criar artigo
     */
    public function createArticle(Community $community)
    {
        $user = auth()->user();

        // Verificação: Precisa ser Dono OU Seguidor
        $isMember = $user->follows()->where('community_id', $community->id)->exists();
        $isOwner = $community->user_id === $user->id;

        if (!$isOwner && !$isMember) {
            abort(403, 'Você precisa participar da comunidade para escrever artigos.');
        }

        return view('community.posts.create', compact('community'));
    }

    /**
     * Salva um Post (Híbrido: Short ou Article)
     */
    public function store(Request $request, Community $community)
    {
        $user = auth()->user();

        // 1. Verificação: O usuário precisa seguir a comunidade ou ser o dono
        // (Assumindo que você tem o relacionamento 'follows' no User ou 'followers' na Community)
        $isMember = $user->follows()->where('community_id', $community->id)->exists();
        $isOwner = $community->user_id === $user->id;

        if (!$isOwner && !$isMember) {
            abort(403, 'Você precisa participar da comunidade para postar.');
        }

        // 2. Definir Status
        // Dono publica na hora. Membro vai para moderação.
        $status = $isOwner ? 'published' : 'pending';

        // ... [MANTENHA A VALIDAÇÃO IGUAL AO QUE JÁ TINHA] ...
        $rules = [
            'content' => 'required|string',
            'type' => 'required|in:short,article',
        ];

        if ($request->input('type') === 'article') {
            $rules['title'] = 'required|string|max:255';
            $rules['category'] = 'required|in:Artigo,Diário,Nota,Atualização';
            $rules['visibility'] = 'required|in:public,followers,members';
            $rules['tags'] = 'nullable|string';
            $rules['image'] = 'nullable|image|max:2048';
        } else {
            $rules['content'] = 'required|string|max:1000';
        }

        $validated = $request->validate($rules);

        // ... [PREPARAÇÃO DOS DADOS] ...
        $data = [
            'user_id' => $user->id,
            'content' => $validated['content'],
            'type' => $request->input('type'),
            'status' => $status, // <--- AQUI ESTÁ A MUDANÇA
            'visibility' => $request->input('visibility', 'public'),
        ];

        // Lógica de Artigo (Copia igual ao anterior)
        if ($request->input('type') === 'article') {
            $data['title'] = $validated['title'];
            $data['slug'] = Str::slug($validated['title']) . '-' . uniqid();
            $data['category'] = $validated['category'];
            if (!empty($request->tags)) {
                $tagsArray = array_map('trim', explode(',', $request->tags));
                $data['tags'] = array_filter($tagsArray);
            }
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('posts/covers', 'public');
            }
        }

        $community->posts()->create($data);

        // Mensagem diferente dependendo de quem postou
        $msg = $isOwner ? 'Publicado com sucesso!' : 'Post enviado para aprovação!';
        
        return redirect()->route('community.show', $community->slug)->with('success', $msg);
    }

    /**
     * Exibe um Artigo Completo (Leitura)
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->with(['comments.user', 'likes'])->firstOrFail();
        
        // 1. Converte Markdown para HTML
        $htmlContent = \Illuminate\Support\Str::markdown($post->content);

        // 2. Aplica os Embeds (Transforma link do YouTube em iframe)
        $htmlContent = $this->parseEmbeds($htmlContent);

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
        // 1. Segurança
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        // 2. Validação
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:Artigo,Diário,Nota,Atualização',
            'visibility' => 'required|in:public,followers,members',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // 3. Preparar dados
        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'visibility' => $validated['visibility'],
        ];

        // 4. Processar Tags (String -> Array)
        if (isset($request->tags)) {
            $tagsArray = array_map('trim', explode(',', $request->tags));
            $data['tags'] = array_filter($tagsArray); // remove vazios
        } else {
            $data['tags'] = null; // Se limpar o campo, limpa no banco
        }

        // 5. Processar Imagem (Se enviou uma nova)
        if ($request->hasFile('image')) {
            // (Opcional) Deletar imagem antiga para não encher o servidor
            if ($post->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($post->image);
            }
            
            $data['image'] = $request->file('image')->store('posts/covers', 'public');
        }

        // 6. Atualizar
        $post->update($data);

        // Redireciona para a leitura do artigo atualizado
        return redirect()->route('post.show', $post->slug)->with('success', 'Artigo atualizado!');
    }

    /**
     * Curtir / Descurtir (Toggle)
     */
    public function toggleLike(Post $post)
    {
        // O método toggle adiciona se não existir, e remove se existir.
        $post->likes()->toggle(auth()->id());

        if ($post->is_liked && $post->user_id !== auth()->id()) {
            $post->user->notify(new NewInteraction(auth()->user(), $post, 'like'));
        }

        return back(); // Recarrega a página
    }


    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);

        // Regra: Só pode apagar se for dono do comentário OU dono do post
        if (auth()->id() !== $comment->user_id && auth()->id() !== $comment->post->user_id) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comentário removido.');
    }

    /**
     * Função Auxiliar para Embeds (YouTube)
     */
    private function parseEmbeds($content)
    {
        // Regex simples para YouTube (Links completos ou curtos)
        $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        
        return preg_replace($pattern, '<div class="aspect-w-16 aspect-h-9 my-4"><iframe src="https://www.youtube.com/embed/$1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-96 rounded-lg"></iframe></div>', $content);
    }

}