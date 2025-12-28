<?php

use Illuminate\Support\Facades\Route;

// Importando os Controladores
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;

// Controladores CRIADOR e ADMIN
use App\Http\Controllers\Creator\DashboardController as CreatorDashboardController;
use App\Http\Controllers\Creator\CommunityController as CreatorCommunityController;
use App\Http\Controllers\Creator\PostController as CreatorPostController;
use App\Http\Controllers\Creator\ProductController as CreatorProductController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CommunityController as AdminCommunityController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === JORNADA 1: PÚBLICA ===
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/explorar', [CommunityController::class, 'index'])->name('community.index');
Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tags.show');
Route::get('/c/{slug}', [CommunityController::class, 'show'])->name('community.show');
Route::get('/u/{user}', [ProfileController::class, 'showPublic'])->name('user.public');
Route::get('/loja/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/p/{slug}', [PostController::class, 'show'])->name('post.show');

// === ROTAS PADRÃO DO BREEZE ===
Route::get('/dashboard', [HomeController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
});

// === JORNADA 2: LEITOR/COMPRADOR ===
Route::middleware('auth')->group(function () {
    Route::post('/community/{community}/follow', [CommunityController::class, 'follow'])->name('community.follow');
    Route::get('/community/{community}/escrever', [PostController::class, 'createArticle'])->name('community.posts.create');
    Route::post('/community/{community}/posts', [PostController::class, 'store'])->name('community.posts.store');

    Route::post('/post/{post}/comment', [PostController::class, 'storeComment'])->name('post.comment.store');
    Route::delete('/comments/{id}', [PostController::class, 'destroyComment'])->name('comments.destroy');
    Route::post('/post/{post}/like', [PostController::class, 'toggleLike'])->name('post.like');

    // Carrinho e Checkout
    Route::get('/carrinho', [OrderController::class, 'cart'])->name('order.cart');
    Route::post('/checkout', [OrderController::class, 'store'])->name('order.store');
    
    // Gestão do Carrinho
    Route::delete('/order/item/{item}', [OrderController::class, 'removeItem'])->name('order.item.remove');
    Route::patch('/order/item/{item}/update', [OrderController::class, 'updateItemQuantity'])->name('order.item.update');

    // Finalização e Histórico
    Route::put('/meus-pedidos/{order}/finalize', [OrderController::class, 'finalize'])->name('order.finalize');
    // ROTA IMPORTANTE DE SUCESSO:
    Route::get('/pedido/{order}/obrigado', [OrderController::class, 'success'])->name('order.success');
    
    Route::get('/meus-pedidos', [OrderController::class, 'index'])->name('order.index');
    Route::get('/meus-pedidos/{order}', [OrderController::class, 'show'])->name('order.show');
    Route::post('/meus-pedidos/{order}/upload', [OrderController::class, 'uploadProof'])->name('order.upload_proof');

    // Notificações
    Route::get('/notificacoes', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return view('notifications.index', [
            'notifications' => auth()->user()->notifications()->paginate(10)
        ]);
    })->name('notifications.index');

    // Avaliações e Perguntas
    Route::post('/produto/{product}/avaliar', [ProductController::class, 'storeReview'])->name('product.review.store');
    Route::post('/produto/{product}/perguntar', [ProductController::class, 'storeQuestion'])->name('product.question.store');
    Route::put('/pergunta/{question}/responder', [ProductController::class, 'answerQuestion'])->name('product.question.answer');
});

// === JORNADA 3: CRIADOR ===
Route::middleware(['auth'])->prefix('criador')->name('creator.')->group(function () {
    Route::get('/comunidade/criar', [CreatorCommunityController::class, 'create'])->name('community.create');
    Route::post('/comunidade', [CreatorCommunityController::class, 'store'])->name('community.store');
    Route::get('/painel', [CreatorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/comunidade/editar', [CreatorCommunityController::class, 'edit'])->name('community.edit');
    Route::put('/comunidade', [CreatorCommunityController::class, 'update'])->name('community.update');
    
    Route::resource('/posts', CreatorPostController::class);
    Route::resource('/produtos', CreatorProductController::class);
    
    Route::get('/pedidos', [CreatorDashboardController::class, 'order'])->name('order.index');
    Route::get('/pedidos/{order}', [CreatorDashboardController::class, 'showOrder'])->name('order.show');
    Route::put('/pedidos/{order}', [CreatorDashboardController::class, 'updateOrder'])->name('order.update');

    Route::get('/moderacao', [CreatorPostController::class, 'moderation'])->name('posts.moderation');
    Route::patch('/posts/{id}/approve', [CreatorPostController::class, 'approve'])->name('posts.approve');
    Route::delete('/posts/{id}/reject', [CreatorPostController::class, 'reject'])->name('posts.reject');
});

// === JORNADA 4: ADMIN ===
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/usuarios', AdminUserController::class);
    Route::get('/usuarios/{user}/login-as', [AdminUserController::class, 'loginAs'])->name('users.login-as');
    Route::resource('/comunidades', AdminCommunityController::class);
    Route::resource('/produtos', AdminProductController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('/pedidos', AdminOrderController::class)->only(['index', 'show', 'update']);
});

require __DIR__.'/auth.php';