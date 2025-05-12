<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        // Валидация данных
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'phone' => ['required', 'string', 'regex:/^8 \d{3} \d{3} \d{2} \d{2}$/'],
        ]);

        Appointment::create($validatedData);

        return redirect()->back()->with('success', 'Заявка успешно отправлена!');
    }
}