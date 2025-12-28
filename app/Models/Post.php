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
        'image',
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

    public function likes()
    {
        return $this->belongsToMany(User::class, 'post_likes');
    }

    public function getIsLikedAttribute()
    {
        if (!auth()->check()) return false;
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    // Apenas posts publicados
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

}