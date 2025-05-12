<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StaffClientController extends Controller
{
    public function index()
    {
        $clients = User::where('role', 'user')
            ->orderBy('name')
            ->paginate(10);

        if (request()->ajax()) {
            return response()->json([
                'content' => view('staff.clients.index', compact('clients'))->render()
            ]);
        }

        return view('staff.clients.index', compact('clients'));
    }
}