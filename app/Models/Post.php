<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_id',
        'user_id',
        'title',       // Novo
        'slug',        // Novo
        'content',
        'image_path',
        'type',        // Novo
        'status',      // Novo
        'visibility',  // Novo
        'category',    // Novo
        'tags',        // Novo
        'settings',    // Novo
    ];

    // Isso aqui é mágica: converte o JSON do banco em Array PHP automaticamente
    protected $casts = [
        'tags' => 'array',
        'settings' => 'array',
    ];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}