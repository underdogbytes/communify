<x-app-layout>
    <div class="bg-indigo-700 py-16 text-center text-white mb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold tracking-tight mb-4">
                Encontre sua tribo.
            </h1>
            <p class="text-xl text-indigo-100 max-w-2xl mx-auto mb-8">
                Descubra comunidades incríveis, aprenda com criadores e conecte-se com pessoas que amam o que você ama.
            </p>

            <div class="max-w-xl mx-auto">
                <form action="{{ route('community.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-full rounded-full border-none px-6 py-4 text-gray-900 shadow-lg focus:ring-4 focus:ring-indigo-400 focus:outline-none text-lg" 
                           placeholder="Buscar por tópicos, nomes ou criadores...">
                    <button type="submit" class="absolute right-2 top-2 bg-indigo-600 text-white p-2.5 rounded-full hover:bg-indigo-800 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
            </div>

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('community.index') }}" 
                   class="px-4 py-2 rounded-full text-sm font-bold transition border 
                   {{ !request('category') ? 'bg-white text-indigo-600 border-white' : 'bg-indigo-800 text-indigo-200 border-indigo-600 hover:bg-indigo-700' }}">
                   Todos
                </a>

                @foreach(\App\Models\Community::CATEGORIES as $cat)
                    <a href="{{ route('community.index', ['category' => $cat, 'search' => request('search')]) }}" 
                       class="px-4 py-2 rounded-full text-sm font-bold transition border 
                       {{ request('category') === $cat ? 'bg-white text-indigo-600 border-white' : 'bg-indigo-800 text-indigo-200 border-indigo-600 hover:bg-indigo-700' }}">
                       {{ $cat }}
                    </a>
                @endforeach
            </div>
            
        </div>
    </div>

    <div class="pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    @if(request('search'))
                        Resultados para "{{ request('search') }}"
                    @else
                        Comunidades em Destaque
                    @endif
                </h2>
                @if(request('search'))
                    <a href="{{ route('community.index') }}" class="text-indigo-600 hover:text-indigo-800 font-bold text-sm">Limpar busca</a>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($communities as $community)
                    <a href="{{ route('community.show', $community->slug) }}" class="block group h-full">
                        <div class="bg-white overflow-hidden shadow-sm hover:shadow-xl transition rounded-2xl h-full flex flex-col border border-gray-100 relative group-hover:-translate-y-1 duration-300">
                            
                            <div class="h-40 bg-gray-200 relative overflow-hidden">
                                @if($community->cover_image)
                                    <img src="{{ asset('storage/' . $community->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                                @else
                                    <div class="w-full h-full bg-gradient-to-r from-gray-200 to-gray-300"></div>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-10 group-hover:bg-opacity-0 transition"></div>
                            </div>

                            <div class="absolute top-28 left-6">
                                <div class="w-20 h-20 rounded-full border-4 border-white overflow-hidden bg-white shadow-md">
                                    @if($community->profile_image)
                                        <img src="{{ asset('storage/' . $community->profile_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-indigo-50 flex items-center justify-center font-bold text-indigo-300 text-2xl">
                                            {{ substr($community->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-12 px-6 pb-6 flex-1 flex flex-col">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition mb-1">{{ $community->name }}</h3>
                                </div>
                                
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-3">
                                    Por {{ $community->user->name }}
                                </p>

                                <p class="text-gray-600 text-sm mb-6 line-clamp-3 flex-1">
                                    {{ $community->description }}
                                </p>

                                <div class="border-t border-gray-100 pt-4 flex items-center justify-between text-sm">
                                    <div class="flex items-center text-gray-500">
                                        <i class="fa-solid fa-users mr-2 text-gray-400"></i>
                                        <span class="font-bold text-gray-700">{{ $community->followers_count }}</span>
                                        <span class="ml-1">seguidores</span>
                                    </div>
                                    <span class="text-indigo-600 font-bold group-hover:underline">Visitar</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-1 md:col-span-3 py-20 text-center">
                        <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                            <i class="fa-solid fa-magnifying-glass text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Nenhuma comunidade encontrada</h3>
                        <p class="text-gray-500 mt-2">Tente buscar por outro termo ou veja todas as comunidades.</p>
                        @if(request('search'))
                            <a href="{{ route('community.index') }}" class="mt-6 inline-block bg-indigo-600 text-white px-6 py-2 rounded-full font-bold hover:bg-indigo-700 transition">
                                Ver todas
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $communities->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</x-app-layout>