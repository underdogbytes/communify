<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pedido #{{ $order->id }} 
            @if($order->status == 'draft')
                - <span class="text-indigo-600">Finalizar Compra</span>
            @else
                - 
                @if($order->status == 'paid')
                    <span class="text-green-600 font-bold uppercase">PAGO & APROVADO</span>
                @elseif($order->status == 'awaiting_payment')
                    <span class="text-yellow-600 font-bold uppercase">AGUARDANDO PAGAMENTO</span>
                @else
                    <span class="uppercase">{{ str_replace('_', ' ', $order->status) }}</span>
                @endif
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($order->status == 'draft')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-2 space-y-6">
                        <div class="bg-white p-6 shadow sm:rounded-lg">
                            <h3 class="font-bold text-gray-800 mb-4">Itens do Pedido</h3>
                            
                            @foreach($order->items as $item)
                                <div class="flex items-center border-b border-gray-100 pb-4 mb-4 last:border-0 last:mb-0">
                                    <img src="{{ asset('storage/' . $item->product->image_path) }}" class="w-20 h-20 rounded object-cover mr-4 border border-gray-200">
                                    
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-800 text-sm">{{ $item->product->name }}</h4>
                                        <p class="text-xs text-gray-500 mb-2">
                                            {{ $item->product->baseProduct->name ?? 'Produto Digital' }}
                                            @if($item->selected_options_json)
                                                <br>
                                                @foreach($item->selected_options_json as $key => $val)
                                                    <span class="inline-block bg-gray-100 px-1 rounded text-[10px] uppercase font-semibold mr-1 mt-1">
                                                        {{ $key }}: {{ $val }}
                                                    </span>
                                                @endforeach
                                            @endif
                                        </p>
                                        
                                        <div class="flex items-center">
                                            <form action="{{ route('order.item.update', $item->id) }}" method="POST" class="inline-flex items-center border border-gray-300 rounded-md">
                                                @csrf @method('PATCH')
                                                <button type="submit" name="action" value="decrease" class="px-2 py-1 text-gray-600 hover:bg-gray-100 border-r border-gray-300 text-sm {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}">-</button>
                                                <span class="px-3 py-1 text-sm font-bold text-gray-700 bg-white">{{ $item->quantity }}</span>
                                                <button type="submit" name="action" value="increase" class="px-2 py-1 text-gray-600 hover:bg-gray-100 border-l border-gray-300 text-sm">+</button>
                                            </form>

                                            <form action="{{ route('order.item.remove', $item->id) }}" method="POST" class="ml-3">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600 p-1 rounded hover:bg-red-50 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="ml-4 text-right">
                                        <div class="font-bold text-gray-900">R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <form id="checkout-form" action="{{ route('order.finalize', $order->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="bg-white p-6 shadow sm:rounded-lg">
                                <h3 class="font-bold text-gray-800 mb-4 flex items-center">Endereço de Entrega</h3>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="text-xs font-bold text-gray-600 uppercase">Nome Completo</label>
                                        <input type="text" name="full_name" required value="{{ auth()->user()->full_name ?? auth()->user()->name }}" class="w-full rounded border-gray-300 text-sm">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="text-xs font-bold text-gray-600 uppercase">CPF</label><input type="text" name="cpf" required value="{{ auth()->user()->cpf }}" class="w-full rounded border-gray-300 text-sm"></div>
                                        <div><label class="text-xs font-bold text-gray-600 uppercase">CEP</label><input type="text" name="address_zip" required value="{{ auth()->user()->address_zip }}" class="w-full rounded border-gray-300 text-sm"></div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="col-span-2"><label class="text-xs font-bold text-gray-600 uppercase">Cidade</label><input type="text" name="address_city" required value="{{ auth()->user()->address_city }}" class="w-full rounded border-gray-300 text-sm"></div>
                                        <div><label class="text-xs font-bold text-gray-600 uppercase">UF</label><input type="text" name="address_state" required maxlength="2" value="{{ auth()->user()->address_state }}" class="w-full rounded border-gray-300 text-sm"></div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="col-span-2"><label class="text-xs font-bold text-gray-600 uppercase">Rua</label><input type="text" name="address_street" required value="{{ auth()->user()->address_street }}" class="w-full rounded border-gray-300 text-sm"></div>
                                        <div><label class="text-xs font-bold text-gray-600 uppercase">Número</label><input type="text" name="address_number" required value="{{ auth()->user()->address_number }}" class="w-full rounded border-gray-300 text-sm"></div>
                                    </div>
                                    <div><label class="text-xs font-bold text-gray-600 uppercase">Complemento</label><input type="text" name="address_complement" value="{{ auth()->user()->address_complement }}" class="w-full rounded border-gray-300 text-sm"></div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-white p-6 shadow sm:rounded-lg" x-data="{ donation: 0, shipping: 30.00, subtotal: {{ $order->items->sum(fn($item) => $item->price * $item->quantity) }} }">
                                <h3 class="font-bold text-gray-800 mb-4">Resumo</h3>
                                <div class="mb-6 p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                                    <label class="block text-sm font-bold text-indigo-800 mb-2">Apoie a Causa (Opcional)</label>
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">R$</span>
                                        <input type="number" name="donation_amount" min="0" step="1.00" x-model.number="donation" class="w-full rounded border-indigo-200 focus:ring-indigo-500">
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm border-t pt-4">
                                    <div class="flex justify-between"><span class="text-gray-600">Subtotal:</span><span class="font-bold">R$ <span x-text="subtotal.toFixed(2).replace('.', ',')"></span></span></div>
                                    <div class="flex justify-between"><span class="text-gray-600">Frete:</span><span class="font-bold">R$ 30,00</span></div>
                                    <div class="flex justify-between text-indigo-600" x-show="donation > 0"><span>Doação:</span><span class="font-bold">R$ <span x-text="parseFloat(donation).toFixed(2)"></span></span></div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">Total:</span>
                                    <span class="text-2xl font-bold text-indigo-600">R$ <span x-text="(subtotal + shipping + parseFloat(donation || 0)).toFixed(2)"></span></span>
                                </div>
                                <button type="submit" form="checkout-form" class="w-full mt-6 bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 shadow-lg transition">Finalizar e Pagar</button>
                            </div>
                        </div>
                    </form>
                </div>

            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Itens Comprados</h3>
                        
                        <div class="space-y-6 mb-4 border-b pb-4">
                            @foreach($order->items as $item)
                                <div class="flex flex-col sm:flex-row items-start sm:items-center bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center mb-4 sm:mb-0">
                                        <img src="{{ asset('storage/' . $item->product->image_path) }}" class="w-16 h-16 rounded object-cover mr-4">
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-sm">{{ $item->product->name }}</h4>
                                            <p class="text-xs text-gray-500">
                                                {{ $item->product->baseProduct->name ?? 'Produto Digital' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="ml-0 sm:ml-auto w-full sm:w-auto mt-2 sm:mt-0">
                                        @if($item->product->type === 'digital')
                                            
                                            @if($order->status == 'paid')
                                                <a href="{{ $item->product->delivery_url }}" target="_blank" class="block w-full sm:w-auto text-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold rounded shadow transition transform hover:scale-105">
                                                    <i class="fa-solid fa-unlock mr-2"></i> Acessar Conteúdo
                                                </a>
                                                <p class="text-[10px] text-gray-400 text-center mt-1">Acesso liberado</p>
                                            
                                            @elseif($order->status == 'awaiting_payment')
                                                <div class="px-4 py-2 bg-gray-200 text-gray-400 text-sm font-bold rounded cursor-not-allowed text-center">
                                                    <i class="fa-solid fa-lock mr-2"></i> Aguardando Pagto
                                                </div>
                                            
                                            @endif

                                        @else
                                            <div class="text-right text-sm font-bold text-gray-600">
                                                {{ $item->quantity }}un x R$ {{ number_format($item->price, 2, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>
                                    </div>
                            @endforeach
                        </div>

                        <div class="mb-4 bg-gray-50 p-3 rounded">
                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-1">Entregar em:</h4>
                            <p class="text-sm text-gray-800">
                                {{ $order->user->address_street }}, {{ $order->user->address_number }}<br>
                                CEP: {{ $order->user->address_zip }}
                            </p>
                        </div>

                        <div class="flex justify-between text-xl font-bold text-gray-900 pt-2 border-t">
                            <span>Total Pago</span>
                            <span>R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-fit">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Status do Pedido</h3>

                        @if($order->status == 'awaiting_payment')
                            @if(!$order->proof_of_payment)
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                    <p class="text-sm text-yellow-800 font-bold">Pagamento Pendente</p>
                                    <p class="text-xs text-yellow-700">Envie o comprovante para liberar seus produtos digitais ou iniciar a produção.</p>
                                </div>

                                <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-6 text-center mb-6">
                                    <p class="text-xs text-gray-500 mb-2">Chave PIX (Email)</p>
                                    <p class="text-lg font-mono font-bold text-gray-800 select-all">pix@communify.com</p>
                                    <p class="text-xs text-indigo-600 mt-2 font-bold">Valor: R$ {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                                </div>

                                <form action="{{ route('order.upload_proof', $order->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Anexar Comprovante</label>
                                    <input type="file" name="proof" required accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 mb-4">
                                    <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700">Enviar Comprovante</button>
                                </form>
                            @else
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                                    <p class="text-sm font-bold text-blue-800">Comprovante em Análise</p>
                                    <p class="text-xs text-blue-700">Assim que o criador confirmar, seu acesso será liberado.</p>
                                </div>
                                <img src="{{ asset('storage/' . $order->proof_of_payment) }}" class="mt-4 w-full rounded border shadow-sm">
                            @endif

                        @elseif($order->status == 'paid')
                            <div class="bg-green-50 border-l-4 border-green-400 p-6 text-center">
                                <div class="text-green-500 text-5xl mb-2"><i class="fa-solid fa-circle-check"></i></div>
                                <h3 class="text-lg font-bold text-green-800">Tudo Certo!</h3>
                                <p class="text-sm text-green-700 mb-4">Pagamento confirmado.</p>
                                <p class="text-xs text-gray-500">Seus produtos digitais já estão liberados para acesso ao lado.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>