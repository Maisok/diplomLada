<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        $staff = Staff::with('branch')->get();
        return view('admin.staff.index', compact('staff', 'branches'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50|regex:/^[a-zA-Zа-яА-Я\s]+$/u',
            'last_name' => 'required|string|max:50|regex:/^[a-zA-Zа-яА-Я\s]+$/u',
            'position' => 'required|string|max:50',
            'branch_id' => 'nullable|exists:branches,id',
            'login' => 'required|digits:5|unique:staff,login',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'first_name.regex' => 'Имя должно содержать только буквы и пробелы',
            'last_name.regex' => 'Фамилия должна содержать только буквы и пробелы',
            'login.digits' => 'Логин должен состоять из 5 цифр',
            'password.min' => 'Пароль должен содержать минимум 8 символов',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except('image', 'password');
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('staff', 'public');
            $data['image'] = 'storage/' . $path;
        }

        Staff::create($data);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Сотрудник успешно добавлен');
    }

    public function edit(Staff $staff)
    {
        $branches = Branch::all();
        return response()->json([
            'first_name' => $staff->first_name,
            'last_name' => $staff->last_name,
            'position' => $staff->position,
            'branch_id' => $staff->branch_id,
            'login' => $staff->login,
            'image' => $staff->image ? asset($staff->image) : null,
        ]);
    }

    public function update(Request $request, Staff $staff)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50|regex:/^[a-zA-Zа-яА-Я\s]+$/u',
            'last_name' => 'required|string|max:50|regex:/^[a-zA-Zа-яА-Я\s]+$/u',
            'position' => 'required|string|max:50',
            'branch_id' => 'nullable|exists:branches,id',
            'login' => 'required|digits:5|unique:staff,login,'.$staff->id,
            'password' => 'nullable|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'first_name.regex' => 'Имя должно содержать только буквы и пробелы',
            'last_name.regex' => 'Фамилия должна содержать только буквы и пробелы',
            'login.digits' => 'Логин должен состоять из 5 цифр',
            'password.min' => 'Пароль должен содержать минимум 8 символов',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except('image', 'password');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            if ($staff->image) {
                Storage::delete(str_replace('storage/', 'public/', $staff->image));
            }
            $path = $request->file('image')->store('staff', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Сотрудник успешно обновлен');
    }

    public function destroy(Staff $staff)
    {
        if ($staff->image) {
            Storage::delete(str_replace('storage/', 'public/', $staff->image));
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Сотрудник успешно удален');
    }
}