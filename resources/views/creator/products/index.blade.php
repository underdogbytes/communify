<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gerenciar Produtos') }}
            </h2>
            <a href="{{ route('creator.produtos.create') }}" class="px-4 py-2 bg-purple-600 text-white text-sm font-bold rounded hover:bg-purple-700 shadow transition transform hover:scale-105">
                + Adicionar Produto
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 flex flex-col h-full hover:shadow-md transition">
                        
                        <div class="h-48 bg-gray-100 relative group">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">Sem Imagem</div>
                            @endif

                            <div class="absolute top-2 right-2 px-2 py-1 text-xs font-bold rounded shadow
                                {{ $product->type === 'digital' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                                
                                @if($product->type === 'digital')
                                    ⚡ Digital
                                @else
                                    {{ $product->baseProduct->name ?? 'Produto Físico' }}
                                @endif
                            </div>
                        </div>

                        <div class="p-5 flex-1 flex flex-col">
                            <h3 class="font-bold text-gray-900 truncate text-lg">{{ $product->name }}</h3>
                            
                            <div class="mt-4 space-y-2 mb-4">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Preço na Loja:</span>
                                    <span class="font-bold text-gray-900">R$ {{ number_format($product->total_price, 2, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Seu Lucro:</span>
                                    <span class="font-bold text-green-600">+ R$ {{ number_format($product->profit, 2, ',', '.') }}</span>
                                </div>
                                
                                @if($product->type === 'physical')
                                    <div class="flex justify-between items-center text-xs text-gray-400">
                                        <span>Custo Base:</span>
                                        <span>- R$ {{ number_format($product->baseProduct->base_price, 2, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-auto flex justify-between items-center border-t border-gray-100 pt-4">
                                <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="text-indigo-600 text-sm font-bold hover:underline flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    Ver na Loja
                                </a>
                                
                                <form action="{{ route('creator.produtos.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este produto da sua loja?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-sm hover:text-red-700 font-medium">Excluir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-12 text-center rounded-xl border-2 border-dashed border-gray-300">
                        <div class="text-gray-300 text-6xl mb-4">🛍️</div>
                        <h3 class="text-lg font-bold text-gray-900">Sua loja está vazia</h3>
                        <p class="text-gray-500 mb-6">Comece a vender produtos físicos ou digitais agora mesmo.</p>
                        <a href="{{ route('creator.produtos.create') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition">
                            Criar Primeiro Produto
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>