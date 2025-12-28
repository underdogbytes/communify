<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $community->name }} - Communify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Personalização Dinâmica */
        .accent-bg { background-color: {{ $community->accent_color ?? '#4F46E5' }}; }
        .accent-text { color: {{ $community->accent_color ?? '#4F46E5' }}; }
        .accent-border { border-color: {{ $community->accent_color ?? '#4F46E5' }}; }
        .accent-hover:hover { background-color: {{ $community->accent_color ?? '#4F46E5' }}; color: white; }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-indigo-600">Communify.</a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        @php
                            $cartCount = auth()->user()->orders()->where('status', 'draft')->first()?->items->count() ?? 0;
                        @endphp
                        <a href="{{ route('order.cart') }}" class="relative text-gray-600 hover:text-indigo-600 mr-4">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            @if($cartCount > 0)
                                <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $cartCount }}</span>
                            @endif
                        </a>
                        
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm text-gray-700 hover:text-indigo-600">
                            <img src="{{ auth()->user()->avatar_url }}" class="h-8 w-8 rounded-full border border-gray-200 object-cover">
                            <span>Painel</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-indigo-600">Entrar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-white shadow-md pb-6">
        <div class="h-48 md:h-64 w-full bg-gray-300 relative overflow-hidden">
            @if($community->cover_image)
                <img src="{{ asset('storage/' . $community->cover_image) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-r from-indigo-500 to-purple-600"></div>
            @endif
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="flex flex-col md:flex-row items-start md:items-end -mt-12 mb-4">
                <div class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white bg-white overflow-hidden shadow-lg z-10">
                    @if($community->profile_image)
                        <img src="{{ asset('storage/' . $community->profile_image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-400">
                            {{ substr($community->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                
                <div class="mt-4 md:mt-0 md:ml-6 flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 leading-tight">{{ $community->name }}</h1>
                    <p class="text-gray-500 text-sm">Criado por {{ $community->user->name }} • {{ $community->followers->count() }} seguidores</p>
                    
                    <div class="flex gap-3 mt-2 text-gray-400">
                        @if($community->instagram_handle)
                            <a href="https://instagram.com/{{ $community->instagram_handle }}" target="_blank" class="hover:text-pink-600"><i class="fa-brands fa-instagram"></i></a>
                        @endif
                        @if($community->youtube_handle)
                            <a href="https://youtube.com/{{ $community->youtube_handle }}" target="_blank" class="hover:text-red-600"><i class="fa-brands fa-youtube"></i></a>
                        @endif
                        @if($community->whatsapp_group)
                            <a href="{{ $community->whatsapp_group }}" target="_blank" class="hover:text-green-500"><i class="fa-brands fa-whatsapp"></i></a>
                        @endif
                    </div>
                </div>

                <div class="mt-4 md:mt-0">
                    @auth
                        @if(auth()->id() !== $community->user_id)
                            <form action="{{ route('community.follow', $community->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-2 rounded-full font-bold shadow-sm transition
                                    {{ auth()->user()->follows->contains($community->id) 
                                        ? 'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' 
                                        : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
                                    {{ auth()->user()->follows->contains($community->id) ? 'Seguindo' : 'Seguir Comunidade' }}
                                </button>
                            </form>
                        @else
                            <div class="flex flex-col md:flex-row gap-2 items-center">
                                <a href="{{ route('creator.dashboard') }}" class="px-6 py-2 bg-gray-100 text-gray-700 font-bold rounded-full border border-gray-300 hover:bg-gray-200">
                                    Painel
                                </a>
                                <a href="{{ route('creator.community.edit') }}" class="px-6 py-2 bg-indigo-50 text-indigo-700 font-bold rounded-full border border-indigo-200 hover:bg-indigo-100 flex items-center gap-2">
                                    <i class="fa-solid fa-gear"></i> Editar
                                </a>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-full hover:bg-indigo-700">Seguir</a>
                    @endauth
                </div>
            </div>

            <div class="mt-6 max-w-3xl">
                <p class="text-gray-700 leading-relaxed">{{ $community->description }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ tab: 'feed' }">
        
        <div class="flex border-b border-gray-200 mb-8">
            <button @click="tab = 'feed'" 
                    :class="tab === 'feed' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-6 border-b-2 font-medium text-lg focus:outline-none transition">
                Feed
            </button>
            <button @click="tab = 'store'" 
                    :class="tab === 'store' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-6 border-b-2 font-medium text-lg focus:outline-none transition flex items-center">
                Loja 
                <span class="ml-2 bg-purple-100 text-purple-700 py-0.5 px-2 rounded-full text-xs">{{ $community->products->count() }}</span>
            </button>
        </div>

        <div x-show="tab === 'feed'" class="max-w-3xl">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @auth
                @if(Auth::id() === $community->user_id || auth()->user()->follows->contains($community->id))
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full object-cover border border-gray-200" 
                                     src="{{ Auth::user()->avatar_url }}" 
                                     alt="{{ Auth::user()->name }}">
                            </div>

                            <div class="flex-1">
                                <form action="{{ route('community.posts.store', $community) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="short"> 
                                    
                                    <textarea 
                                        name="content" 
                                        rows="2" 
                                        class="w-full border-none focus:ring-0 text-lg placeholder-gray-400 resize-none p-0 focus:outline-none"
                                        placeholder="O que está acontecendo? {{ Auth::id() !== $community->user_id ? '(Sujeito a aprovação)' : '' }}"></textarea>
                                    
                                    <div class="mt-3 flex items-center justify-between border-t border-gray-100 pt-3">
                                        <div class="flex gap-4 text-gray-400 text-sm">
                                            <a href="{{ route('community.posts.create', $community) }}" class="hover:text-indigo-600 flex items-center gap-1 transition">
                                                <i class="fa-regular fa-newspaper"></i> <span>Escrever Artigo Longo</span>
                                            </a>
                                        </div>
                                        
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-1.5 rounded-full font-bold text-sm transition">
                                            Postar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @elseif(!auth()->user()->follows->contains($community->id))
                    <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 mb-6 text-center text-indigo-700">
                        <p class="font-bold">Quer participar da conversa?</p>
                        <p class="text-sm mb-3">Siga a comunidade para poder criar posts e interagir.</p>
                        <form action="{{ route('community.follow', $community->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs bg-indigo-600 text-white px-4 py-1 rounded-full font-bold hover:bg-indigo-700 transition">
                                Seguir Agora
                            </button>
                        </form>
                    </div>
                @endif
            @endauth

            @forelse($community->posts as $post)
                <div class="bg-white rounded-lg shadow mb-6 overflow-hidden border border-gray-100" x-data="{ showComment: false }">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <img class="h-10 w-10 rounded-full object-cover border border-gray-200" 
                                     src="{{ $post->user->avatar_url }}" 
                                     alt="{{ $post->user->name }}">
                                <div class="ml-3">
                                    <div class="text-sm font-bold text-gray-900">{{ $post->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</div>
                                </div>
                            </div>

                            @if(auth()->id() === $post->user_id)
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>

                                    <div x-show="open" @click.away="open = false" 
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 border border-gray-100"
                                         style="display: none;">
                                        
                                        @if($post->type === 'article')
                                            <a href="{{ route('posts.edit', $post->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fa-solid fa-pen mr-2"></i> Editar Artigo
                                            </a>
                                        @endif

                                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja apagar?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                <i class="fa-solid fa-trash mr-2"></i> Excluir
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        @if($post->type === 'article')
                            <div class="mb-4">
                                <a href="{{ route('post.show', $post->slug) }}" class="block group">
                                    @if($post->image)
                                        <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-48 object-cover rounded-lg mb-3">
                                    @endif
                                    <h2 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition">
                                        {{ $post->title }}
                                    </h2>
                                    <p class="text-gray-600 leading-relaxed mb-4">
                                        {{ \Illuminate\Support\Str::limit(strip_tags(\Illuminate\Support\Str::markdown($post->content)), 150) }}
                                    </p>
                                    <div class="text-indigo-600 font-bold text-sm flex items-center gap-1">
                                        Ler artigo completo <span class="group-hover:translate-x-1 transition">&rarr;</span>
                                    </div>
                                </a>
                            </div>
                        @else
                            <div class="text-gray-800 whitespace-pre-line mb-4 text-lg">
                                {{ $post->content }}
                            </div>
                        @endif

                        <div class="flex items-center justify-between text-gray-500 text-sm mb-4 border-t border-gray-100 pt-4">
                            <div class="flex gap-6">
                                <form action="{{ route('post.like', $post->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-1 transition hover:text-red-500 {{ $post->is_liked ? 'text-red-500 font-bold' : '' }}">
                                        @if($post->is_liked)
                                            <i class="fa-solid fa-heart"></i>
                                        @else
                                            <i class="fa-regular fa-heart"></i>
                                        @endif
                                        <span>{{ $post->likes->count() }}</span>
                                    </button>
                                </form>

                                <button @click="showComment = !showComment" class="flex items-center gap-1 hover:text-indigo-600 cursor-pointer transition">
                                    <i class="fa-regular fa-comment"></i> 
                                    <span>{{ $post->comments->count() }}</span>
                                </button>
                            </div>

                            @if($post->type === 'article')
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">
                                    <i class="fa-regular fa-file-lines mr-1"></i> Artigo
                                </span>
                            @endif
                        </div>

                        <div x-show="showComment" x-transition class="mt-4 pt-4 border-t border-gray-50 bg-gray-50 -mx-6 -mb-6 px-6 pb-6">
                            
                            @if($post->comments->count() > 0)
                                <div class="space-y-3 mb-4">
                                    @foreach($post->comments->take(3) as $comment)
                                        <div class="flex gap-2 items-start text-sm">
                                            <img src="{{ $comment->user->avatar_url }}" class="w-6 h-6 rounded-full mt-0.5">
                                            <div class="bg-white px-3 py-2 rounded-lg border border-gray-100 shadow-sm flex-1">
                                                <span class="font-bold text-gray-900 block">{{ $comment->user->name }}</span>
                                                <span class="text-gray-700">{{ $comment->content }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($post->comments->count() > 3)
                                        <a href="{{ $post->type == 'article' ? route('post.show', $post->slug) : '#' }}" class="block text-xs text-center text-indigo-500 hover:underline mt-2">
                                            Ver todos os {{ $post->comments->count() }} comentários
                                        </a>
                                    @endif
                                </div>
                            @endif

                            @auth
                                <form action="{{ route('post.comment.store', $post->id) }}" method="POST" class="flex gap-2 items-center">
                                    @csrf
                                    <img src="{{ auth()->user()->avatar_url }}" class="h-8 w-8 rounded-full border border-gray-200">
                                    <input type="text" name="content" placeholder="Escreva uma resposta..." class="flex-1 rounded-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-800 font-bold px-2">
                                        <i class="fa-solid fa-paper-plane"></i>
                                    </button>
                                </form>
                            @else
                                <p class="text-xs text-center text-gray-500">Faça login para comentar.</p>
                            @endauth
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-white rounded-lg border border-gray-200 border-dashed">
                    <p class="text-gray-500">Nenhum post publicado ainda.</p>
                </div>
            @endforelse
        </div>

        <div x-show="tab === 'store'" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($community->products as $product)
                    <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition group border border-gray-100">
                        <div class="h-64 overflow-hidden relative">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">Sem Imagem</div>
                            @endif

                            <div class="absolute top-2 right-2 px-2 py-1 text-xs font-bold rounded shadow {{ $product->type === 'digital' ? 'bg-purple-100 text-purple-700' : 'bg-white text-gray-700' }}">
                                {{ $product->type === 'digital' ? '⚡ Digital' : ($product->baseProduct->name ?? 'Físico') }}
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="text-xs text-indigo-500 font-bold uppercase mb-1">
                                {{ $product->baseProduct->name ?? 'Produto Digital' }}
                            </div>
                            
                            <h3 class="text-lg font-bold text-gray-900 mb-2 truncate">{{ $product->name }}</h3>
                            
                            <div class="flex items-center justify-between mt-4">
                                <span class="text-xl font-bold text-gray-900">
                                    R$ {{ number_format($product->total_price, 2, ',', '.') }}
                                </span>
                                
                                <a href="{{ route('product.show', $product->slug) }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded hover:bg-indigo-700 transition">
                                    Ver Detalhes
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-10 bg-white rounded-lg border border-gray-200 border-dashed">
                        <p class="text-gray-500">A loja ainda está vazia.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</body>
</html>