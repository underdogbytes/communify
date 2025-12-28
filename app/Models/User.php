<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Campos que podem ser preenchidos no cadastro/edição
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin', 
        'bio',      
        'avatar',   
        'full_name', 'cpf', 'address_street', 'address_number', 
        'address_complement', 'address_city', 'address_state', 'address_zip'
    ];

    /**
     * Campos escondidos (segurança)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversão de tipos
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    // --- RELACIONAMENTOS ---

    public function community()
    {
        return $this->hasOne(Community::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function follows()
    {
        return $this->belongsToMany(Community::class, 'follows', 'user_id', 'community_id');
    }
    
    public function likes()
    {
        return $this->belongsToMany(Post::class, 'post_likes');
    }

    // --- ACESSOR MÁGICO (NOVO) ---
    // Isso permite usar {{ auth()->user()->avatar_url }} na view
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Se não tiver foto, gera uma com as iniciais via API externa gratuita
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
    }
    
}