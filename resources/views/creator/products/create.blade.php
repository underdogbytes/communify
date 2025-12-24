<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Adicionar Produto à Loja') }}
        </h2>
    </x-slot>

    <style>[x-cloak] { display: none !important; }</style>

    <div class="py-12" x-data="{ 
        type: 'physical', 
        baseProduct: null, 
        baseProducts: {{ $baseProducts->toJson() }} 
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('creator.produtos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <label class="cursor-pointer group">
                        <input type="radio" name="type" value="physical" x-model="type" class="peer sr-only">
                        <div class="p-6 rounded-xl border-2 border-gray-200 bg-white hover:border-indigo-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition text-center h-full flex flex-col items-center justify-center shadow-sm">
                            <span class="text-4xl mb-3 group-hover:scale-110 transition">👕</span>
                            <span class="font-bold text-lg text-gray-800">Produto Físico</span>
                            <span class="text-xs text-gray-500 mt-1">Camisetas, Canecas (POD)</span>
                        </div>
                    </label>

                    <label class="cursor-pointer group">
                        <input type="radio" name="type" value="digital" x-model="type" class="peer sr-only">
                        <div class="p-6 rounded-xl border-2 border-gray-200 bg-white hover:border-purple-300 peer-checked:border-purple-600 peer-checked:bg-purple-50 transition text-center h-full flex flex-col items-center justify-center shadow-sm">
                            <span class="text-4xl mb-3 group-hover:scale-110 transition">⚡</span>
                            <span class="font-bold text-lg text-gray-800">Digital / Link</span>
                            <span class="text-xs text-gray-500 mt-1">Ebook, Mentoria, Grupo VIP</span>
                        </div>
                    </label>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-8 bg-white border-b border-gray-200 space-y-6">

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nome do Produto</label>
                            <input type="text" name="name" required 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   placeholder="Ex: Masterclass Exclusiva ou Caneca Dev">
                        </div>

                        <div x-show="type === 'physical'" x-transition>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Escolha o Molde</label>
                            <select name="base_product_id" x-model="baseProduct" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecione um item...</option>
                                <template x-for="bp in baseProducts" :key="bp.id">
                                    <option :value="bp.id" x-text="bp.name + ' (Custo: R$ ' + bp.base_price + ')'"></option>
                                </template>
                            </select>
                        </div>

                        <div x-show="type === 'digital'" x-transition x-cloak>
                            <label class="block text-sm font-bold text-purple-700 mb-2">Link de Acesso (Gatekeeper)</label>
                            <div class="relative">
                                <input type="url" name="delivery_url" placeholder="https://drive.google.com/..." 
                                       class="w-full pl-4 rounded-md border-purple-300 focus:border-purple-500 focus:ring-purple-500 bg-purple-50">
                            </div>
                            <p class="text-xs text-purple-600 mt-1">🔒 O cliente só receberá este link após o pagamento confirmado.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Descrição</label>
                            <textarea name="description" rows="4" 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2" 
                                   x-text="type === 'physical' ? 'Quanto você quer lucrar? (R$)' : 'Qual o preço de venda? (R$)'">
                            </label>
                            
                            <div class="flex items-center gap-4">
                                <input type="number" name="profit" step="0.01" required 
                                       class="w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg font-bold" 
                                       placeholder="0.00">
                                
                                <div x-show="type === 'physical' && baseProduct" class="text-sm text-gray-500">
                                     + Custo Base: <span class="font-bold text-gray-700">automático</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2" x-show="type === 'physical'">
                                O preço final na loja será a soma do Custo Base + Seu Lucro.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Capa da Loja (Mockup)</label>
                                <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>

                            <div x-show="type === 'physical'" x-transition>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Arte para Impressão</label>
                                <input type="file" name="file_artwork" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 border-t border-gray-100 mt-6">
                            <a href="{{ route('creator.produtos.index') }}" class="mr-4 px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">Cancelar</a>
                            <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg transition">
                                Publicar Produto
                            </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>