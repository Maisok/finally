<?php

namespace App\Http\Controllers;

use App\Exports\BookingsExport;
use Illuminate\Http\Request;
use App\Exports\MultiSheetBookingsExport;
use Maatwebsite\Excel\Facades\Excel;

class BookingExportController extends Controller
{

public function exportBookings(Request $request)
{
    $status = $request->input('status');
    $managerId = $request->input('manager_id');

    $validStatuses = ['pending', 'confirmed', 'rejected', 'completed'];
    if ($status && !in_array($status, $validStatuses)) {
        return back()->withErrors(['status' => 'Неверный статус']);
    }

    $fileName = 'bookings';

    if ($status) {
        $fileName .= "_{$status}";
    }

    if ($managerId) {
        $managerName = \App\Models\User::find($managerId)?->name ?? 'manager';
        $fileName .= "_$managerName";
    }

    $fileName .= '.xlsx';

    return Excel::download(new MultiSheetBookingsExport($status, $managerId), $fileName);
}
}