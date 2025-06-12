@extends('layouts.user')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-white mb-6">Мои бронирования</h2>

    @if($bookings->isEmpty())
        <div class="text-center py-8 bg-gray-800 rounded-lg">
            <svg class="mx-auto w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h4 class="mt-3 text-lg font-medium text-gray-300">Нет активных бронирований</h4>
            <p class="mt-1 text-gray-500">Забронируйте автомобиль для тест-драйва</p>
            <a href="{{ route('catalog') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg transition hover:bg-purple-700">
                Перейти в каталог
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-[#3C3C3C] hover:bg-gray-800 rounded-xl border border-gray-700 p-4 transition-all">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="mb-3 sm:mb-0">
                            <h4 class="font-semibold text-white">
                                {{ $booking->car->equipment->generation->carModel->brand->name }} {{ $booking->car->equipment->generation->carModel->name }}
                            </h4>
                            <div class="flex items-center mt-1">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm text-gray-400">{{ $booking->appointment_date->format('d.m.Y H:i') }}</span>
                                <span class="mx-2 text-gray-600">•</span>
                                <span class="text-sm 
                                @switch($booking->status)
                                    @case('pending') text-yellow-400 @break
                                    @case('confirmed') text-emerald-400 @break
                                    @case('rejected') text-rose-400 @break
                                    @case('cancelled') text-gray-400 @break
                                    @default text-blue-400
                                @endswitch">
                                @switch($booking->status)
                                    @case('pending') Ожидание @break
                                    @case('confirmed') Подтверждено @break
                                    @case('rejected') Отклонено @break
                                    @case('cancelled') Отменено @break
                                    @default Статус неизвестен
                                @endswitch
                            </span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('bookings.show', $booking) }}" class="px-3 py-1.5 text-sm bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                                Подробнее
                            </a>
                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Вы уверены, что хотите отменить бронирование?')" class="px-3 py-1.5 text-sm bg-rose-600/10 hover:bg-rose-600/20 border border-rose-600/30 text-rose-400 rounded-lg transition">
                                        Отменить
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection