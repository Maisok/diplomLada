<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffAuthController extends Controller
{
    public function showLoginForm()
    {

        if (Auth::guard('staff')->check()) {
            return redirect()->route('staff.dashboard');
        }
        
        return view('staff.auth.login');
    } 

    public function login(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
    
        $validator = Validator::make($request->all(), [
            'login' => 'required|digits:5',
            'password' => 'required|string|min:8',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        $credentials = $request->only('login', 'password');
    
        if (Auth::guard('staff')->attempt($credentials, $request->remember)) {
            \Log::info('Staff logged in', ['staff' => Auth::guard('staff')->user()]);
            return redirect()->route('staff.dashboard');
        }
    
        return redirect()->back()
            ->withInput($request->only('login', 'remember'))
            ->withErrors(['login' => 'Неверные учетные данные']);
    }

    public function logout()
    {
        Auth::guard('staff')->logout();
        return redirect()->route('staff.login');
    }
}