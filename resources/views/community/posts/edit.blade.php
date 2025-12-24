<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Artigo - {{ $post->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>

    <style>
        .editor-toolbar { border-color: #f3f4f6; background: #fff; opacity: 0.8; }
        .CodeMirror { border-color: #f3f4f6; border-radius: 8px; min-height: 400px; font-size: 1.1rem; padding: 10px; }
        .editor-statusbar { display: none; }
    </style>
</head>
<body class="bg-white text-gray-900 font-sans antialiased">

    <nav class="border-b border-gray-100 py-4">
        <div class="max-w-4xl mx-auto px-4 flex justify-between items-center">
            <a href="{{ route('post.show', $post->slug) }}" class="text-gray-500 hover:text-gray-900 flex items-center gap-2">
                &larr; Cancelar
            </a>
            <div class="font-bold text-gray-400 text-sm uppercase tracking-wide">
                Editando Artigo
            </div>
            <button form="edit-form" type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-full font-bold transition">
                Salvar Alterações
            </button>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-10">
        <form id="edit-form" action="{{ route('posts.update', $post->id) }}" method="POST">
            @csrf
            @method('PUT') <input type="text" 
                   name="title" 
                   value="{{ old('title', $post->title) }}"
                   placeholder="Título do seu artigo..." 
                   class="w-full text-4xl md:text-5xl font-extrabold text-gray-900 border-none focus:ring-0 placeholder-gray-300 px-0 mb-6"
                   required>

            <textarea name="content" id="markdown-editor">{{ old('content', $post->content) }}</textarea>
        </form>
    </div>

    <script>
        const easyMDE = new EasyMDE({
            element: document.getElementById('markdown-editor'),
            spellChecker: false,
            toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "|", "preview"],
            status: false,
            initialValue: document.getElementById('markdown-editor').value, // Garante que carrega o texto
        });
    </script>

</body>
</html>