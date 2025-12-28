<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notificações') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    @forelse($notifications as $notification)
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-100 hover:bg-gray-100 transition">
                            <div class="mr-4">
                                @if($notification->data['type'] == 'like')
                                    <div class="h-10 w-10 rounded-full bg-red-100 text-red-500 flex items-center justify-center">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                @else
                                    <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center">
                                        <i class="fa-solid fa-comment"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1">
                                <p class="text-sm">
                                    <span class="font-bold">{{ $notification->data['user_name'] }}</span>
                                    {{ $notification->data['message'] }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>

                            @if(isset($notification->data['post_slug']))
                                <a href="{{ route('post.show', $notification->data['post_slug']) }}" class="text-sm text-indigo-600 font-bold hover:underline">
                                    Ver Post
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-10">
                            Nenhuma notificação por enquanto.
                        </div>
                    @endforelse

                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>