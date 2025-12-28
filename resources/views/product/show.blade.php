<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{ $product->name }} - {{ $product->community->name }}</title>
    <meta property="og:title" content="{{ $product->name }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($product->description, 150) }}">
    <meta property="og:image" content="{{ $product->image_path ? asset('storage/' . $product->image_path) : '' }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <a href="{{ route('community.show', $product->community->slug) }}" class="flex items-center space-x-2 hover:opacity-80 transition">
                @if($product->community->profile_image)
                    <img src="{{ asset('storage/' . $product->community->profile_image) }}" class="w-8 h-8 rounded-full object-cover">
                @else
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                        {{ substr($product->community->name, 0, 1) }}
                    </div>
                @endif
                <span class="font-bold text-gray-700">{{ $product->community->name }}</span>
            </a>
            
            <div class="flex items-center space-x-4">
                @auth
                    @php
                        $cartCount = auth()->user()->orders()->where('status', 'draft')->first()?->items->count() ?? 0;
                    @endphp
                    <a href="{{ route('order.cart') }}" class="relative text-gray-600 hover:text-indigo-600">
                        <i class="fa-solid fa-cart-shopping text-xl"></i>
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-indigo-600 font-semibold">Painel</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">Entrar</a>
                @endauth
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex justify-between items-center">
                <span>{{ session('success') }}</span>
                @if(session('success') == 'Produto adicionado ao carrinho!')
                    <a href="{{ route('order.cart') }}" class="text-sm font-bold underline hover:text-green-900">Ir para o Carrinho &rarr;</a>
                @endif
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative group">
                    <div class="absolute top-4 left-4 z-10">
                        @if($product->type === 'digital')
                            <span class="bg-purple-600 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide shadow-sm flex items-center gap-1">
                                <i class="fa-solid fa-bolt"></i> Digital
                            </span>
                        @else
                            <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide shadow-sm flex items-center gap-1">
                                <i class="fa-solid fa-box"></i> Físico
                            </span>
                        @endif
                    </div>

                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-auto object-cover max-h-[600px]">
                    @else
                        <div class="h-96 w-full bg-gray-100 flex items-center justify-center text-gray-400 flex-col">
                            <i class="fa-regular fa-image text-6xl mb-4"></i>
                            <span>Sem imagem disponível</span>
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ tab: 'details' }">
                    <div class="flex border-b border-gray-200">
                        <button @click="tab = 'details'" 
                                :class="tab === 'details' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'" 
                                class="flex-1 py-4 px-1 text-center border-b-2 font-bold text-sm transition focus:outline-none">
                            Detalhes
                        </button>
                        <button @click="tab = 'reviews'" 
                                :class="tab === 'reviews' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'" 
                                class="flex-1 py-4 px-1 text-center border-b-2 font-bold text-sm transition focus:outline-none">
                            Avaliações ({{ $product->reviews->count() }})
                        </button>
                        <button @click="tab = 'qa'" 
                                :class="tab === 'qa' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'" 
                                class="flex-1 py-4 px-1 text-center border-b-2 font-bold text-sm transition focus:outline-none">
                            Perguntas ({{ $product->questions->count() }})
                        </button>
                    </div>

                    <div class="p-6 md:p-8 min-h-[300px]">
                        
                        <div x-show="tab === 'details'" x-transition:enter.duration.300ms>
                            <h3 class="font-bold text-gray-900 mb-4 text-lg">Descrição do Produto</h3>
                            <div class="prose max-w-none text-gray-600 leading-relaxed whitespace-pre-line">
                                {{ $product->description }}
                            </div>
                        </div>

                        <div x-show="tab === 'reviews'" x-cloak x-transition:enter.duration.300ms>
                            
                            <div class="flex items-center gap-6 mb-8 bg-gray-50 p-6 rounded-xl border border-gray-100">
                                <div class="text-center">
                                    <div class="text-5xl font-extrabold text-gray-900">{{ $product->rating }}</div>
                                    <div class="flex text-yellow-400 text-sm justify-center mt-1">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="fa-solid fa-star {{ $i <= round($product->rating) ? '' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $product->reviews->count() }} opiniões</p>
                                </div>
                                <div class="h-12 w-px bg-gray-300 hidden md:block"></div>
                                <div class="flex-1 text-sm text-gray-600">
                                    <p>As avaliações ajudam outros membros a decidir. Apenas compradores confirmados podem avaliar.</p>
                                </div>
                            </div>

                            @auth
                                @if($canReview ?? false)
                                    <form action="{{ route('product.review.store', $product->id) }}" method="POST" class="mb-8 border-b border-gray-100 pb-8 bg-indigo-50 p-4 rounded-lg">
                                        @csrf
                                        <h4 class="font-bold text-indigo-900 mb-3 flex items-center gap-2">
                                            <i class="fa-regular fa-star"></i> Avalie sua compra
                                        </h4>
                                        
                                        <div class="mb-3">
                                            <label class="block text-xs font-bold text-gray-700 mb-1 uppercase">Nota</label>
                                            <select name="rating" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm w-full">
                                                <option value="5">⭐⭐⭐⭐⭐ Excelente</option>
                                                <option value="4">⭐⭐⭐⭐ Muito Bom</option>
                                                <option value="3">⭐⭐⭐ Bom</option>
                                                <option value="2">⭐⭐ Ruim</option>
                                                <option value="1">⭐ Péssimo</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-xs font-bold text-gray-700 mb-1 uppercase">Comentário</label>
                                            <textarea name="comment" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="O que você achou do produto?"></textarea>
                                        </div>
                                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-indigo-700 transition w-full md:w-auto">
                                            Enviar Avaliação
                                        </button>
                                    </form>
                                @endif
                            @endauth

                            <div class="space-y-6">
                                @forelse($product->reviews as $review)
                                    <div class="border-b border-gray-100 last:border-0 pb-6 last:pb-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500 text-xs">
                                                    {{ substr($review->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <span class="font-bold text-gray-900 text-sm block">{{ $review->user->name }}</span>
                                                    <div class="flex text-yellow-400 text-xs">
                                                        @for($i=1; $i<=5; $i++)
                                                            <i class="fa-solid fa-star {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-400">{{ $review->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <p class="text-gray-700 text-sm mt-2 bg-gray-50 p-3 rounded-lg">{{ $review->comment }}</p>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-gray-400">
                                        <i class="fa-regular fa-comment-dots text-3xl mb-2"></i>
                                        <p>Seja o primeiro a avaliar este produto!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div x-show="tab === 'qa'" x-cloak x-transition:enter.duration.300ms>
                            
                            @auth
                                <form action="{{ route('product.question.store', $product->id) }}" method="POST" class="mb-8 flex gap-2">
                                    @csrf
                                    <input type="text" name="question" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Tem alguma dúvida? Pergunte ao criador..." required>
                                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md font-bold hover:bg-gray-700 transition">Perguntar</button>
                                </form>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg text-center mb-6">
                                    <p class="text-sm text-yellow-800">
                                        <a href="{{ route('login') }}" class="font-bold underline">Faça login</a> para enviar uma pergunta.
                                    </p>
                                </div>
                            @endauth

                            <div class="space-y-6">
                                @forelse($product->questions as $qa)
                                    <div class="bg-white border border-gray-200 p-4 rounded-xl shadow-sm">
                                        <div class="flex gap-3 mb-2">
                                            <div class="bg-gray-100 text-gray-600 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0">P</div>
                                            <div>
                                                <p class="font-bold text-gray-900 text-sm">{{ $qa->question }}</p>
                                                <p class="text-xs text-gray-400 mt-1">por {{ $qa->user->name }} em {{ $qa->created_at->format('d/m') }}</p>
                                            </div>
                                        </div>

                                        @if($qa->answer)
                                            <div class="flex gap-3 mt-4 pl-4 border-l-2 border-indigo-100">
                                                <div class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0">R</div>
                                                <div>
                                                    <p class="text-gray-800 text-sm font-medium">{{ $qa->answer }}</p>
                                                    <p class="text-xs text-indigo-500 mt-1 font-bold">Resposta do Criador</p>
                                                </div>
                                            </div>
                                        @elseif(auth()->check() && auth()->id() === $product->community->user_id)
                                            <form action="{{ route('product.question.answer', $qa->id) }}" method="POST" class="mt-3 pl-11">
                                                @csrf @method('PUT')
                                                <div class="flex gap-2">
                                                    <input type="text" name="answer" class="flex-1 rounded border-gray-300 text-sm py-1.5 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Escreva a resposta aqui..." required>
                                                    <button class="text-xs bg-indigo-600 text-white px-3 py-1.5 rounded font-bold hover:bg-indigo-700">Responder</button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-gray-400">
                                        <p>Nenhuma pergunta feita ainda.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sticky top-24">
                    
                    <div class="mb-4">
                        <h1 class="text-2xl font-extrabold text-gray-900 leading-tight mb-2">{{ $product->name }}</h1>
                        <div class="flex items-center gap-2">
                            <div class="flex text-yellow-400 text-xs">
                                @for($i=1; $i<=5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= round($product->rating) ? '' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <span class="text-xs text-gray-500 font-medium">({{ $product->reviews->count() }} avaliações)</span>
                        </div>
                    </div>

                    <div class="text-3xl font-bold text-gray-900 mb-6">
                        R$ {{ number_format($product->total_price, 2, ',', '.') }}
                    </div>

                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        @if($product->baseProduct && $product->baseProduct->options_json)
                            <div class="space-y-3 mb-6 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                @foreach($product->baseProduct->options_json as $key => $values)
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase">{{ $key }}</label>
                                        <select name="selected_options[{{ $key }}]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-1.5">
                                            @foreach($values as $val)
                                                <option value="{{ $val }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @auth
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-bold py-3.5 rounded-xl shadow-md transition transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
                                <span>Comprar Agora</span> <i class="fa-solid fa-arrow-right text-sm"></i>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center bg-gray-800 text-white font-bold py-3.5 rounded-xl hover:bg-gray-900 transition">
                                Faça Login para Comprar
                            </a>
                        @endauth
                    </form>

                    <div class="mt-6 border-t border-gray-100 pt-4 space-y-3">
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fa-solid fa-shield-halved text-green-500 text-lg"></i>
                            <span>Pagamento 100% Seguro</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fa-solid fa-undo text-blue-500 text-lg"></i>
                            <span>Garantia de 7 dias</span>
                        </div>
                        @if($product->type === 'physical')
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <i class="fa-solid fa-truck-fast text-orange-500 text-lg"></i>
                                <span>Envio para todo o Brasil</span>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </main>

</body>
</html>