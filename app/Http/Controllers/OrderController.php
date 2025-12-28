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

        // Se não tem carrinho, mostra view vazia
        if (!$order) {
            return view('order.empty'); // Certifique-se que o arquivo é resources/views/orders/empty.blade.php
        }

        // Se tem carrinho, usa a view de show (que tem a lógica de checkout)
        return view('order.show', compact('order'));
    }

    // ADICIONAR AO CARRINHO
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'selected_options' => 'nullable|json',
        ]);

        $product = Product::findOrFail($request->product_id);
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
        $this->recalculateOrderTotal($order);

        return back()->with('success', 'Produto adicionado ao carrinho!'); 
    }

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

    private function recalculateOrderTotal(Order $order)
    {
        $itemsTotal = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $order->total_amount = $itemsTotal;
        $order->save();
    }

    // MOSTRAR PEDIDO (CHECKOUT OU DETALHES)
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        return view('order.show', compact('order'));
    }

    // FINALIZAR COMPRA
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

        // Atualiza dados do usuário
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

        // Frete
        $hasPhysicalItems = $order->items->contains(function($item) {
            return $item->product->type === 'physical';
        });
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

        // CORREÇÃO CRÍTICA: REDIRECIONAR PARA A ROTA DE SUCESSO
        return redirect()->route('order.success', $order->id);
    }

    // EXIBIR TELA DE SUCESSO / PIX
    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        return view('order.success', compact('order'));
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