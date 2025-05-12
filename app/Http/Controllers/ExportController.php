<?php

namespace App\Http\Controllers;

use App\Models\NewAppointment;
use App\Models\Service;
use App\Models\User;
use App\Models\Staff;
use App\Models\Branch;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AppointmentsExport;
use App\Exports\NewAppointmentsExport;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function exportAllAppointments()
    {
        return Excel::download(new AppointmentsExport(), 'all_appointments.xlsx');
    }

    public function exportNewAppointments()
    {
        $date = Carbon::now()->subDays(7)->toDateTimeString();
        return Excel::download(new NewAppointmentsExport($date), 'new_appointments_last_7_days.xlsx');
    }
}