<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\BaseProduct;

class ProductController extends Controller
{
    public function index()
    {
        $products = auth()->user()->community->products()->with('baseProduct')->latest()->get();
        return view('creator.products.index', compact('products'));
    }

    public function create()
    {
        $baseProducts = BaseProduct::all();
        return view('creator.products.create', compact('baseProducts'));
    }

    public function store(Request $request)
    {
        // 1. Validação Básica
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'profit' => 'required|numeric|min:0',
            'type' => 'required|in:physical,digital',
            'image' => 'nullable|image|max:2048', // Capa da Loja
        ];

        // 2. Validação Condicional
        if ($request->type === 'physical') {
            $rules['base_product_id'] = 'required|exists:base_products,id';
            // Arte é opcional no MVP, mas recomendada para POD
            $rules['file_artwork'] = 'nullable|image|max:5120'; 
        }

        if ($request->type === 'digital') {
            $rules['delivery_url'] = 'required|url';
        }

        $validated = $request->validate($rules);

        // 3. Uploads
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products/covers', 'public');
        }

        $artworkPath = null;
        if ($request->hasFile('file_artwork')) {
            $artworkPath = $request->file('file_artwork')->store('products/artwork', 'local');
        }

        // 4. Criação
        auth()->user()->community->products()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'profit' => $validated['profit'],
            'image_path' => $imagePath,     // Atenção: O banco agora usa image_path
            'file_artwork' => $artworkPath,
            'is_active' => true,
            'type' => $validated['type'],
            // Se for físico, usa o ID. Se for digital, NULL.
            'base_product_id' => $request->type === 'physical' ? $request->base_product_id : null,
            'delivery_url' => $request->type === 'digital' ? $request->delivery_url : null,
        ]);

        return redirect()->route('creator.produtos.index')->with('success', 'Produto criado com sucesso!');
    }

    public function destroy(Product $produto)
    {
        if ($produto->community_id !== auth()->user()->community->id) {
            abort(403);
        }
        $produto->delete();
        return back()->with('success', 'Produto removido.');
    }
}