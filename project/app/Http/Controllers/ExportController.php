<?php

namespace App\Http\Controllers;

use App\Exports\BookingsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportBookings(Request $request)
    {
        $status = $request->input('status');
        $managerId = $request->input('manager_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
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
    
        if ($startDate || $endDate) {
            $fileName .= "_from_".($startDate ?: 'start')."_to_".($endDate ?: 'end');
        }
    
        $fileName .= '.xlsx';
    
        return Excel::download(new BookingsExport($status, $managerId, $startDate, $endDate), $fileName);
    }
}