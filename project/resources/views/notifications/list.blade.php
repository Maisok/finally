@extends('layouts.user')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Ваши уведомления</h1>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        @forelse($notifications as $notification)
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 {{ is_null($notification->read_at) ? 'bg-blue-50 dark:bg-gray-750' : '' }}">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <p class="text-gray-800 dark:text-gray-200">{{ $notification->message }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <a href="{{ $notification->url }}" class="ml-4 text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                        Перейти
                    </a>
                </div>
            </div>
        @empty
            <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                У вас пока нет уведомлений
            </div>
        @endforelse
    </div>

    <!-- Пагинация -->
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
@endsection