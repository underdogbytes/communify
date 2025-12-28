<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fila de Moderação') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded mb-6 border border-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-gavel text-indigo-600"></i>
                        Posts Aguardando Aprovação ({{ $pendingPosts->count() }})
                    </h3>

                    @forelse($pendingPosts as $post)
                        <div class="border border-gray-200 rounded-lg p-6 mb-6 bg-gray-50">
                            <div class="flex items-center gap-3 mb-4">
                                <img src="{{ $post->user->avatar_url }}" class="w-10 h-10 rounded-full bg-white border">
                                <div>
                                    <p class="font-bold text-gray-800">{{ $post->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $post->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <span class="ml-auto bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded">Pendente</span>
                            </div>

                            <div class="bg-white p-4 rounded border border-gray-100 mb-4 prose max-w-none text-sm">
                                @if($post->title)
                                    <h4 class="font-bold text-lg mb-2">{{ $post->title }}</h4>
                                @endif
                                
                                {!! \Illuminate\Support\Str::markdown($post->content) !!}
                                
                                @if($post->image)
                                    <div class="mt-3">
                                        <img src="{{ asset('storage/'.$post->image) }}" class="h-32 rounded border">
                                        <span class="text-xs text-gray-400 block mt-1">Imagem anexada</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex gap-3 justify-end">
                                <form action="{{ route('creator.posts.reject', $post->id) }}" method="POST" onsubmit="return confirm('Rejeitar e excluir este post?');">
                                    @csrf @method('DELETE')
                                    <button class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded hover:bg-red-50 font-bold text-sm transition">
                                        Rejeitar ✕
                                    </button>
                                </form>

                                <form action="{{ route('creator.posts.approve', $post->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-bold text-sm shadow transition">
                                        Aprovar Post ✓
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            <i class="fa-solid fa-check-circle text-4xl text-green-200 mb-3"></i>
                            <p>Tudo limpo! Nenhum post pendente.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>