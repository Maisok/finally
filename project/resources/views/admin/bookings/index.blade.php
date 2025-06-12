@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Управление бронированиями</h1>

        <!-- Уведомления -->
        <div class="space-y-4 mb-6">
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Произошли ошибки:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm flex items-start">
                    <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-green-700">{{ session('success') }}</span>
                </div>
            @endif
        </div>

       <!-- Стилизованная форма -->
<div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200 mb-6">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-sm sm:text-base font-medium text-gray-700">Экспорт бронирований</h3>
    </div>
    <div class="p-4 sm:p-6">
        <form action="{{ route('export.bookings') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Статус -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                    <select name="status" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Все статусы</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ожидает подтверждения</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Отклонено</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершено</option>
                    </select>
                </div>

                <!-- Менеджер -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Менеджер</label>
                    <select name="manager_id" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                        <option value="">Все менеджеры</option>
                        @foreach ($managers as $manager)
                            <option value="{{ $manager->id }}" {{ request('manager_id') == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Дата с -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Дата от</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                </div>

                <!-- Дата по -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Дата до</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                </div>
            </div>

            <!-- Кнопки -->
            <div class="flex justify-end space-x-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Экспортировать
                </button>
            </div>
        </form>
    </div>
</div>

        <!-- Фильтры -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200 mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-sm sm:text-base font-medium text-gray-700">Фильтр бронирований</h3>
            </div>
            <div class="p-4 sm:p-6">
                <form action="{{ route('admin.bookings.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Менеджер</label>
                            <select name="manager_id" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2">
                                <option value="">Все менеджеры</option>
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ request('manager_id') == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                            <select name="status" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                                <option value="">Все статусы</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ожидание</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Отклонено</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершено</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Дата от</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                   class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Дата до</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                   class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Применить фильтры
                        </button>
                        <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Сбросить
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Таблица бронирований -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Автомобиль</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Клиент</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Менеджер</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $booking->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ optional($booking->car->equipment->generation->carModel->brand)->name ?? '-' }}
                                {{ optional($booking->car->equipment->generation->carModel)->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $booking->user->name }}<br>
                                <span class="text-xs">{{ $booking->user->email }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ optional($booking->manager)->name ?? 'Не назначен' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @switch($booking->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('confirmed') bg-green-100 text-green-800 @break
                                        @case('rejected') bg-red-100 text-red-800 @break
                                        @case('completed') bg-blue-100 text-blue-800 @break
                                        @default bg-gray-100 text-gray-800
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
                                <a href="{{ route('admin.bookings.edit-manager', $booking) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Назначить менеджера
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                Нет бронирований по заданным критериям
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Пагинация -->
            @if ($bookings->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Выберите значение",
        allowClear: true,
        width: '100%'
    });
});
</script>
@endsection