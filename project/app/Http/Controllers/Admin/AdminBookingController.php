<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        // Получаем список всех менеджеров для фильтрации
        $managers = User::where('role', 'manager')->get();

        // Начинаем запрос
        $query = Booking::with(['user', 'car.equipment.generation.carModel.brand', 'manager']);

        // Фильтры
        $query->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->date_from, fn($q) => $q->where('booking_date', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->where('booking_date', '<=', $request->date_to))
            ->when($request->manager_id, fn($q) => $q->where('manager_id', $request->manager_id));

        // Получаем результаты
        $bookings = $query->latest()->paginate(15)->appends($request->query());

        return view('admin.bookings.index', compact('bookings', 'managers'));
    }

    public function editManager(Booking $booking)
    {
        $managers = User::where('role', 'manager')->get();
        return view('admin.bookings.edit-manager', compact('booking', 'managers'));
    }

    public function updateManager(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'manager_id' => ['nullable', 'exists:users,id,role,manager'],
        ]);

        $booking->update(['manager_id' => $validated['manager_id']]);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Менеджер успешно обновлён');
    }
}