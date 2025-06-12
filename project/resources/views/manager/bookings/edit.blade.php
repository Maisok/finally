@extends('layouts.user')

@section('content')
<section class="py-10">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
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
            <div class="bg-[#3C3C3C] rounded-xl shadow-lg overflow-hidden">
                <!-- Заголовок -->
                <div class="px-6 py-4 bg-gray-800 border-b border-gray-700">
                    <h2 class="text-xl font-bold text-white">Редактирование бронирования #{{ $booking->id }}</h2>
                </div>

                <!-- Контент -->
                <div class="p-6">
                    <!-- Информация о клиенте и автомобиле -->
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <!-- Информация о клиенте -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-300 mb-3">Информация о клиенте</h3>
                            <div class="space-y-2">
                                <p><strong class="text-gray-400">Имя:</strong> <span class="text-white">{{ $booking->user->name }}</span></p>
                                <p><strong class="text-gray-400">Email:</strong> <span class="text-white">{{ $booking->user->email }}</span></p>
                                <p><strong class="text-gray-400">Телефон:</strong> <span class="text-white">{{ $booking->user->phone ?? 'Не указан' }}</span></p>
                            </div>
                        </div>

                        <!-- Информация об автомобиле -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-300 mb-3">Информация об автомобиле</h3>
                            <div class="space-y-2">
                                <p>
                                    <strong class="text-gray-400">Автомобиль:</strong> 
                                    <span class="text-white">
                                        {{ $booking->car->equipment->generation->carModel->brand->name }}
                                        {{ $booking->car->equipment->generation->carModel->name }}
                                    </span>
                                </p>
                                <p><strong class="text-gray-400">VIN:</strong> <span class="text-white">{{ $booking->car->vin }}</span></p>
                                <p><strong class="text-gray-400">Цена:</strong> <span class="text-purple-400">{{ number_format($booking->car->price, 0, '', ' ') }} ₽</span></p>
                                <p><strong class="text-gray-400">Филиал:</strong> <span class="text-white">{{ $booking->car->branch->name }}</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Форма редактирования -->
                    <form action="{{ route('manager.bookings.update', $booking) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Статус + Дата визита -->
                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <!-- Статус -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-400 mb-2">Статус бронирования</label>
                                <select id="status" name="status"
                                class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>Ожидание</option>
                            <option value="confirmed" {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                            <option value="rejected" {{ old('status', $booking->status) == 'rejected' ? 'selected' : '' }}>Отклонено</option>
                            <option value="completed" {{ old('status', $booking->status) == 'completed' ? 'selected' : '' }}>Завершено</option>
                        </select>
                            </div>

                            <!-- Дата визита -->
                            <div>
                                <label for="appointment_date" class="block text-sm font-medium text-gray-400 mb-2">Дата визита</label>

                                <input type="datetime-local"
                                name="appointment_date"
                                value="{{ old('appointment_date', $booking->appointment_date?->format('Y-m-d\TH:i')) }}"
                                min="{{ now()->startOfDay()->format('Y-m-d\TH:i') }}"
                                max="{{ now()->addDays(7)->endOfDay()->format('Y-m-d\TH:i') }}"
                                class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>

                        <!-- Цена автомобиля -->
                        <div>
                            <label for="car_price" class="block text-sm font-medium text-gray-400 mb-2">Цена автомобиля</label>
                            <input type="number"
                                id="car_price"
                                name="car_price"
                                value="{{ old('car_price', $booking->car->price) }}"
                                min="1000"
                                step="1000"
                                class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>

                        <!-- Комментарий менеджера -->
                        <div class="mb-6">
                            <label for="manager_comment" class="block text-sm font-medium text-gray-400 mb-2">Комментарий менеджера</label>
                            <textarea id="manager_comment" name="manager_comment" rows="4" maxlength="1000"
          class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('manager_comment', $booking->manager_comment) }}</textarea>
                        </div>

                        <!-- Кнопки -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('manager.bookings.index') }}"
                               class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-all">
                                Назад
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-[#ffffff] rounded-lg transition-all">
                                Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection