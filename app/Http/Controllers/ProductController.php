<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductQuestion;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        // Carrega produto com reviews e perguntas
        $product = Product::where('slug', $slug)
            ->with(['community.user', 'reviews.user', 'questions.user'])
            ->firstOrFail();

        // Verifica se o usuário logado COMPROU esse produto (para liberar review)
        $canReview = false;
        if (auth()->check()) {
            $canReview = auth()->user()->orders()
                ->where('status', 'paid') // Só pedido pago conta
                ->whereHas('items', function($q) use ($product) {
                    $q->where('product_id', $product->id);
                })
                ->exists();
            
            // Se já avaliou, não pode avaliar de novo
            if ($product->reviews()->where('user_id', auth()->id())->exists()) {
                $canReview = false;
            }
        }

        // CORREÇÃO AQUI: Apontando para a view correta 'products.show'
        return view('product.show', compact('product', 'canReview'));
    }

    // Salvar Avaliação
    public function storeReview(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        $hasBought = auth()->user()->orders()
            ->where('status', 'paid')
            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
            ->exists();

        if (!$hasBought) {
            return back()->with('error', 'Você precisa comprar o produto para avaliar.');
        }

        $product->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Avaliação enviada!');
    }

    // Salvar Pergunta (Cliente)
    public function storeQuestion(Request $request, Product $product)
    {
        $request->validate(['question' => 'required|string|max:300']);

        $product->questions()->create([
            'user_id' => auth()->id(),
            'question' => $request->question
        ]);

        return back()->with('success', 'Pergunta enviada!');
    }

    // Salvar Resposta (Criador)
    public function answerQuestion(Request $request, ProductQuestion $question)
    {
        if (auth()->id() !== $question->product->community->user_id) {
            abort(403);
        }

        $question->update([
            'answer' => $request->answer,
            'answered_at' => now()
        ]);

        return back()->with('success', 'Resposta enviada!');
    }
}