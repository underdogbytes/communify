<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl text-center p-10 border border-green-100">
                <div class="mb-6">
                    <div class="h-24 w-24 bg-green-100 rounded-full flex items-center justify-center mx-auto animate-bounce">
                        <i class="fa-solid fa-check text-5xl text-green-600"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Pedido Recebido!</h1>
                <p class="text-gray-500 mb-8">Pedido #{{ $order->id }} aguardando pagamento.</p>
                
                <div class="bg-gray-50 p-6 rounded-lg mb-6">
                    <p class="font-bold text-lg mb-2">Total: R$ {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                    <p class="text-sm text-gray-600 mb-2">Copie o código PIX:</p>
                    <input type="text" value="00020126580014br.gov.bcb.pix0136{{ $order->id }}-fake-pix" class="w-full border rounded p-2 text-center bg-white select-all" readonly>
                </div>

                <a href="{{ route('order.index') }}" class="text-indigo-600 underline">Ver meus pedidos</a>
            </div>
        </div>
    </div>
</x-app-layout>