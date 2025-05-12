<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffIndexController extends Controller
{
    public function show(Staff $staff)
    {
        $service = $staff->specialist->service;
        return view('staffshow', compact('staff', 'service'));
    }
}