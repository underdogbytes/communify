<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();

        // LÓGICA DE UPLOAD DE AVATAR
        if ($request->hasFile('avatar')) {
            // Se o usuário já tiver um avatar antigo (que não seja nulo), poderiamos deletar aqui.
            // Por segurança inicial, vamos apenas salvar o novo.
            
            // Salva na pasta 'storage/app/public/avatars'
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        // Preenche os dados (Nome, Email, Bio, Location e o caminho do Avatar)
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Exibe o perfil público do usuário
     */
    public function showPublic(\App\Models\User $user)
    {
        // Carrega a comunidade que ele criou (se tiver)
        $user->load('community');
        
        // Carrega as comunidades que ele segue
        $following = $user->follows()->latest()->get();

        return view('profile.public', compact('user', 'following'));
    }
    
}
