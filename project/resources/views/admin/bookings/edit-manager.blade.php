@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Назначить/изменить менеджера</h1>

        <!-- Информация о бронировании -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Клиент -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Клиент</h3>
                    <p><strong>Имя:</strong> {{ $booking->user->name }}</p>
                    <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                    <p><strong>Телефон:</strong> {{ $booking->user->phone ?? 'Не указан' }}</p>
                </div>

                <!-- Автомобиль -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Автомобиль</h3>
                    @if($booking->car)
                        <p><strong>Модель:</strong> 
                            {{ optional(optional($booking->car->equipment)->generation)->carModel->brand->name }}
                            {{ optional(optional($booking->car->equipment)->generation)->carModel->name }}
                        </p>
                        <p><strong>VIN:</strong> {{ $booking->car->vin }}</p>
                        <p><strong>Цена:</strong> {{ number_format($booking->car->price, 0, '', ' ') }} ₽</p>
                        <p><strong>Филиал:</strong> {{ optional($booking->car->branch)->name }}</p>
                    @else
                        <p class="text-gray-500">Данные автомобиля отсутствуют</p>
                    @endif
                </div>

                <!-- Бронирование -->
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Бронирование</h3>
                    <p><strong>Статус:</strong> {{ $booking->status_label }}</p>
                    <p><strong>Дата визита:</strong> 
                        {{ $booking->appointment_date ? $booking->appointment_date->format('d.m.Y H:i') : 'Не указана' }}
                    </p>
                    <p><strong>Дата брони:</strong> {{ $booking->created_at->format('d.m.Y H:i') }}</p>
                </div>

                <!-- Комментарий менеджера -->
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Комментарий менеджера</h3>
                    <p>{{ $booking->manager_comment ?: '—' }}</p>
                </div>
            </div>
        </div>

        <!-- Форма изменения менеджера -->
        <form action="{{ route('admin.bookings.update-manager', $booking) }}" method="POST" class="mb-6">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="manager_id" class="block text-sm font-medium text-gray-700 mb-1">Менеджер</label>
                <select id="manager_id" name="manager_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Не назначен</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->id }}" {{ old('manager_id', $booking->manager_id) == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }} ({{ $manager->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Кнопки -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.bookings.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Назад
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Сохранить изменения
                </button>
            </div>
        </form>
    </div>
</div>
@endsection