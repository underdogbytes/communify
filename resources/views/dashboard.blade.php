<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meu Feed') }}
        </h2>
    </x-slot>

    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>[x-cloak] { display: none !important; }</style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-2 space-y-6">
                    
                    @forelse($posts as $post)
                        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100" x-data="{ showComment: false }">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border border-gray-200">
                                            @if($post->community->profile_image)
                                                <img src="{{ asset('storage/' . $post->community->profile_image) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-gray-500 font-bold text-xs">{{ substr($post->community->name, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('community.show', $post->community->slug) }}" class="text-sm font-bold text-gray-900 hover:underline">
                                                    {{ $post->community->name }}
                                                </a>
                                                <span class="text-gray-300">•</span>
                                                <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                Por {{ $post->user->name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                @if($post->type === 'article')
                                    <div class="mb-4">
                                        <a href="{{ route('post.show', $post->slug) }}" class="block group">
                                            @if($post->image)
                                                <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-48 object-cover rounded-lg mb-3 border border-gray-100">
                                            @endif
                                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition">
                                                {{ $post->title }}
                                            </h3>
                                            <p class="text-gray-600 leading-relaxed mb-4 text-sm">
                                                {{ \Illuminate\Support\Str::limit(strip_tags(\Illuminate\Support\Str::markdown($post->content)), 140) }}
                                            </p>
                                            <div class="text-indigo-600 font-bold text-xs flex items-center gap-1 uppercase tracking-wide">
                                                Ler artigo <span class="group-hover:translate-x-1 transition">&rarr;</span>
                                            </div>
                                        </a>
                                    </div>
                                @else
                                    <div class="text-gray-800 whitespace-pre-line mb-4 text-base">
                                        {{ $post->content }}
                                    </div>
                                @endif

                                <div class="flex items-center justify-between text-gray-500 text-sm mb-4 border-t border-gray-100 pt-4">
                                    <div class="flex gap-6">
                                        <form action="{{ route('post.like', $post->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-1 transition hover:text-red-500 {{ $post->is_liked ? 'text-red-500 font-bold' : '' }}">
                                                <i class="{{ $post->is_liked ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                                                <span>{{ $post->likes->count() }}</span>
                                            </button>
                                        </form>

                                        <button @click="showComment = !showComment" class="flex items-center gap-1 hover:text-indigo-600 transition">
                                            <i class="fa-regular fa-comment"></i> 
                                            <span>{{ $post->comments->count() }}</span>
                                        </button>
                                    </div>
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
                                        </div>
                                    @endif

                                    <form action="{{ route('post.comment.store', $post->id) }}" method="POST" class="flex gap-2 items-center">
                                        @csrf
                                        <img src="{{ auth()->user()->avatar_url }}" class="h-8 w-8 rounded-full border border-gray-200">
                                        <input type="text" name="content" placeholder="Responder..." class="flex-1 rounded-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                        <button type="submit" class="text-indigo-600 font-bold text-sm px-2 hover:text-indigo-800">
                                            <i class="fa-solid fa-paper-plane"></i>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 text-center border border-gray-200 border-dashed">
                            <div class="mb-4">
                                <div class="mx-auto h-12 w-12 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-rss text-xl"></i>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Seu feed está vazio</h3>
                            <p class="mt-1 text-gray-500 mb-6">Siga comunidades para ver os posts aqui.</p>
                            <a href="{{ route('community.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-full text-white bg-indigo-600 hover:bg-indigo-700">
                                Explorar Comunidades
                            </a>
                        </div>
                    @endforelse

                </div>

                <div class="space-y-6">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Área do Criador</h3>
                        
                        @if(auth()->user()->community)
                            <div class="text-center">
                                <div class="mx-auto h-16 w-16 rounded-full p-1 border-2 border-indigo-100 mb-3">
                                    @if(auth()->user()->community->profile_image)
                                        <img src="{{ asset('storage/' . auth()->user()->community->profile_image) }}" class="rounded-full w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-indigo-50 rounded-full flex items-center justify-center text-indigo-500 font-bold text-xl">
                                            {{ substr(auth()->user()->community->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <h4 class="font-bold text-gray-900">{{ auth()->user()->community->name }}</h4>
                                <p class="text-xs text-gray-500 mb-4">{{ auth()->user()->community->followers->count() }} seguidores</p>
                                
                                <a href="{{ route('creator.dashboard') }}" class="block w-full text-center px-4 py-2 text-sm font-bold rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition">
                                    Acessar Painel
                                </a>
                            </div>
                        @else
                            <div class="text-center">
                                <p class="text-sm text-gray-600 mb-4 leading-relaxed">Você ainda não tem uma comunidade. Crie a sua e comece a monetizar!</p>
                                <a href="{{ route('creator.community.create') }}" class="block w-full text-center px-4 py-2 text-sm font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition">
                                    Criar Comunidade
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Menu</h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ route('order.index') }}" class="flex items-center text-gray-600 hover:text-indigo-600 transition text-sm font-medium">
                                    <i class="fa-solid fa-bag-shopping w-6 text-center"></i>
                                    Meus Pedidos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile.edit') }}" class="flex items-center text-gray-600 hover:text-indigo-600 transition text-sm font-medium">
                                    <i class="fa-solid fa-user-gear w-6 text-center"></i>
                                    Editar Perfil
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('community.index') }}" class="flex items-center text-gray-600 hover:text-indigo-600 transition text-sm font-medium">
                                    <i class="fa-solid fa-compass w-6 text-center"></i>
                                    Explorar
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>