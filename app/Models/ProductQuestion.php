<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductQuestion extends Model
{
    protected $fillable = ['user_id', 'product_id', 'question', 'answer', 'answered_at'];
    protected $casts = ['answered_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }
}