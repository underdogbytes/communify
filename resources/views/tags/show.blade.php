<x-app-layout>
    <div class="bg-indigo-600 py-12 text-center text-white mb-8">
        <h1 class="text-4xl font-extrabold tracking-tight">
            #{{ $tag }}
        </h1>
        <p class="mt-2 text-indigo-100">
            Explorando todo o conteúdo sobre este tópico.
        </p>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if($communities->count() > 0)
            <div class="mb-12">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-users"></i> Comunidades Relacionadas
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($communities as $community)
                        <a href="{{ route('community.show', $community->slug) }}" class="flex items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                            <img src="{{ $community->profile_image ? asset('storage/'.$community->profile_image) : asset('images/default-avatar.png') }}" class="w-16 h-16 rounded-full object-cover">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $community->name }}</h3>
                                <p class="text-xs text-gray-500">{{ $community->followers_count ?? 0 }} seguidores</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-layer-group"></i> Publicações Recentes
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($posts as $post)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col h-full">
                        @if($post->image)
                            <a href="{{ route('post.show', $post->slug) }}" class="h-48 overflow-hidden block">
                                <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-full object-cover hover:scale-105 transition duration-500">
                            </a>
                        @endif
                        
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                                <img src="{{ $post->community->profile_image ? asset('storage/'.$post->community->profile_image) : '' }}" class="w-5 h-5 rounded-full">
                                <span>{{ $post->community->name }}</span>
                                <span>•</span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>

                            <a href="{{ route('post.show', $post->slug) }}" class="block mb-3">
                                <h3 class="text-lg font-bold text-gray-900 hover:text-indigo-600 transition leading-tight">
                                    {{ $post->title ?? Str::limit($post->content, 50) }}
                                </h3>
                            </a>
                            
                            @if($post->type === 'article')
                                <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-1">
                                    {{ Str::limit(strip_tags(Str::markdown($post->content)), 120) }}
                                </p>
                            @endif

                            <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between text-sm text-gray-500">
                                <div class="flex gap-4">
                                    <span><i class="fa-regular fa-heart"></i> {{ $post->likes_count ?? 0 }}</span>
                                    <span><i class="fa-regular fa-comment"></i> {{ $post->comments_count ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-20 text-gray-500">
                        <i class="fa-solid fa-hashtag text-4xl mb-4 text-gray-300"></i>
                        <p>Ainda não há posts com a tag <strong>#{{ $tag }}</strong>.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>