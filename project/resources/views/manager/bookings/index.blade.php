@extends('layouts.user')

@section('content')
<section class="py-10">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold text-white mb-6">Управление бронированиями</h1>

        <!-- Уведомления -->
        @if(session('success'))
            <div class="bg-green-500/20 border-l-4 border-green-500 text-green-300 p-4 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/20 border-l-4 border-red-500 text-red-300 p-4 mb-6 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Фильтры -->
<div class="bg-[#3C3C3C] rounded-xl shadow-lg p-6 mb-6">
    <form action="{{ route('manager.bookings.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <!-- Статус -->
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Статус</label>
            <select name="status"
                    class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">Все</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ожидание</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Отклонено</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершено</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Тип бронирования</label>
            <select name="type"
                    class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">Все</option>
                <option value="mine" {{ request('type') == 'mine' ? 'selected' : '' }}>Мои</option>
                <option value="free" {{ request('type') == 'free' ? 'selected' : '' }}>Свободные</option>
            </select>
        </div>
        <!-- Дата от -->
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Дата от</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>

        <!-- Дата до -->
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Дата до</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>

        <!-- Кнопки -->
        <div class="flex space-x-3">
            <button type="submit"
                    class="px-4 py-3 bg-purple-600 hover:bg-purple-700 text-[#ffffff] rounded-lg transition-all">
                Применить
            </button>
            <a href="{{ route('manager.bookings.index') }}"
               class="px-4 py-3 bg-[#3c3c3c] hover:bg-gray-600 text-[#ffffff] rounded-lg transition-all">
                Сбросить
            </a>
        </div>
    </form>


   
</div>

        <!-- Таблица -->
        <div class="bg-[#3C3C3C] rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-[#2D2D2D]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Автомобиль</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Клиент</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Дата брони</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Дата визита</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Статус</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($bookings as $booking)
                        <tr class="{{ $booking->status === 'rejected' ? 'bg-red-900/10' : ($booking->status === 'confirmed' ? 'bg-green-900/10' : '') }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">#{{ $booking->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-white">
                                    {{ $booking->car->equipment->generation->carModel->brand->name }} 
                                    {{ $booking->car->equipment->generation->carModel->name }}
                                </div>
                                <div class="text-xs text-gray-400">VIN: {{ $booking->car->vin }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-white">{{ $booking->user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $booking->user->email }}</div>
                                <div class="text-xs text-gray-400">{{ $booking->user->phone ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $booking->booking_date->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $booking->appointment_date }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch($booking->status)
                                    @case('pending') bg-yellow-500/20 text-yellow-400 @break
                                    @case('confirmed') bg-green-500/20 text-green-400 @break
                                    @case('rejected') bg-red-500/20 text-red-400 @break
                                    @case('completed') bg-blue-500/20 text-blue-400 @break
                                    @default bg-gray-500/20 text-gray-400
                                @endswitch">
                                {{ match($booking->status) {
                                    'pending' => 'Ожидание',
                                    'confirmed' => 'Подтверждено',
                                    'rejected' => 'Отклонено',
                                    'completed' => 'Завершено',
                                    default => 'Неизвестный статус',
                                } }}
                            </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right space-x-2">
                                @if(is_null($booking->manager_id) && $booking->status !== 'rejected')
                                    <form action="{{ route('manager.bookings.assign', $booking) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1 bg-purple-600 hover:bg-purple-600 text-[#ffffff] rounded transition-all">
                                            Принять в работу
                                        </button>
                                    </form>
                                @else
                                
                                    @if($booking->manager_id == auth()->id())
                                    @if($booking->status !== 'completed')
                                        <a href="{{ route('manager.bookings.edit', $booking) }}"
                                           class="inline-block px-3 py-1 bg-purple-600 hover:bg-purple-700 text-[#ffffff] rounded transition-all">
                                            Редактировать
                                        </a>
                                        @endif
                                       
                                    @else
                                        <span class="text-gray-500">Занято</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-400">
                                Нет бронирований по заданным критериям
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Пагинация -->
            @if($bookings->hasPages())
                <div class="bg-[#2D2D2D] px-6 py-4 border-t border-gray-700">
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection