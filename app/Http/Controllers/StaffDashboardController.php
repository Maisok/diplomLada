<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NewAppointment;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $staff = Auth::guard('staff')->user();
    
        if (!$staff) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('staff.login');
        }
        
        // Статистика на сегодня
        $todayAppointmentsCount = NewAppointment::where('staff_id', $staff->id)
            ->whereDate('start_time', today())
            ->count();
            
        $lastAppointmentToday = NewAppointment::where('staff_id', $staff->id)
            ->whereDate('start_time', today())
            ->orderBy('start_time', 'desc')
            ->first();
        
        // Статистика за месяц
        $monthAppointmentsCount = NewAppointment::where('staff_id', $staff->id)
            ->whereBetween('start_time', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
            
        $monthRevenue = NewAppointment::where('staff_id', $staff->id)
            ->whereBetween('start_time', [now()->startOfMonth(), now()->endOfMonth()])
            ->where('status', 'confirmed')
            ->with('service')
            ->get()
            ->sum(function($appointment) {
                return $appointment->service->price ?? 0;
            });
        
        // Ближайшие записи (3 ближайшие)
        $upcomingAppointments = NewAppointment::with(['service', 'user'])
            ->where('staff_id', $staff->id)
            ->where('start_time', '>=', now())
            ->whereIn('status', ['confirmed', 'pending'])
            ->orderBy('start_time')
            ->take(3)
            ->get();

            $totalRevenue = NewAppointment::where('staff_id', $staff->id)
            ->where('status', 'confirmed')
            ->with('service')
            ->get()
            ->sum(function($appointment) {
                return $appointment->service->price ?? 0;
            });
            
      
            if (request()->ajax()) {
                try {
                    return response()->json([
                        'content' => view('staff.dashboard.index', compact(
                            'todayAppointmentsCount',
                            'lastAppointmentToday',
                            'monthAppointmentsCount',
                            'monthRevenue',
                            'totalRevenue',
                            'upcomingAppointments'
                        ))->render()
                    ]);
                } catch (\Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            }
        
            return view('staff.dashboard', compact(
                'todayAppointmentsCount',
                'lastAppointmentToday',
                'monthAppointmentsCount',
                'monthRevenue',
                'totalRevenue',
                'upcomingAppointments'
            ));
    }
}