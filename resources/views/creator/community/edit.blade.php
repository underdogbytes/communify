<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Comunidade') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('creator.community.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="bg-white shadow sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Identidade Visual</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="cover_image" :value="__('Imagem de Capa (Banner)')" />
                                @if($community->cover_image)
                                    <img src="{{ asset('storage/' . $community->cover_image) }}" class="h-32 w-full object-cover rounded-lg mb-2 border">
                                @endif
                                <input type="file" name="cover_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <x-input-error class="mt-2" :messages="$errors->get('cover_image')" />
                            </div>

                            <div>
                                <x-input-label for="profile_image" :value="__('Logo / Avatar')" />
                                <div class="flex items-center gap-4">
                                    @if($community->profile_image)
                                        <img src="{{ asset('storage/' . $community->profile_image) }}" class="h-20 w-20 rounded-full object-cover border">
                                    @endif
                                    <input type="file" name="profile_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('profile_image')" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-input-label for="accent_color" :value="__('Cor de Destaque (Botões e Links)')" />
                            <div class="flex items-center gap-2 mt-1">
                                <input type="color" name="accent_color" value="{{ old('accent_color', $community->accent_color ?? '#4F46E5') }}" class="h-10 w-10 p-1 rounded border border-gray-300 cursor-pointer">
                                <span class="text-sm text-gray-500">Escolha a cor principal da sua marca</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200 space-y-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Informações</h3>

                        <div>
                            <x-input-label for="name" :value="__('Nome da Comunidade')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $community->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Descrição / Bio')" />
                            <textarea name="description" rows="4" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $community->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Categoria Principal</label>
                            <select name="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecione uma categoria...</option>
                                @foreach(\App\Models\Community::CATEGORIES as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $community->category) === $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200 space-y-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Redes Sociais & Links</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="instagram_handle" :value="__('Instagram (usuário sem @)')" />
                                <div class="flex rounded-md shadow-sm mt-1">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">instagram.com/</span>
                                    <input type="text" name="instagram_handle" value="{{ old('instagram_handle', $community->instagram_handle) }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300">
                                </div>
                            </div>

                            <div>
                                <x-input-label for="youtube_handle" :value="__('YouTube (usuário ou canal)')" />
                                <div class="flex rounded-md shadow-sm mt-1">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">youtube.com/</span>
                                    <input type="text" name="youtube_handle" value="{{ old('youtube_handle', $community->youtube_handle) }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300">
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="whatsapp_group" :value="__('Link do Grupo (WhatsApp/Telegram/Discord)')" />
                                <x-text-input id="whatsapp_group" name="whatsapp_group" type="url" class="mt-1 block w-full" :value="old('whatsapp_group', $community->whatsapp_group)" placeholder="https://chat.whatsapp.com/..." />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <x-primary-button class="px-6 py-3 text-lg">
                        {{ __('Salvar Alterações') }}
                    </x-primary-button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>