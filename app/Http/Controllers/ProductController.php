<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Página Pública de Detalhes do Produto (Híbrida)
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['community', 'baseProduct']) // Traz baseProduct SE existir
            ->firstOrFail();

        return view('product.show', compact('product'));
    }
}