<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Painel do Criador') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('community.show', $community->slug) }}" target="_blank" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center bg-white px-3 py-2 rounded-md shadow-sm border border-gray-200 transition hover:bg-gray-50">
                    Ver Comunidade <i class="fa-solid fa-external-link-alt ml-2"></i>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500 p-6 flex items-center justify-between">
                    <div>
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wide">Faturamento Total</div>
                        <div class="text-3xl font-extrabold text-gray-900 mt-1">
                            R$ {{ number_format($totalRevenue, 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="p-3 bg-green-50 rounded-full text-green-600">
                        <i class="fa-solid fa-sack-dollar text-2xl"></i>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500 p-6 flex items-center justify-between">
                    <div>
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wide">Vendas Realizadas</div>
                        <div class="text-3xl font-extrabold text-gray-900 mt-1">
                            {{ $totalSales }}
                        </div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                        <i class="fa-solid fa-shopping-bag text-2xl"></i>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500 p-6 flex items-center justify-between">
                    <div>
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wide">Membros da Comunidade</div>
                        <div class="text-3xl font-extrabold text-gray-900 mt-1">
                            {{ $totalFollowers }}
                        </div>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-full text-purple-600">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <a href="{{ route('creator.posts.create') }}" class="group bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:border-indigo-500 transition flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="bg-indigo-50 text-indigo-600 p-3 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition">
                            <i class="fa-solid fa-pen-nib text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Novo Post</h4>
                            <p class="text-xs text-gray-500">Engaje com sua audiência</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-indigo-600"></i>
                </a>

                <a href="{{ route('creator.produtos.create') }}" class="group bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:border-purple-500 transition flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="bg-purple-50 text-purple-600 p-3 rounded-lg group-hover:bg-purple-600 group-hover:text-white transition">
                            <i class="fa-solid fa-tag text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Novo Produto</h4>
                            <p class="text-xs text-gray-500">Adicione algo à sua loja</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-purple-600"></i>
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800">Últimos Pedidos</h3>
                    </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-white text-gray-500 uppercase text-xs border-b">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Data</th>
                                <th class="px-6 py-3 font-semibold">Cliente</th>
                                <th class="px-6 py-3 font-semibold">Status</th>
                                <th class="px-6 py-3 font-semibold text-right">Valor Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $order->created_at->format('d/m/Y') }}
                                        <span class="text-xs text-gray-400 block">{{ $order->created_at->format('H:i') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $order->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($order->status == 'paid')
                                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-bold">
                                                <i class="fa-solid fa-check-circle"></i> Pago
                                            </span>
                                        @elseif($order->status == 'awaiting_payment')
                                            <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-bold">
                                                <i class="fa-solid fa-clock"></i> Pendente
                                            </span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">
                                                {{ $order->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900">
                                        R$ {{ number_format($order->total_amount, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fa-solid fa-box-open text-4xl mb-3 text-gray-300"></i>
                                            <p>Nenhuma venda recente.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>