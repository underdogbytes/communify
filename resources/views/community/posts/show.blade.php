<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->title }} - {{ $post->community->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilos básicos para o HTML gerado pelo Markdown */
        .prose h1 { font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; line-height: 1.2; }
        .prose h2 { font-size: 1.8rem; font-weight: 700; margin-top: 2rem; margin-bottom: 1rem; }
        .prose p { margin-bottom: 1.5rem; line-height: 1.8; color: #374151; font-size: 1.125rem; }
        .prose ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.5rem; }
        .prose blockquote { border-left: 4px solid #e5e7eb; padding-left: 1rem; font-style: italic; color: #6b7280; }
        .prose img { border-radius: 0.5rem; margin: 2rem 0; width: 100%; }
        .prose a { color: #4f46e5; text-decoration: underline; }
    </style>
</head>
<body class="bg-white text-gray-900 font-sans antialiased">

    <nav class="border-b border-gray-100 sticky top-0 bg-white/95 backdrop-blur z-50">
        <div class="max-w-3xl mx-auto px-4 h-16 flex justify-between items-center">
            <a href="{{ route('community.show', $post->community->slug) }}" class="flex items-center gap-3 hover:opacity-75 transition">
                @if($post->community->profile_image)
                    <img src="{{ asset('storage/' . $post->community->profile_image) }}" class="w-8 h-8 rounded-full">
                @else
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-600">
                        {{ substr($post->community->name, 0, 1) }}
                    </div>
                @endif
                <span class="font-bold text-gray-900">{{ $post->community->name }}</span>
            </a>

            @auth
                @if(auth()->id() === $post->user_id)
                    <span class="text-xs text-gray-400 uppercase font-bold">Modo Autor</span>
                @else
                    @endif
            @else
                <a href="{{ route('login') }}" class="text-indigo-600 font-bold text-sm">Entrar</a>
            @endauth
        </div>
    </nav>

    <article class="max-w-3xl mx-auto px-4 py-12">
        <header class="mb-10 text-center">
            <div class="text-sm text-gray-500 mb-4">{{ $post->created_at->format('d de M, Y') }} • Leitura de 5 min</div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight mb-6">
                {{ $post->title }}
            </h1>
            
            <div class="flex items-center justify-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500">
                    {{ substr($post->user->name, 0, 1) }}
                </div>
                <div class="text-left">
                    <div class="font-bold text-gray-900">{{ $post->user->name }}</div>
                    <div class="text-xs text-gray-500">Autor</div>
                </div>
            </div>
        </header>

        <div class="prose">
            {!! $htmlContent !!}
        </div>

        <div class="mt-16 pt-8 border-t border-gray-100">
            <h3 class="font-bold text-gray-900 mb-6">Comentários ({{ $post->comments->count() }})</h3>
            
            <div class="space-y-6">
                @foreach($post->comments as $comment)
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex-shrink-0 flex items-center justify-center font-bold text-xs text-gray-500">
                            {{ substr($comment->user->name, 0, 1) }}
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg flex-1">
                            <div class="font-bold text-sm text-gray-900 mb-1">{{ $comment->user->name }}</div>
                            <div class="text-gray-700 leading-relaxed">{{ $comment->content }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            @auth
                <form action="{{ route('post.comment.store', $post->id) }}" method="POST" class="mt-8">
                    @csrf
                    <textarea name="content" rows="3" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Deixe seu comentário..."></textarea>
                    <button type="submit" class="mt-2 bg-indigo-600 text-white px-6 py-2 rounded-full font-bold text-sm hover:bg-indigo-700 transition">
                        Comentar
                    </button>
                </form>
            @endauth
        </div>
    </article>

</body>
</html>