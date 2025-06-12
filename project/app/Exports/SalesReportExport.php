<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;

class SalesReportExport implements FromArray, WithHeadings, WithEvents
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function array(): array
    {
        // Основной запрос
        $query = Booking::join('cars', 'bookings.car_id', '=', 'cars.id')
            ->join('equipment', 'cars.equipment_id', '=', 'equipment.id')
            ->join('generations', 'equipment.generation_id', '=', 'generations.id')
            ->join('car_models', 'generations.car_model_id', '=', 'car_models.id')
            ->join('brands', 'car_models.brand_id', '=', 'brands.id')
            ->where('bookings.status', 'completed');
    
        if (!empty($this->filters['brand_id'])) {
            $query->where('brands.id', $this->filters['brand_id']);
        }
    
        if (!empty($this->filters['model_id'])) {
            $query->where('car_models.id', $this->filters['model_id']);
        }
    
        if (!empty($this->filters['generation_id'])) {
            $query->where('generations.id', $this->filters['generation_id']);
        }
    
        if (!empty($this->filters['equipment_id'])) {
            $query->where('equipment.id', $this->filters['equipment_id']);
        }
    
        if (!empty($this->filters['start_date'])) {
            $query->whereDate('bookings.appointment_date', '>=', $this->filters['start_date']);
        }
    
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('bookings.appointment_date', '<=', $this->filters['end_date']);
        }
    
        // Получаем общее количество и сумму всех продаж (до группировки)
        $totalSalesRaw = clone $query;
        $totalCount = $totalSalesRaw->count();
        $totalSum = $totalSalesRaw->sum('cars.price');
    
        // Группируем данные для детализации
        $results = $query
            ->groupBy([
                'brands.id',
                'car_models.id',
                'generations.id',
                'equipment.id'
            ])
            ->select(
                'brands.name as brand',
                'car_models.name as model',
                'generations.name as generation',
                'equipment.name as equipment',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(cars.price) as total_price')
            )
            ->get();
    
        $rows = [];
    
        foreach ($results as $result) {
            $percentage = $totalCount > 0 ? round(($result->count / $totalCount) * 100, 2) : 0;
    
            $rows[] = [
                $result->brand,
                $result->model,
                $result->generation,
                $result->equipment,
                $result->count,
                number_format($result->total_price, 0, ' ', ' ') . ' ₽',
                $percentage . '%'
            ];
        }
    
        // Добавляем итоговую статистику в конец файла
        $averagePrice = $totalCount > 0 ? round($totalSum / $totalCount) : 0;
    
        $rows[] = [];
        $rows[] = ['Общая статистика'];
        $rows[] = ['Общее количество проданных авто', $totalCount];
        $rows[] = ['Общая сумма продаж', number_format($totalSum, 0, ' ', ' ') . ' ₽'];
        $rows[] = ['Средняя цена продажи', number_format($averagePrice, 0, ' ', ' ') . ' ₽'];
    
        return $rows;
    }

    public function headings(): array
    {
        return [
            'Марка',
            'Модель',
            'Поколение',
            'Комплектация',
            'Количество продаж',
            'Сумма продаж',
            'Процент от общего числа'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                for ($i = 0; $i <= 6; $i++) {
                    $event->sheet->getDelegate()->getColumnDimensionByColumn($i)->setAutoSize(true);
                }
            },
        ];
    }
}