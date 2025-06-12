<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    public function store(Request $request, Car $car)
    {
        // Проверка: продан ли автомобиль
        if ($car->is_sold) {
            return back()->with('error', 'Этот автомобиль уже продан');
        }
    
        try {
            $user = Auth::user();
    
            // 1. Проверка: не превышено ли количество активных броней у авто (максимум 10)
            $activeBookingsCount = Booking::where('car_id', $car->id)
                ->whereIn('status', ['pending'])
                ->count();
    
            if ($activeBookingsCount >= 10) {
                return back()->with('error', 'На этот автомобиль уже забронировано максимальное количество слотов (10)');
            }
    
            // 2. Проверка: не превышено ли количество броней у пользователя за сегодняшний день
            $bookingsTodayCount = Booking::where('user_id', $user->id)
                ->whereDate('created_at', Carbon::today())
                ->count();
    
            if ($bookingsTodayCount >= 10) {
                return back()->with('error', 'Вы не можете создать более 10 бронирований в день');
            }
    
            // === Логика выбора даты ===
            $date = Carbon::now()->addDay(); // Сначала проверяем завтра
    
            // Пропускаем выходные
            while ($date->isSaturday() || $date->isSunday()) {
                $date->addDay();
            }
    
            // Устанавливаем время на 9:00 утра
            $date->setTime(9, 0);
    
            // Создание брони
            $booking = Booking::create([
                'user_id' => $user->id,
                'car_id' => $car->id,
                // Теперь дата указывает на ближайший будний день в 9:00
                'status' => 'pending',
            ]);
    
            return back()->with([
                'success' => 'Автомобиль успешно забронирован!',
                'booking_info' => [
                    'id' => $booking->id,
                   
                    'status' => 'Ожидание подтверждения',
                    'car' => $car->fullName,
                    'branch' => $car->branch->name,
                    'address' => $car->branch->address,
                ]
            ]);
    
        } catch (\Exception $e) {
            return back()->with('error', 'Произошла ошибка при бронировании: ' . $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('bookings.show', compact('booking'));
    }

    public function index()
    {
        $user = Auth::user();
        $bookings = $user->bookings()->with('car.equipment.generation.carModel.brand')->latest()->get();

        return view('bookings.index', compact('bookings'));
    }


    public function cancel(Booking $booking)
    {
        // Проверка прав доступа
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->update(['status' => 'rejected']);
        
        return back()->with('status', 'Бронирование успешно отменено');
    }
} 