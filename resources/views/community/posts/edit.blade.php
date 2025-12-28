<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Artigo - {{ $post->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .editor-toolbar { border-color: #e5e7eb; background: #fff; opacity: 1; border-radius: 8px 8px 0 0; }
        .CodeMirror { border-color: #e5e7eb; border-radius: 0 0 8px 8px; min-height: 500px; font-size: 1.1rem; padding: 10px; color: #374151; }
        .editor-statusbar { display: none; }
        .file-input-wrapper { position: relative; overflow: hidden; display: inline-block; width: 100%; }
        .file-input-wrapper input[type=file] { font-size: 100px; position: absolute; left: 0; top: 0; opacity: 0; cursor: pointer; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="{{ route('post.show', $post->slug) }}" class="text-gray-500 hover:text-gray-900 flex items-center gap-2 transition">
                        <i class="fa-solid fa-times"></i> Cancelar
                    </a>
                    <span class="mx-4 text-gray-300">|</span>
                    <span class="font-bold text-gray-700">Editando: {{ \Illuminate\Support\Str::limit($post->title, 30) }}</span>
                </div>
                <div>
                    <button form="edit-form" type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-full font-bold transition shadow-sm flex items-center gap-2">
                        <span>Salvar Alterações</span> <i class="fa-solid fa-check text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <form id="edit-form" action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <input type="text" name="title" 
                               value="{{ old('title', $post->title) }}"
                               placeholder="Título do seu artigo..." 
                               class="w-full text-4xl font-extrabold text-gray-900 border-none bg-transparent focus:ring-0 placeholder-gray-300 px-0 leading-tight" 
                               required autofocus>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm">
                        <textarea name="content" id="markdown-editor">{{ old('content', $post->content) }}</textarea>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-6">
                        <h3 class="font-bold text-gray-800 border-b pb-2 mb-4">Configurações</h3>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Imagem de Capa</label>
                            
                            <div id="image-preview" class="mb-2 {{ $post->image ? '' : 'hidden' }}">
                                <img src="{{ $post->image ? asset('storage/' . $post->image) : '' }}" class="w-full h-32 object-cover rounded-lg">
                            </div>

                            <div class="file-input-wrapper bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg h-12 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:border-indigo-400 transition cursor-pointer">
                                <span class="text-sm"><i class="fa-regular fa-image mr-1"></i> Alterar Capa</span>
                                <input type="file" name="image" accept="image/*" onchange="previewImage(this)">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Categoria</label>
                            <select name="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach(['Artigo', 'Diário', 'Nota', 'Atualização'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $post->category) == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tags</label>
                            <input type="text" name="tags" 
                                   value="{{ old('tags', implode(', ', $post->tags ?? [])) }}"
                                   placeholder="Ex: tecnologia, tutorial" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <p class="text-xs text-gray-500 mt-1">Separe por vírgula.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Quem pode ver?</label>
                            <select name="visibility" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="public" {{ old('visibility', $post->visibility) == 'public' ? 'selected' : '' }}>🌍 Público</option>
                                <option value="followers" {{ old('visibility', $post->visibility) == 'followers' ? 'selected' : '' }}>👥 Apenas Seguidores</option>
                            </select>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        const easyMDE = new EasyMDE({
            element: document.getElementById('markdown-editor'),
            spellChecker: false,
            toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "code", "|", "preview"],
            status: false,
            minHeight: "400px",
        });

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.querySelector('#image-preview img').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>