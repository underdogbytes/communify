<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue; // Opcional, se quiser fila
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Models\Post;

class NewInteraction extends Notification
{
    use Queueable;

    public $user;
    public $post;
    public $type;

    public function __construct(User $user, Post $post, $type)
    {
        $this->user = $user;
        $this->post = $post;
        $this->type = $type;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        // Define o Link correto baseando-se no tipo do post
        $link = '#';
        
        if ($this->post->slug) {
            // Se for Artigo, vai para a página do post
            $link = route('post.show', $this->post->slug);
        } else {
            // Se for Short Post (sem slug), vai para a comunidade
            // (Futuramente podemos adicionar #post-123 na view da comunidade para rolar até ele)
            $link = route('community.show', $this->post->community->slug);
        }

        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_avatar' => $this->user->avatar_url,
            'post_id' => $this->post->id,
            'post_slug' => $this->post->slug, 
            'type' => $this->type,
            'message' => $this->type === 'like' ? "curtiu seu post." : "comentou no seu post.",
            'link' => $link, // <--- Link seguro agora
        ];
    }
}