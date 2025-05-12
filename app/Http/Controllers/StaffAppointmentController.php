<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NewAppointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class StaffAppointmentController extends Controller
{
    public function index()
    {
        $appointments = NewAppointment::with(['user', 'service'])
            ->where('staff_id', auth('staff')->id())
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        if (request()->ajax()) {
            return response()->json([
                'content' => view('staff.appointments.index', compact('appointments'))->render()
            ]);
        }

        return view('staff.appointments.index', compact('appointments'));
    }


    public function updateStatus(NewAppointment $appointment, Request $request)
{
    $validated = $request->validate([
        'status' => 'required|in:pending,active,completed,cancelled'
    ]);
    
    $appointment->update(['status' => $validated['status']]);
    
    return response()->json(['success' => true]);
}
}