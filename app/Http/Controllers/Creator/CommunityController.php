<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Community;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CommunityController extends Controller
{
    public function create()
    {
        if (auth()->user()->community) {
            return redirect()->route('creator.dashboard');
        }
        return view('creator.community.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:communities',
            'description' => 'required|string|max:1000',
            'cover_image' => 'nullable|image|max:2048',
            'profile_image' => 'nullable|image|max:1024',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('communities/covers', 'public');
        }

        $profilePath = null;
        if ($request->hasFile('profile_image')) {
            $profilePath = $request->file('profile_image')->store('communities/profiles', 'public');
        }

        auth()->user()->community()->create([
            'name' => $request->name,
            'description' => $request->description,
            'cover_image' => $coverPath,
            'profile_image' => $profilePath,
        ]);

        return redirect()->route('creator.dashboard')->with('success', 'Comunidade criada com sucesso!');
    }

    // --- MÉTODOS DE EDIÇÃO (NOVOS) ---

    /**
     * Mostra o formulário de edição
     */
    public function edit()
    {
        $community = auth()->user()->community;
        
        // Segurança: Só edita se tiver comunidade
        if (!$community) abort(404);

        return view('creator.community.edit', compact('community'));
    }

    /**
     * Atualiza os dados no banco
     */
    public function update(Request $request)
    {
        $community = auth()->user()->community;

        // Validação
        $request->validate([
            'name' => 'required|string|max:255|unique:communities,name,' . $community->id, // Ignora o próprio ID na verificação de único
            'description' => 'required|string|max:1000',
            'cover_image' => 'nullable|image|max:2048',
            'profile_image' => 'nullable|image|max:1024',
            'instagram_handle' => 'nullable|string|max:50',
            'youtube_handle' => 'nullable|string|max:100',
            'whatsapp_group' => 'nullable|url',
            'accent_color' => 'nullable|string|size:7', // Hex code #RRGGBB
            'category' => 'nullable|string|in:' . implode(',', \App\Models\Community::CATEGORIES),
        ]);

        $community->update([
            'name' => $request->name,
            'description' => $request->description,
            'accent_color' => $request->accent_color,
            'category' => $request->category, // <--- SALVANDO
            // ... handles sociais ...
        ]);

        $data = $request->only([
            'name', 'description', 'instagram_handle', 'youtube_handle', 'whatsapp_group', 'accent_color'
        ]);

        // Upload de Capa (se enviou nova)
        if ($request->hasFile('cover_image')) {
            // Deleta antiga se existir
            if ($community->cover_image) Storage::disk('public')->delete($community->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('communities/covers', 'public');
        }

        // Upload de Perfil (se enviou nova)
        if ($request->hasFile('profile_image')) {
            if ($community->profile_image) Storage::disk('public')->delete($community->profile_image);
            $data['profile_image'] = $request->file('profile_image')->store('communities/profiles', 'public');
        }

        $community->update($data);

        return back()->with('success', 'Comunidade atualizada!');
    }
}