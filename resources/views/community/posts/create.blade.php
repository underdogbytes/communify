<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Escrever Artigo - {{ $community->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>

    <style>
        /* Ajustes finos para o editor ficar bonito */
        .editor-toolbar { border-color: #f3f4f6; background: #fff; opacity: 0.8; }
        .CodeMirror { border-color: #f3f4f6; border-radius: 8px; min-height: 400px; font-size: 1.1rem; padding: 10px; }
        .editor-statusbar { display: none; } /* Esconde barra inferior feia */
    </style>
</head>
<body class="bg-white text-gray-900 font-sans antialiased">

    <nav class="border-b border-gray-100 py-4">
        <div class="max-w-4xl mx-auto px-4 flex justify-between items-center">
            <a href="{{ route('community.show', $community->slug) }}" class="text-gray-500 hover:text-gray-900 flex items-center gap-2">
                &larr; Voltar
            </a>
            <div class="font-bold text-gray-400 text-sm uppercase tracking-wide">
                Rascunho em {{ $community->name }}
            </div>
            <button form="article-form" type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-full font-bold transition">
                Publicar
            </button>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-10">
        <form id="article-form" action="{{ route('community.posts.store', $community) }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="article">

            <input type="text" 
                   name="title" 
                   placeholder="Título do seu artigo..." 
                   class="w-full text-4xl md:text-5xl font-extrabold text-gray-900 border-none focus:ring-0 placeholder-gray-300 px-0 mb-6"
                   required autofocus>

            <textarea name="content" id="markdown-editor" placeholder="Escreva sua história aqui..."></textarea>
        </form>
    </div>

    <script>
        const easyMDE = new EasyMDE({
            element: document.getElementById('markdown-editor'),
            spellChecker: false,
            placeholder: "Comece a escrever...",
            toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "|", "preview"],
            status: false,
            autosave: {
                enabled: true,
                uniqueId: "article_draft_{{ $community->id }}",
                delay: 1000,
            },
        });
    </script>

</body>
</html>