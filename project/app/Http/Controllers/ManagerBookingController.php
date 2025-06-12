<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ManagerBookingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
    
        $query = Booking::with(['user', 'car.equipment.generation.carModel.brand', 'manager']);
    
        if ($user->role === 'manager') {
            // Если указан тип, применяем его приоритетно
            if ($request->filled('type')) {
                if ($request->type === 'mine') {
                    $query->where('manager_id', $user->id);
                } elseif ($request->type === 'free') {
                    $query->whereNull('manager_id');
                }
            } else {
                // По умолчанию показываем и свои, и свободные
                $query->whereNull('manager_id')->orWhere('manager_id', $user->id);
            }
        }
    
        $bookings = $query
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->date_from, fn($q) => $q->where('booking_date', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->where('booking_date', '<=', $request->date_to))
            ->orderByDesc('booking_date')
            ->paginate(15)
            ->appends($request->query());
    
        return view('manager.bookings.index', compact('bookings'));
    }

    public function assignToManager(Booking $booking)
    {
        $user = auth()->user();

        if (!$booking->manager_id) {
            $booking->update(['manager_id' => $user->id]);
            return redirect()->back()->with('success', 'Бронирование взято в работу');
        }

        return redirect()->back()->with('error', 'Это бронирование уже назначено другому менеджеру');
    }

    public function edit(Booking $booking)
    {
        $user = auth()->user();
    
        if ($booking->manager_id && $booking->manager_id !== $user->id) {
            abort(403, 'Доступ запрещён');
        }
    
        return view('manager.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $user = auth()->user();
    
        if ($booking->manager_id && $booking->manager_id !== $user->id) {
            abort(403, 'Вы не можете редактировать это бронирование');
        }

        if ($booking->status==='completed') {
            abort(403, 'Вы не можете редактировать это бронирование');
        }

        $hasCompletedBooking = Booking::where('car_id', $booking->car_id)
            ->where('id', '!=', $booking->id)
            ->where('status', 'completed')
            ->exists();

        if ($hasCompletedBooking) {
            throw ValidationException::withMessages([
                'status' => ['Вы не можете редактировать это бронирование.']
            ]);
          
        }

        $appointmentDate = $request->input('appointment_date');

if (!empty($appointmentDate)) {
    try {
        $date = Carbon::createFromFormat('Y-m-d\TH:i', $appointmentDate);

        // Проверка: не выходной
        if ($date->isWeekend()) {
            throw ValidationException::withMessages([
                'appointment_date' => ['Выбранная дата приходится на выходной. Пожалуйста, выберите будний день.']
            ]);
        }

        // Проверка: только с 10:00 до 19:00
        $hour = $date->hour;

        if ($hour < 10 || $hour >= 19) {
            throw ValidationException::withMessages([
                'appointment_date' => ['Время должно быть в диапазоне с 10:00 до 19:00.']
            ]);
        }

        // Проверка: не больше недели вперёд
        if ($date->greaterThan(now()->addDays(7))) {
            throw ValidationException::withMessages([
                'appointment_date' => ['Дата не может быть позже чем через 7 дней.']
            ]);
        }

    } catch (\InvalidArgumentException $e) {
        throw ValidationException::withMessages([
            'appointment_date' => ['Некорректный формат даты и времени.']
        ]);
    }
}
    
        $now = \Carbon\Carbon::now();
        $maxDate = $now->copy()->addDays(7);
    
        // Определяем правила валидации
        $rules = [
            'status' => 'required|in:pending,confirmed,rejected,completed',
            'manager_comment' => 'nullable|string|max:1000',
            'car_price' => 'required|numeric|min:1000',
        ];
    
        // Добавляем appointment_date только если статус НЕ rejected и НЕ pending
        if (!in_array($request->input('status'), ['rejected', 'pending'])) {
            $rules['appointment_date'] = [
                'required',
                'date_format:Y-m-d\TH:i',
                "after_or_equal:" . $now->format('Y-m-d\TH:i'),
                "before_or_equal:" . $maxDate->format('Y-m-d\TH:i')
            ];
        } else {
            $rules['appointment_date'] = [
                'nullable',
                'date_format:Y-m-d\TH:i',
                "after_or_equal:" . $now->format('Y-m-d\TH:i'),
                "before_or_equal:" . $maxDate->format('Y-m-d\TH:i')
            ];
        }
    
        $validated = $request->validate($rules, [

            'car_price.required' => 'Цена обязательна к заполенению',
            'car_price.numeric' => 'Цена должна быть числом.',
            'car_price.min' => 'Цена не может быть меньше 1000 ₽.',

            'status.required' => 'Поле "Статус" обязательно для заполнения',
            'status.in' => 'Недопустимое значение статуса. Допустимые значения: ожидание, подтверждено, отклонено, завершено',
            
            'manager_comment.string' => 'Комментарий менеджера должен быть строкой',
            'manager_comment.max' => 'Комментарий не может превышать 1000 символов',
    
            'appointment_date.required' => 'Дата встречи обязательна, так как бронирование подтверждено или завершено',
            'appointment_date.date' => 'Некорректный формат даты встречи',
            'appointment_date.after_or_equal' => 'Дата встречи не может быть раньше сегодняшней',
            'appointment_date.before_or_equal' => 'Дата встречи не может быть позже чем через 7 дней',
        ]);

        if ($validated['status'] === 'completed' && $request->has('car_price')) {
            $booking->car->update([
                'price' => $request->input('car_price'),
            ]);
        }
    
        $oldStatus = $booking->status;
        $newStatus = $validated['status'];
        $carName = $booking->car?->equipment?->generation?->carModel?->name ?? 'автомобиль';
    
        // --- ОТПРАВКА УВЕДОМЛЕНИЯ ---
        $message = '';
        $type = '';
    
        switch ($newStatus) {
            case 'confirmed':
            case 'completed':
                $message = "Ваше бронирование автомобиля {$carName} было подтверждено.";
                $type = 'booking_confirmed';
    
             // Получаем список бронирований, которые будут отменены
                $cancelledBookings = Booking::where('car_id', $booking->car_id)
                ->where('id', '!=', $booking->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->get();

                // Обновляем статус у всех найденных бронирований
                foreach ($cancelledBookings as $bookingToCancel) {
                $bookingToCancel->update([
                    'status' => 'rejected',
                    'manager_comment' => 'Автомобиль продан другому клиенту'
                ]);

                // Отправляем уведомление пользователю
                Notification::create([
                    'user_id' => $bookingToCancel->user_id,
                    'car_id' => $bookingToCancel->car_id,
                    'type' => 'booking_rejected',
                    'message' => "Ваше бронирование было отклонено, так как автомобиль был продан другому клиенту.",
                    'url' => route('bookings.show', $bookingToCancel),
                ]);
                }
    
                $booking->car->update(['is_sold' => true]);
                break;
    
            case 'rejected':
                $message = "Ваше бронирование автомобиля {$carName} было отклонено.";
    
                if (!empty($validated['manager_comment'])) {
                    $message .= " Комментарий: {$validated['manager_comment']}";
                }
    
                $type = 'booking_rejected';
                $booking->car->update(['is_sold' => false]);
                break;
    
            case 'pending':
                $message = "Ваше бронирование автомобиля {$carName} переведено в статус ожидания.";
                if (!empty($validated['manager_comment'])) {
                    $message .= " Комментарий: {$validated['manager_comment']}";
                }
                $type = 'booking_pending';
                $booking->car->update(['is_sold' => false]);
                break;
    
            default:
                $changes = [];
    
                if ($booking->appointment_date != $validated['appointment_date']) {
                    $changes[] = "дата встречи изменена на {$validated['appointment_date']}";
                }
    
                if ($booking->status != $validated['status']) {
                    $changes[] = "статус изменён на '{$validated['status']}'";
                }
    
                if (!empty($validated['manager_comment']) && $booking->manager_comment != $validated['manager_comment']) {
                    $changes[] = "оставлен комментарий: \"{$validated['manager_comment']}\"";
                }
    
                if (!empty($changes)) {
                    $message = "Изменения по вашему бронированию автомобиля {$carName}: " . implode(', ', $changes) . '.';
                    $type = 'booking_updated';
                }
                break;
        }
    
        if (!empty($message)) {
            Notification::create([
                'user_id' => $booking->user_id,
                'car_id' => $booking->car_id,
                'type' => $type,
                'message' => $message,
                'url' => route('bookings.show', $booking),
            ]);
        }
    
        // --- ОБНОВЛЯЕМ БРОНИРОВАНИЕ ---
        $booking->update($validated);
    
        return redirect()->route('manager.bookings.index')
            ->with('success', 'Бронирование успешно обновлено');
    }

    public function destroy(Booking $booking)
    {
        try {
            $booking->delete();
            return redirect()->route('manager.bookings.index')
                ->with('success', 'Бронирование #' . $booking->id . ' успешно удалено');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ошибка при удалении бронирования: ' . $e->getMessage());
        }
    }
}