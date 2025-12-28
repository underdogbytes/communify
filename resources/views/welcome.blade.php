<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Communify - Monetize sua Paixão</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <nav class="absolute w-full z-20 top-0 left-0 py-6">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div class="text-2xl font-extrabold text-white tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-layer-group"></i> Communify.
            </div>
            <div class="space-x-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-white bg-white/20 px-4 py-2 rounded-full hover:bg-white/30 transition backdrop-blur-sm">
                        Meu Painel &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-indigo-100 hover:text-white transition">Entrar</a>
                    <a href="{{ route('register') }}" class="ml-4 text-sm font-bold text-indigo-600 bg-white px-5 py-2.5 rounded-full hover:bg-indigo-50 shadow-lg transition transform hover:-translate-y-0.5">
                        Começar Grátis
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="hero-gradient relative overflow-hidden pt-32 pb-20 lg:pt-48 lg:pb-32">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-20 pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white blur-3xl mix-blend-overlay"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 rounded-full bg-purple-400 blur-3xl mix-blend-overlay"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-indigo-500/30 text-indigo-100 text-xs font-bold uppercase tracking-wider mb-6 border border-indigo-400/30 backdrop-blur-sm">
                🚀 A plataforma para criadores do futuro
            </span>
            <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight leading-tight mb-8">
                Transforme sua audiência <br/>
                em uma <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-500">comunidade real</span>.
            </h1>
            <p class="mt-4 text-xl text-indigo-100 max-w-2xl mx-auto mb-10 leading-relaxed">
                Crie seu espaço exclusivo, publique conteúdo premium, venda produtos digitais e físicos. Tudo em um único lugar, sem algoritmos te atrapalhando.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-4 text-lg font-bold rounded-full text-indigo-700 bg-white hover:bg-indigo-50 shadow-xl transition transform hover:-translate-y-1">
                    Criar minha Comunidade
                </a>
                <a href="{{ route('community.index') }}" class="px-8 py-4 text-lg font-bold rounded-full text-white border border-white/30 hover:bg-white/10 transition backdrop-blur-sm flex items-center justify-center gap-2">
                    <i class="fa-regular fa-compass"></i> Explorar
                </a>
            </div>

            <div class="mt-12 text-indigo-200 text-sm font-medium">
                Junte-se a criadores de tecnologia, educação, games e mais.
            </div>
        </div>
    </div>

    <div class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Tudo o que você precisa</h2>
                <p class="text-gray-500 mt-2">Esqueça a gambiarra de usar 5 ferramentas diferentes.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="text-center group">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-users-rays"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Comunidade Engajada</h3>
                    <p class="text-gray-500 leading-relaxed">Posts, discussões, likes e comentários em um feed livre de ruído e focado no seu conteúdo.</p>
                </div>

                <div class="text-center group">
                    <div class="w-16 h-16 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-shop"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Loja Integrada</h3>
                    <p class="text-gray-500 leading-relaxed">Venda produtos físicos (camisetas, canecas) ou digitais (ebooks, cursos) direto no seu perfil.</p>
                </div>

                <div class="text-center group">
                    <div class="w-16 h-16 bg-pink-50 text-pink-600 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Editor Poderoso</h3>
                    <p class="text-gray-500 leading-relaxed">Escreva artigos ricos com Markdown, imagens e formatação profissional para sua audiência.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 py-24 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Em Alta no Communify 🔥</h2>
                    <p class="text-gray-500 mt-2">Descubra as comunidades que estão bombando.</p>
                </div>
                <a href="{{ route('community.index') }}" class="hidden md:inline-flex text-indigo-600 font-bold hover:underline items-center gap-1">
                    Ver todas <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            @if($featuredCommunities->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($featuredCommunities as $community)
                        <a href="{{ route('community.show', $community->slug) }}" class="block group">
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-gray-100 flex flex-col h-full">
                                <div class="h-40 relative overflow-hidden">
                                    @if($community->cover_image)
                                        <img src="{{ asset('storage/' . $community->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-r from-gray-200 to-gray-300"></div>
                                    @endif
                                </div>
                                <div class="p-6 pt-10 relative flex-1 flex flex-col">
                                    <div class="absolute -top-10 left-6">
                                        <div class="w-20 h-20 rounded-2xl border-4 border-white bg-white overflow-hidden shadow-md">
                                            @if($community->profile_image)
                                                <img src="{{ asset('storage/' . $community->profile_image) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-indigo-50 flex items-center justify-center font-bold text-indigo-300 text-2xl">
                                                    {{ substr($community->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $community->name }}</h3>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-3">Por {{ $community->user->name }}</p>
                                    
                                    <p class="text-gray-600 text-sm line-clamp-3 mb-6 flex-1">
                                        {{ $community->description }}
                                    </p>
                                    
                                    <div class="flex items-center text-indigo-600 font-bold text-sm group-hover:translate-x-1 transition">
                                        Visitar Comunidade &rarr;
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-300">
                    <p class="text-gray-500">Seja o primeiro a criar uma comunidade de sucesso!</p>
                </div>
            @endif

            <div class="mt-8 text-center md:hidden">
                <a href="{{ route('community.index') }}" class="text-indigo-600 font-bold hover:underline">
                    Ver todas as comunidades &rarr;
                </a>
            </div>
        </div>
    </div>

    <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <span class="text-2xl font-bold text-white">Communify.</span>
                <p class="text-sm mt-1">Feito para criadores, por criadores.</p>
            </div>
            <div class="flex gap-6 text-sm font-medium">
                <a href="#" class="hover:text-white transition">Sobre</a>
                <a href="#" class="hover:text-white transition">Termos</a>
                <a href="#" class="hover:text-white transition">Privacidade</a>
                <a href="#" class="hover:text-white transition">Twitter</a>
            </div>
        </div>
    </footer>
</body>
</html>