<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialist;

class SpecialistIndexController extends Controller
{
    public function index()
    {
        $specialists = Specialist::all();
        return view('specialists', compact('specialists'));
    }

    public function show(Specialist $specialist)
    {
        $staff = $specialist->staff;
        return view('staff', compact('specialist', 'staff'));
    }
}
