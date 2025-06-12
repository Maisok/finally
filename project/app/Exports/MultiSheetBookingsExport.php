<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetBookingsExport implements WithMultipleSheets
{
    protected $status;
    protected $managerId;

    public function __construct(?string $status = null, ?int $managerId = null)
    {
        $this->status = $status;
        $this->managerId = $managerId;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Лист с бронированиями
        $sheets[] = new BookingsExport($this->status, $this->managerId);

        // Лист со статистикой
        $sheets[] = new ManagerStatsExport($this->managerId);

        return $sheets;
    }
}