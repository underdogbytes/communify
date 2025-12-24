<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes do Pedido #') . $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <a href="{{ route('admin.pedidos.index') }}" class="text-gray-500 hover:text-gray-900 mb-6 inline-block">
                &larr; Voltar para a Lista
            </a>

            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Cliente: {{ $order->user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                    </div>
                    <div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                            {{ $order->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <h4 class="font-bold text-gray-700 mb-4 uppercase text-xs tracking-wider">Itens Comprados</h4>
                    <ul class="divide-y divide-gray-100 mb-6">
                        @foreach($order->items as $item)
                            <li class="py-3 flex justify-between items-center">
                                <div class="flex items-center">
                                    @if($item->product->image_path)
                                        <img src="{{ asset('storage/' . $item->product->image_path) }}" class="w-12 h-12 rounded object-cover mr-4">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded mr-4"></div>
                                    @endif
                                    
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $item->product->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $item->product->baseProduct->name ?? 'Produto Digital' }}
                                            @if($item->quantity > 1)
                                                • Qtd: {{ $item->quantity }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="font-bold text-gray-700">
                                    R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="border-t border-gray-100 pt-6 flex justify-end">
                        <div class="text-right">
                            <p class="text-gray-500 text-sm">Frete: R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</p>
                            <p class="text-gray-500 text-sm">Doação: R$ {{ number_format($order->donation_amount, 2, ',', '.') }}</p>
                            <p class="text-2xl font-bold text-indigo-600 mt-2">Total: R$ {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                @if($order->status == 'awaiting_payment')
                    <div class="bg-gray-50 p-6 border-t border-gray-200">
                        <h4 class="font-bold text-gray-700 mb-4">Comprovante de Pagamento</h4>
                        
                        @if($order->proof_of_payment)
                            <div class="mb-6">
                                <a href="{{ asset('storage/' . $order->proof_of_payment) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $order->proof_of_payment) }}" class="max-w-xs rounded border hover:opacity-75 transition">
                                </a>
                                <p class="text-xs text-gray-500 mt-1">Clique para ampliar</p>
                            </div>

                            <form action="{{ route('admin.pedidos.update', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="paid">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-bold shadow w-full md:w-auto">
                                    ✅ Confirmar Pagamento e Liberar Acesso
                                </button>
                            </form>
                        @else
                            <p class="text-yellow-600 bg-yellow-50 p-3 rounded border border-yellow-200">
                                O cliente ainda não enviou o comprovante.
                            </p>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>