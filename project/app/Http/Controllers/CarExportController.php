<?php

namespace App\Http\Controllers;

use App\Exports\CarsForSaleExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CarExportController extends Controller
{
    public function exportNotSold()
    {
        $fileName = "Автомобили_в_продаже_" . now()->format('d.m.Y') . ".xlsx";

        return Excel::download(new CarsForSaleExport(), $fileName);
    }
}