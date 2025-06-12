<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class BookingsExport implements FromArray, WithHeadings, WithEvents
{
    protected $status;
    protected $managerId;
    protected $startDate;
    protected $endDate;

    public function __construct(?string $status = null, ?int $managerId = null, ?string $startDate = null, ?string $endDate = null)
    {
        $this->status = $status;
        $this->managerId = $managerId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    protected function translateStatus(string $status): string
{
    $translations = [
        'pending' => 'Ожидает подтверждения',
        'confirmed' => 'Подтверждено',
        'rejected' => 'Отклонено',
        'completed' => 'Завершено',
    ];

    return $translations[$status] ?? ucfirst($status);
}

    public function array(): array
    {
        $query = Booking::with(['user', 'car', 'manager']);

    if ($this->status) {
        $query->where('status', $this->status);
    }

    if ($this->managerId) {
        $query->where('manager_id', $this->managerId);
    }

    // Фильтр по дате встречи
    if ($this->startDate && $this->endDate) {
        $query->whereBetween('booking_date', [$this->startDate, $this->endDate]);
    } elseif ($this->startDate) {
        $query->where('booking_date', '>=', $this->startDate);
    } elseif ($this->endDate) {
        $query->where('booking_date', '<=', $this->endDate);
    }

    $bookings = $query->get();
    
        $rows = [];
    
        foreach ($bookings as $booking) {
            $rows[] = [
                $booking->id,
                $booking->user?->name ?? '-',
                $booking->user?->email ?? '-',
                $booking->user?->phone ?? '-',
                $booking->car?->full_name ?? '-',
                $booking->car?->vin ?? '-',
                $booking->booking_date,
                $booking->appointment_date,
                $this->translateStatus($booking->status), // <-- Теперь на русском
                $booking->manager_comment ?? '-',
                $booking->manager?->name ?? '-',
                $booking->manager?->email ?? '-',
                $booking->car?->price ?? 0,
            ];
        }
            
        // --- Пустая строка перед статистикой ---
        $rows[] = [];
    
        // --- Статистика по менеджерам ---
        $totalCountAll = Booking::where('status', 'completed')->count();
    
        $totalCompletedAll = Booking::join('cars', 'bookings.car_id', '=', 'cars.id')
            ->where('bookings.status', 'completed')
            ->sum('cars.price');
    
        $managersQuery = \App\Models\User::where('role', 'manager');
    
        if ($this->managerId) {
            $managersQuery->where('id', $this->managerId);
        }
    
        $managers = $managersQuery->get();
    
        // Заголовок статистики
        $rows[] = ['Статистика по менеджерам'];
        $rows[] = [
            'Имя менеджера',
            'Всего сделок',
            'Завершено',
            'Общая сумма сделок',
            'Средняя цена сделки',
            '% успеха',
            '% от общего числа'
        ];
    
        foreach ($managers as $manager) {
            $totalBookings = Booking::where('manager_id', $manager->id)->count();
            $completedBookings = Booking::where('manager_id', $manager->id)
                ->where('status', 'completed')
                ->count();
    
            $totalPrice = Booking::join('cars', 'bookings.car_id', '=', 'cars.id')
                ->where('bookings.manager_id', $manager->id)
                ->where('bookings.status', 'completed')
                ->sum('cars.price');
    
            $avgPrice = $completedBookings > 0 ? round(($totalPrice / $completedBookings), 2) : 0;
    
            $efficiency = $totalBookings > 0 ? round(($completedBookings / $totalBookings) * 100, 2) : 0;
            $share = $totalCountAll > 0 ? round(($completedBookings / $totalCountAll) * 100, 2) : 0;
    
            $rows[] = [
                $manager->name,
                $totalBookings,
                $completedBookings,
                number_format($totalPrice, 2, '.', ' '),
                number_format($avgPrice, 2, '.', ' '),
                $efficiency . '%',
                $share . '%'
            ];
        }
    
        // --- Общая сумма всех успешных сделок ---
        $rows[] = [];
        $rows[] = ['Общая сумма всех успешных сделок'];
        $rows[] = [
            'Сумма',
            number_format($totalCompletedAll, 2, '.', ' ')
        ];
    
        return $rows;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Пользователь',
            'Email пользователя',
            'Телефон пользователя',
            'Машина',
            'VIN',
            'Дата бронирования',
            'Дата встречи',
            'Статус',
            'Комментарий менеджера',
            'Менеджер',
            'Email менеджера',
            'Цена машины'
        ];
    }

    /**
     * Регистрация событий
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate(); // Получаем родительский объект

                // Подбираем ширину для первых 15 столбцов (от A до O)
                for ($i = 0; $i <= 15; $i++) {
                    $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
                }
            },
        ];
    }
}