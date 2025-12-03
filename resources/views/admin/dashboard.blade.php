<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Painel Administrativo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <div class="text-2xl font-bold">{{ $stats['users'] }}</div>
                    <div class="text-xs text-gray-500 uppercase">Usuários</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <div class="text-2xl font-bold">{{ $stats['communities'] }}</div>
                    <div class="text-xs text-gray-500 uppercase">Comunidades</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <div class="text-2xl font-bold">{{ $stats['orders'] }}</div>
                    <div class="text-xs text-gray-500 uppercase">Pedidos</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center border-l-4 border-green-500">
                    <div class="text-2xl font-bold text-green-600">R$ {{ number_format($stats['revenue'], 2, ',', '.') }}</div>
                    <div class="text-xs text-gray-500 uppercase">Receita Total</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center border-l-4 border-yellow-500">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</div>
                    <div class="text-xs text-gray-500 uppercase">Aguardando Aprovação</div>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-700 mb-4 px-1">Gerenciamento do Sistema</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <a href="{{ route('admin.pedidos.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition border border-gray-200 group">
                    <div class="p-6 flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4 group-hover:bg-yellow-200 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-gray-800">Pedidos</div>
                            <div class="text-sm text-gray-500">Aprovar pagamentos</div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.usuarios.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition border border-gray-200 group">
                    <div class="p-6 flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4 group-hover:bg-blue-200 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-gray-800">Usuários</div>
                            <div class="text-sm text-gray-500">Ver lista e "logar como"</div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.comunidades.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition border border-gray-200 group">
                    <div class="p-6 flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4 group-hover:bg-indigo-200 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-gray-800">Comunidades</div>
                            <div class="text-sm text-gray-500">Monitorar criadores</div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.produtos.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition border border-gray-200 group">
                    <div class="p-6 flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4 group-hover:bg-purple-200 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-gray-800">Todos Produtos</div>
                            <div class="text-sm text-gray-500">Catálogo global</div>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>