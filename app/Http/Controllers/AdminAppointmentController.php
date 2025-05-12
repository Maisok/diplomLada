<?php

namespace App\Http\Controllers;

use App\Models\NewAppointment;
use Illuminate\Http\Request;

class AdminAppointmentController extends Controller
{
    public function index()
    {
        $appointments = NewAppointment::with(['user', 'service', 'staff', 'branch'])
            ->orderBy('start_time', 'desc')
            ->paginate(10);
            
        return view('admin.appointments.index', compact('appointments'));
    }

    public function updateStatus(Request $request, NewAppointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,active,completed,cancelled'
        ]);

        $appointment->update(['status' => $request->status]);

        return back()->with('success', 'Статус записи успешно обновлен');
    }
}