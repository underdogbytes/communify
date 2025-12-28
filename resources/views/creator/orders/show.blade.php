<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalhes do Pedido #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">📦 Cliente & Entrega</h3>
                    <p class="font-bold">{{ $order->user->full_name ?? $order->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                    <div class="mt-4 text-sm bg-gray-50 p-3 rounded">
                        <p>{{ $order->address_street }}, {{ $order->address_number }}</p>
                        <p>{{ $order->address_complement }}</p>
                        <p>{{ $order->address_city }} - {{ $order->address_state }}</p>
                        <p>{{ $order->address_zip }}</p>
                        <p>CPF: {{ $order->cpf }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">🚚 Atualizar Status</h3>
                    
                    <form action="{{ route('creator.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Status Atual</label>
                            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="awaiting_payment" {{ $order->status == 'awaiting_payment' ? 'selected' : '' }}>Aguardando Pagamento</option>
                                <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Pago (Em Produção)</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Enviado</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Entregue</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Transportadora</label>
                            <input type="text" name="shipping_carrier" value="{{ $order->shipping_carrier }}" placeholder="Ex: Correios" class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Código de Rastreio</label>
                            <input type="text" name="tracking_code" value="{{ $order->tracking_code }}" placeholder="Ex: AA123456789BR" class="w-full rounded-md border-gray-300 shadow-sm font-mono uppercase">
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md transition">
                            Atualizar Pedido
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <h3 class="font-bold text-gray-700 mb-4">Itens do Pedido</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Produto</th>
                                <th class="text-right text-xs font-medium text-gray-500 uppercase">Qtd</th>
                                <th class="text-right text-xs font-medium text-gray-500 uppercase">Preço Un.</th>
                                <th class="text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-4 text-sm text-gray-900">{{ $item->product_name }}</td>
                                    <td class="py-4 text-sm text-gray-900 text-right">{{ $item->quantity }}</td>
                                    <td class="py-4 text-sm text-gray-900 text-right">R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                                    <td class="py-4 text-sm text-gray-900 text-right font-bold">R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="pt-4 text-right font-bold text-gray-900">Total do Pedido:</td>
                                <td class="pt-4 text-right font-bold text-indigo-600 text-lg">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>