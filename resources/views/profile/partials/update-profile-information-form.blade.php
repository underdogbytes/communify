<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informações do Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Atualize suas informações de conta, bio e endereço de entrega.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="space-y-6">
                <div>
                    <x-input-label for="avatar" :value="__('Foto de Perfil')" />
                    
                    <div class="flex items-center gap-4 mt-2">
                        <img src="{{ $user->avatar_url }}" class="w-16 h-16 rounded-full border border-gray-200 object-cover">
                        <input id="avatar" name="avatar" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                </div>

                <div>
                    <x-input-label for="name" :value="__('Nome de Usuário (Público)')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-gray-800">
                                {{ __('Your email address is unverified.') }}

                                <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div>
                    <x-input-label for="bio" :value="__('Bio (Sobre você)')" />
                    <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Conte um pouco sobre seus interesses...">{{ old('bio', $user->bio) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg space-y-4 border border-gray-200">
                <h3 class="font-bold text-gray-700 border-b pb-2 mb-4">Dados para Entrega 📦</h3>

                <div>
                    <x-input-label for="full_name" :value="__('Nome Completo')" />
                    <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" :value="old('full_name', $user->full_name)" />
                </div>

                <div>
                    <x-input-label for="cpf" :value="__('CPF')" />
                    <x-text-input id="cpf" name="cpf" type="text" class="mt-1 block w-full" :value="old('cpf', $user->cpf)" placeholder="000.000.000-00" />
                </div>

                <div>
                    <x-input-label for="address_zip" :value="__('CEP')" />
                    <x-text-input id="address_zip" name="address_zip" type="text" class="mt-1 block w-full" :value="old('address_zip', $user->address_zip)" />
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <x-input-label for="address_street" :value="__('Rua / Avenida')" />
                        <x-text-input id="address_street" name="address_street" type="text" class="mt-1 block w-full" :value="old('address_street', $user->address_street)" />
                    </div>
                    <div>
                        <x-input-label for="address_number" :value="__('Número')" />
                        <x-text-input id="address_number" name="address_number" type="text" class="mt-1 block w-full" :value="old('address_number', $user->address_number)" />
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <x-input-label for="address_city" :value="__('Cidade')" />
                        <x-text-input id="address_city" name="address_city" type="text" class="mt-1 block w-full" :value="old('address_city', $user->address_city)" />
                    </div>
                    <div>
                        <x-input-label for="address_state" :value="__('Estado')" />
                        <x-text-input id="address_state" name="address_state" type="text" class="mt-1 block w-full" :value="old('address_state', $user->address_state)" placeholder="UF" />
                    </div>
                </div>
            </div>

        </div>

        <div class="flex items-center gap-4 mt-6">
            <x-primary-button>{{ __('Salvar Alterações') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-bold"
                >{{ __('Perfil atualizado com sucesso!') }}</p>
            @endif
        </div>
    </form>
</section>