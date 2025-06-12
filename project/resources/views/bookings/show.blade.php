@extends('layouts.user')

@section('content')
<section class="py-10">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto bg-[#3C3C3C] rounded-xl shadow-lg overflow-hidden">
            <!-- Заголовок -->
            <div class="px-6 py-4 bg-gray-800 border-b border-gray-700">
                <h2 class="text-2xl font-bold text-white">Бронирование #{{ $booking->id }}</h2>
            </div>

            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <!-- Информация об автомобиле -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-300 mb-3">Автомобиль</h3>
                        <div class="space-y-2">
                            <p><strong class="text-gray-400">Марка:</strong> <span class="text-white">{{ $booking->car->equipment->generation->carModel->brand->name }}</span></p>
                            <p><strong class="text-gray-400">Модель:</strong> <span class="text-white">{{ $booking->car->equipment->generation->carModel->name }}</span></p>
                            <p><strong class="text-gray-400">Поколение:</strong> <span class="text-white">{{ $booking->car->equipment->generation->name }}</span></p>
                            <p><strong class="text-gray-400">Цена:</strong> <span class="text-purple-400">{{ number_format($booking->car->price, 0, '', ' ') }} ₽</span></p>
                            <p><strong class="text-gray-400">VIN:</strong> <span class="text-white">{{ $booking->car->vin }}</span></p>
                        </div>
                    </div>

                    <!-- Информация о бронировании -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-300 mb-3">Детали бронирования</h3>
                        <div class="space-y-2">
                            <p><strong class="text-gray-400">Статус:</strong>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                @switch($booking->status)
                                    @case('pending') bg-yellow-500/20 text-yellow-400 @break
                                    @case('confirmed') bg-green-500/20 text-green-400 @break
                                    @case('rejected') bg-red-500/20 text-red-400 @break
                                    @case('completed') bg-purple-600  text-[#ffffff] @break
                                    @default bg-blue-500/20 text-blue-400
                                @endswitch">
                                @switch($booking->status)
                                    @case('pending') Ожидание @break
                                    @case('confirmed') Подтверждено @break
                                    @case('rejected') Отклонено @break
                                    @case('completed') Выполнено  @break
                                    @default Статус неизвестен
                                @endswitch
                            </span>
                            </p>
                            <p><strong class="text-gray-400">Дата бронирования:</strong> <span class="text-white">{{ $booking->booking_date->format('d.m.Y H:i') }}</span></p>
                            @if($booking->appointment_date)
                                <p>
                                    <strong class="text-gray-400">Дата визита:</strong> 
                                    <span class="text-white">{{ $booking->appointment_date->format('d.m.Y H:i') }}</span>
                                </p>
                            @else
                                <p>
                                    <strong class="text-gray-400">Дата визита:</strong> 
                                    <span class="text-white">Не указана</span>
                                </p>
                            @endif
                            @if($booking->manager_comment)
                                <p><strong class="text-gray-400">Комментарий менеджера:</strong> <span class="text-white">{{ $booking->manager_comment }}</span></p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Информация о филиале -->
                <div class="bg-[#2D2D2D] p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-300 mb-3">Филиал для визита</h3>
                    <div class="space-y-1">
                        <p><strong class="text-gray-400">Название:</strong> <span class="text-white">{{ $booking->car->branch->name }}</span></p>
                        <p><strong class="text-gray-400">Адрес:</strong> <span class="text-white">{{ $booking->car->branch->address }}</span></p>
                        @if($booking->manager)
                            <p><strong class="text-gray-400">Телефон:</strong> <span class="text-white">{{ $booking->manager->phone }}</span></p>
                            <p><strong class="text-gray-400">Email:</strong> <span class="text-white">{{ $booking->manager->email }}</span></p>
                        @else
                            <p><strong class="text-gray-400">Менеджер:</strong><span class="text-white"> Не назначен</span></p>
                            @endif
                    </div>
                </div>

                <!-- Кнопки управления -->
                <div class="mt-6 flex justify-between items-center">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Назад в личный кабинет
                    </a>

                    @if(in_array($booking->status, ['pending', 'confirmed']))
                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Вы уверены, что хотите отменить бронирование?')"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all">
                                Отменить бронирование
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection