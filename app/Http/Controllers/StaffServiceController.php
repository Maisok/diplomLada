<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class StaffServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('name')->paginate(10);

        if (request()->ajax()) {
            return response()->json([
                'content' => view('staff.services.index', compact('services'))->render()
            ]);
        }

        return view('staff.services.index', compact('services'));
    }
}