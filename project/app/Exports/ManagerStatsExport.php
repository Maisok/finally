<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Booking;
use App\Models\User;

class ManagerStatsExport implements FromCollection, WithHeadings
{
    protected $managerId;

    public function __construct(?int $managerId = null)
    {
        $this->managerId = $managerId;
    }

    public function collection()
    {
        $totalCompletedAll = Booking::where('status', 'completed')->count();

        $managersQuery = User::where('role', 'manager');

        if ($this->managerId) {
            $managersQuery->where('id', $this->managerId);
        }

        $managers = $managersQuery->get();

        $stats = [];

        foreach ($managers as $manager) {
            $total = Booking::where('manager_id', $manager->id)->count();
            $completed = Booking::where('manager_id', $manager->id)
                ->where('status', 'completed')
                ->count();

            $efficiency = $total > 0 ? round(($completed / $total) * 100, 2) : 0;
            $share = $totalCompletedAll > 0 ? round(($completed / $totalCompletedAll) * 100, 2) : 0;

            $stats[] = [
                $manager->name,
                $total,
                $completed,
                $efficiency . '%',
                $share . '%'
            ];
        }

        return collect($stats);
    }

    public function headings(): array
    {
        return [
            'Имя менеджера',
            'Всего сделок',
            'Завершено',
            '% успеха',
            '% от общего числа'
        ];
    }
}