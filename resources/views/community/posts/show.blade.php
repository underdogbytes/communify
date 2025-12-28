<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{ $post->title }} - {{ $post->community->name }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($htmlContent), 150) }}">
    
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($htmlContent), 150) }}">
    <meta property="og:image" content="{{ $post->image ? asset('storage/' . $post->image) : asset('images/default-share.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->title }}">
    <meta name="twitter:image" content="{{ $post->image ? asset('storage/' . $post->image) : asset('images/default-share.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Ajuste para embeds do YouTube ficarem responsivos */
        .prose { max-width: 65ch; margin: 0 auto; color: #374151; line-height: 1.75; }
        .prose h1 { font-size: 2.25em; font-weight: 800; margin-top: 0; margin-bottom: 0.8em; color: #111827; }
        .prose h2 { font-size: 1.5em; font-weight: 700; margin-top: 1.5em; margin-bottom: 0.8em; color: #111827; }
        .prose p { margin-bottom: 1.25em; font-size: 1.125rem; }
        .prose ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 1.25em; }
        .prose blockquote { border-left: 4px solid #e5e7eb; padding-left: 1em; font-style: italic; color: #4b5563; }
        .prose img { border-radius: 0.5rem; margin-top: 2em; margin-bottom: 2em; width: 100%; }
        .prose a { color: #4f46e5; text-decoration: underline; }
        .prose iframe { width: 100%; aspect-ratio: 16/9; margin: 2rem 0; border-radius: 0.5rem; }
    </style>
</head>
<body class="bg-white text-gray-900 font-sans antialiased">

    <nav class="border-b border-gray-100 py-4 sticky top-0 bg-white/90 backdrop-blur-md z-50">
        <div class="max-w-4xl mx-auto px-4 flex justify-between items-center">
            <a href="{{ route('community.show', $post->community->slug) }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2 transition font-medium">
                <i class="fa-solid fa-arrow-left"></i> 
                {{ $post->community->name }}
            </a>
            
            <div class="flex items-center gap-4">
                @auth
                    @if(auth()->id() === $post->user_id)
                        <a href="{{ route('posts.edit', $post->id) }}" class="text-sm text-gray-500 hover:text-indigo-600 underline">Editar Artigo</a>
                    @endif
                @endauth
                
                <a href="{{ route('user.public', $post->user->id) }}">
                    <img src="{{ $post->user->avatar_url }}" class="h-8 w-8 rounded-full border border-gray-200" title="{{ $post->user->name }}">
                </a>
            </div>
        </div>
    </nav>

    <article class="max-w-4xl mx-auto px-4 py-10">
        
        <header class="mb-10 text-center max-w-2xl mx-auto">
            @if($post->category)
                <span class="inline-block bg-indigo-50 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4">
                    {{ $post->category }}
                </span>
            @endif

            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                {{ $post->title }}
            </h1>

            <div class="flex items-center justify-center gap-4 text-gray-500 text-sm">
                <span>Por <a href="{{ route('user.public', $post->user->id) }}" class="font-bold text-gray-800 hover:underline">{{ $post->user->name }}</a></span>
                <span>•</span>
                <span>{{ $post->created_at->format('d/m/Y') }}</span>
                <span>•</span>
                <span>{{ ceil(str_word_count(strip_tags($htmlContent)) / 200) }} min de leitura</span>
            </div>
        </header>

        @if($post->image)
            <figure class="mb-12">
                <img src="{{ asset('storage/' . $post->image) }}" 
                     class="w-full h-auto max-h-[500px] object-cover rounded-2xl shadow-lg" 
                     alt="{{ $post->title }}">
            </figure>
        @endif

        <div class="prose">
            {!! $htmlContent !!}
        </div>

        @if(!empty($post->tags))
            <div class="max-w-2xl mx-auto mt-12 pt-6 border-t border-gray-100">
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('tags.show', trim($tag)) }}" class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-sm hover:bg-indigo-100 hover:text-indigo-700 transition font-medium">
                            #{{ trim($tag) }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </article>

    <div class="max-w-3xl mx-auto px-4 pb-20">
        
        <hr class="border-gray-100 mb-10">

        <div class="flex justify-center mb-12">
            <form action="{{ route('post.like', $post->id) }}" method="POST">
                @csrf
                <button class="flex flex-col items-center group transition">
                    <div class="p-4 rounded-full shadow-lg border border-gray-100 transition transform group-hover:scale-110 {{ $post->is_liked ? 'bg-red-50 text-red-500 border-red-200' : 'bg-white text-gray-400 hover:text-red-500' }}">
                        @if($post->is_liked)
                            <i class="fa-solid fa-heart text-3xl"></i>
                        @else
                            <i class="fa-regular fa-heart text-3xl"></i>
                        @endif
                    </div>
                    <span class="mt-2 text-sm font-bold text-gray-500 group-hover:text-red-500 transition">
                        {{ $post->likes->count() }} curtidas
                    </span>
                </button>
            </form>
        </div>

        <div class="bg-gray-50 rounded-2xl p-6 md:p-10">
            <h3 class="font-bold text-xl text-gray-900 mb-6 flex items-center gap-2">
                <i class="fa-regular fa-comments"></i> Discussão ({{ $post->comments->count() }})
            </h3>

            @auth
                <form action="{{ route('post.comment.store', $post->id) }}" method="POST" class="mb-10">
                    @csrf
                    <div class="flex items-start gap-4">
                        <img src="{{ auth()->user()->avatar_url }}" class="h-10 w-10 rounded-full border border-gray-200">
                        <div class="flex-1">
                            <textarea name="content" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Escreva seu comentário..." required></textarea>
                            <div class="mt-2 flex justify-end">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold">
                                    Enviar Comentário
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <p class="text-center text-gray-500 mb-8">
                    <a href="{{ route('login') }}" class="text-indigo-600 font-bold underline">Faça login</a> para participar da discussão.
                </p>
            @endauth

            <div class="space-y-8">
                @forelse($post->comments as $comment)
                    <div class="flex gap-4">
                        <a href="{{ route('user.public', $comment->user->id) }}">
                            <img src="{{ $comment->user->avatar_url }}" class="h-10 w-10 rounded-full border border-gray-200 bg-white">
                        </a>
                        
                        <div class="flex-1">
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                                <div class="flex justify-between items-start mb-2">
                                    <a href="{{ route('user.public', $comment->user->id) }}" class="font-bold text-gray-900 hover:underline">
                                        {{ $comment->user->name }}
                                    </a>
                                    <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-700 leading-relaxed">{{ $comment->content }}</p>
                            </div>
                            
                            @if(auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                                <div class="mt-1 ml-2 text-xs text-gray-400 flex gap-3">
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este comentário?');">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="hover:text-red-600 transition">Excluir</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-400 py-4">
                        Seja o primeiro a comentar!
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</body>
</html>