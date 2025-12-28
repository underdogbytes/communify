<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                <div class="px-8 pb-8">
                    <div class="relative flex justify-between items-end -mt-12 mb-6">
                        <img src="{{ $user->avatar_url }}" class="w-32 h-32 rounded-full border-4 border-white shadow-md bg-white">
                        
                        @auth
                            @if(auth()->id() === $user->id)
                                <a href="{{ route('profile.edit') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-bold text-sm hover:bg-gray-200 transition">
                                    <i class="fa-solid fa-pen mr-1"></i> Editar Perfil
                                </a>
                            @endif
                        @endauth
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-gray-500 text-sm mb-4">Membro desde {{ $user->created_at->format('M Y') }}</p>
                    
                    @if($user->bio)
                        <p class="text-gray-700 max-w-2xl leading-relaxed">{{ $user->bio }}</p>
                    @else
                        <p class="text-gray-400 italic">Este usuário ainda não escreveu uma bio.</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-crown text-yellow-500"></i> Comunidade Criada
                    </h3>
                    @if($user->community)
                        <a href="{{ route('community.show', $user->community->slug) }}" class="block bg-white rounded-lg shadow-sm hover:shadow-md transition overflow-hidden border border-gray-200">
                            <div class="h-24 bg-gray-200 relative">
                                @if($user->community->cover_image)
                                    <img src="{{ asset('storage/' . $user->community->cover_image) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="p-4 flex items-center gap-4">
                                <img src="{{ asset('storage/' . $user->community->profile_image) }}" class="w-12 h-12 rounded-full border border-gray-100 -mt-8 bg-white">
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $user->community->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $user->community->followers->count() }} seguidores</p>
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center border border-dashed border-gray-300 text-gray-500 text-sm">
                            Este usuário ainda não criou uma comunidade.
                        </div>
                    @endif
                </div>

                <div>
                    <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-heart text-red-500"></i> Seguindo ({{ $following->count() }})
                    </h3>
                    
                    @if($following->count() > 0)
                        <div class="space-y-3">
                            @foreach($following as $comm)
                                <a href="{{ route('community.show', $comm->slug) }}" class="flex items-center gap-3 bg-white p-3 rounded-lg shadow-sm border border-gray-100 hover:border-indigo-300 transition">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                                        @if($comm->profile_image)
                                            <img src="{{ asset('storage/' . $comm->profile_image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center font-bold text-gray-400 text-xs">{{ substr($comm->name, 0, 1) }}</div>
                                        @endif
                                    </div>
                                    <div class="overflow-hidden">
                                        <h4 class="font-bold text-gray-900 text-sm truncate">{{ $comm->name }}</h4>
                                        <p class="text-xs text-gray-500 truncate">{{ $comm->category ?? 'Geral' }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center border border-dashed border-gray-300 text-gray-500 text-sm">
                            Não segue nenhuma comunidade ainda.
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-app-layout>