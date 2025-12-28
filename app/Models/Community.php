<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'cover_image',
        'profile_image',
        'instagram_handle',
        'youtube_handle',
        'whatsapp_group',
        'accent_color',
        'category',
    ];

    // Lista oficial de categorias do sistema
    public const CATEGORIES = [
        'Tecnologia',
        'Educação',
        'Negócios',
        'Saúde & Bem-estar',
        'Games',
        'Arte & Design',
        'Estilo de Vida',
        'Finanças',
        'Esportes',
        'Música',
        'Outros'
    ];
    
    /**
     * Evento automático: Quando criar a comunidade, gera o 'slug'
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($community) {
            $community->slug = Str::slug($community->name);
        });
        
        // CORREÇÃO: Ao editar o nome, atualiza o slug também?
        // Geralmente NÃO se muda slug para não quebrar links antigos.
        // Vamos deixar só no creating por segurança.
    }

    // --- RELACIONAMENTOS ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'community_id', 'user_id');
    }
}