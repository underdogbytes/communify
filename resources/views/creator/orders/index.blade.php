<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Todos os Pedidos Recebidos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">ID</th>
                                    <th class="px-6 py-3 font-semibold">Data</th>
                                    <th class="px-6 py-3 font-semibold">Cliente</th>
                                    <th class="px-6 py-3 font-semibold">Status</th>
                                    <th class="px-6 py-3 font-semibold text-right">Total</th>
                                    <th class="px-6 py-3 font-semibold text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($orders ?? [] as $order)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-gray-500">#{{ $order->id }}</td>
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $order->created_at->format('d/m/Y') }}
                                            <span class="text-xs text-gray-400 block">{{ $order->created_at->format('H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900">{{ $order->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-gray-900">
                                            R$ {{ number_format($order->total_amount, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900 text-xs font-bold">Detalhes</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                            Nenhum pedido encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(isset($orders) && $orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>