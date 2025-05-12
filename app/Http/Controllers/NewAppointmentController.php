<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\NewAppointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NewAppointmentController extends Controller
{
    public function create(Staff $staff)
    {
        $service = $staff->specialist->service;
        return view('appointments.create', compact('staff', 'service'));
    }

    public function store(Request $request, Staff $staff)
    {
        $request->validate([
            'name' => 'required|max:50',
            'phone' => 'required|max:15',
            'date' => 'required|date|after_or_equal:today', // Проверка на дату, не раньше сегодняшнего дня
        ]);

        $newAppointment = new NewAppointment();
        $newAppointment->name = $request->name;
        $newAppointment->phone = $request->phone;
        $newAppointment->date = $request->date;
        $newAppointment->specialist_id = $staff->specialist->id;
        $newAppointment->service_id = $staff->specialist->service->id;
        $newAppointment->user_id = auth()->id();
        $newAppointment->save();

        return redirect()->route('staff.show', $staff)->with('success', 'Appointment created successfully.');
    }
}