<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Lista todos os pedidos
     */
    public function index()
    {
        // Traz os pedidos, ordenando os "Pendentes com Comprovante" primeiro
        $orders = Order::with(['user', 'items'])
            ->orderByRaw("CASE WHEN status = 'awaiting_payment' AND proof_of_payment IS NOT NULL THEN 0 ELSE 1 END")
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Mostra detalhes para aprovação
     */
    public function show(Order $pedido)
    {
        // Precisamos passar 'order' para a view, pois é o nome que usamos lá dentro ($order->id)
        return view('admin.orders.show', ['order' => $pedido]);
    }

    /**
     * Atualiza o status (Aprovar Pagamento)
     */
public function update(Request $request, $id) // Mudei de Order $pedido para $id para testar
    {
        // DEBUG: Vamos ver se está chegando aqui
        // dd($request->all(), $id); 

        $pedido = Order::findOrFail($id); // Força a busca manual

        $request->validate([
            'status' => 'required|in:paid,shipped,delivered,canceled'
        ]);

        $pedido->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status atualizado para: ' . $request->status);
    }
}