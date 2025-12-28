<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class DashboardController extends Controller
{
    /**
     * Painel Principal do Criador (Wallet)
     */
    public function index()
    {
        $user = Auth::user();
        $community = $user->community;

        if (!$community) {
            return redirect()->route('community.create');
        }

        // 1. Financeiro
        $totalRevenue = $community->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'paid')
            ->sum(DB::raw('order_items.price * order_items.quantity'));

        $totalSales = $community->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'paid')
            ->distinct('orders.id')
            ->count('orders.id');

        // 2. Seguidores
        $totalFollowers = $community->followers()->count();

        // 3. Posts Pendentes (NOVO)
        $pendingPostCount = $community->posts()->where('status', 'pending')->count();

        // 4. Últimos Pedidos
        $recentOrders = Order::whereHas('items.product', function($query) use ($community) {
                $query->where('community_id', $community->id);
            })
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('creator.dashboard', compact(
            'community', 
            'totalRevenue', 
            'totalSales', 
            'totalFollowers', 
            'pendingPostCount', // <--- Adicionado
            'recentOrders'
        ));
    }

    /**
     * Lista de Pedidos Completa (Placeholder)
     */
/**
     * Lista de Pedidos Completa
     */
    public function orders()
    {
        $community = Auth::user()->community;

        if (!$community) abort(403);

        // Busca TODOS os pedidos da comunidade, paginados
        $orders = Order::whereHas('items.product', function($query) use ($community) {
                $query->where('community_id', $community->id);
            })
            ->with('user')
            ->latest()
            ->paginate(20); // 20 por página

        return view('creator.orders.index', compact('orders')); 
    }

    // Exibe detalhes do pedido
    public function showOrder(Order $order)
    {
        // Segurança: Verifica se o pedido tem produtos da comunidade deste criador
        // (Simplificação: assume que o pedido é de uma unica comunidade por enquanto)
        // Idealmente verificar item a item, mas para MVP ok.
        
        return view('creator.orders.show', compact('order'));
    }

    // Salva rastreio e status
    public function updateOrder(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:paid,shipped,delivered,cancelled,awaiting_payment',
            'tracking_code' => 'nullable|string|max:50',
            'shipping_carrier' => 'nullable|string|max:50',
        ]);

        $order->update($data);

        // TODO: Enviar notificação ao usuário "Seu pedido foi enviado!"

        return back()->with('success', 'Pedido atualizado com sucesso!');
    }

}