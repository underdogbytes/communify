<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_id',
        'base_product_id',
        'name',
        'slug',
        'description',
        'profit',
        'image_path',   // Padronizado
        'file_artwork',
        'type',         // Novo
        'delivery_url', // Novo
        'is_active',
    ];

    protected $casts = [
        'profit' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name) . '-' . uniqid();
        });
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function baseProduct()
    {
        return $this->belongsTo(BaseProduct::class);
    }

    // --- MÁGICA ---
    // Retorna o preço total (Custo Base + Lucro)
    // Se for digital, Custo Base é 0, então retorna só o Lucro.
    public function getTotalPriceAttribute()
    {
        $baseCost = $this->baseProduct ? $this->baseProduct->base_price : 0;
        return $baseCost + $this->profit;
    }
}