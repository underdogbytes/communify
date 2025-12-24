<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->latest()->get();
        return view('order.index', compact('orders'));
    }

    // LISTAR O CARRINHO
    public function cart()
    {
        $order = auth()->user()->orders()->where('status', 'draft')->with('items.product')->first();

        if (!$order) {
            return view('order.empty');
        }

        return view('order.show', compact('order'));
    }

    // ADICIONAR AO CARRINHO (CORRIGIDO)
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'selected_options' => 'nullable|json',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // CORREÇÃO: Usamos o Accessor do Model em vez de calcular na mão
        // Isso evita o erro "attempt to read property on null"
        $unitPrice = $product->total_price;

        // 1. Tenta achar um carrinho aberto
        $order = Order::where('user_id', auth()->id())->where('status', 'draft')->first();

        // 2. Se não achar, cria um NOVO
        if (!$order) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'draft',
                'total_amount' => 0, 
                'shipping_cost' => 0,
                'donation_amount' => 0,
            ]);
        }

        // 3. Adiciona item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $unitPrice,
            'selected_options_json' => json_decode($request->selected_options, true),
        ]);

        // 4. Atualiza total
        $order->total_amount = $order->items()->sum('price');
        $order->save();

        return back()->with('success', 'Produto adicionado ao carrinho!'); 
    }


    /**
     * Remover item do carrinho
     */
    public function removeItem(OrderItem $item)
    {
        if ($item->order->user_id !== auth()->id() || $item->order->status !== 'draft') {
            abort(403);
        }

        $order = $item->order;
        $item->delete();

        $this->recalculateOrderTotal($order);

        if ($order->items()->count() === 0) {
            $order->delete();
            return redirect()->route('order.cart');
        }

        return back()->with('success', 'Item removido.');
    }

    /**
     * Atualizar Quantidade
     */
    public function updateItemQuantity(Request $request, OrderItem $item)
    {
        if ($item->order->user_id !== auth()->id() || $item->order->status !== 'draft') {
            abort(403);
        }

        $action = $request->input('action');

        if ($action === 'increase') {
            $item->increment('quantity');
        } elseif ($action === 'decrease') {
            if ($item->quantity > 1) {
                $item->decrement('quantity');
            }
        }

        $this->recalculateOrderTotal($item->order);

        return back();
    }

    /**
     * Função auxiliar para recalcular totais
     */
    private function recalculateOrderTotal(Order $order)
    {
        $itemsTotal = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $order->total_amount = $itemsTotal;
        $order->save();
    }

    // MOSTRAR CHECKOUT
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        return view('order.show', compact('order'));
    }

    // FINALIZAR COMPRA (CORRIGIDO PARA DIGITAL)
    public function finalize(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        $request->validate([
            'full_name' => 'required|string',
            'cpf' => 'required|string',
            'address_zip' => 'required|string',
            'address_street' => 'required|string',
            'address_number' => 'required|string',
            'address_city' => 'required|string',
            'address_state' => 'required|string|max:2',
        ]);

        // Atualiza usuário
        auth()->user()->update([
            'full_name' => $request->full_name,
            'cpf' => $request->cpf,
            'address_zip' => $request->address_zip,
            'address_street' => $request->address_street,
            'address_number' => $request->address_number,
            'address_complement' => $request->address_complement,
            'address_city' => $request->address_city,
            'address_state' => $request->address_state,
        ]);

        // BÔNUS: Lógica de Frete Inteligente
        // Verifica se existe algum produto físico no carrinho
        $hasPhysicalItems = $order->items->contains(function($item) {
            return $item->product->type === 'physical';
        });

        // Se tiver item físico, cobra 30. Se for tudo digital, frete grátis.
        $shippingCost = $hasPhysicalItems ? 30.00 : 0.00;
        
        $donationAmount = $request->input('donation_amount', 0);
        
        $itemsTotal = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        $finalTotal = $itemsTotal + $shippingCost + $donationAmount;

        $order->update([
            'status' => 'awaiting_payment',
            'shipping_cost' => $shippingCost,
            'donation_amount' => $donationAmount,
            'total_amount' => $finalTotal
        ]);

        return back()->with('success', 'Pedido atualizado! Realize o pagamento.');
    }

    // UPLOAD COMPROVANTE
    public function uploadProof(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        $request->validate(['proof' => 'required|image|max:2048']);
        $path = $request->file('proof')->store('orders/proofs', 'public');
        $order->update(['proof_of_payment' => $path]);
        return back()->with('success', 'Comprovante enviado!');
    }
}