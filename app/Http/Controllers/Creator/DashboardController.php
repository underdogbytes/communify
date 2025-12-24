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

        // --- ESTATÍSTICAS FINANCEIRAS (WALLET) ---
        
        // 1. Faturamento Total (Soma de: preço * quantidade dos itens vendidos e PAGOS)
        $totalRevenue = $community->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'paid') // Só conta dinheiro no bolso
            ->sum(DB::raw('order_items.price * order_items.quantity'));

        // 2. Total de Vendas (Quantidade de pedidos pagos que contêm meus produtos)
        $totalSales = $community->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'paid')
            ->distinct('orders.id')
            ->count('orders.id');

        // 3. Seguidores
        $totalFollowers = $community->followers()->count();

        // 4. Últimos Pedidos (Para a tabela de gestão rápida)
        // Busca pedidos que tenham itens desta comunidade
        $recentOrders = Order::whereHas('items.product', function($query) use ($community) {
                $query->where('community_id', $community->id);
            })
            ->with('user') // Traz quem comprou
            ->latest()
            ->take(5)
            ->get();

        return view('creator.dashboard', compact(
            'community', 
            'totalRevenue', 
            'totalSales', 
            'totalFollowers', 
            'recentOrders'
        ));
    }

    /**
     * Lista de Pedidos Completa (Placeholder)
     */
    public function orders()
    {
        return view('creator.orders.index'); 
    }
}