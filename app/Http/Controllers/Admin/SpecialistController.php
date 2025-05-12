<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialist;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpecialistController extends Controller
{
    public function index()
    {
        $specialists = Specialist::all();
        return view('specialists.index', compact('specialists'));
    }

    public function create()
    {
        $services = Service::all();
        return view('specialists.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'service_id' => 'required|exists:services,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $specialist = new Specialist();
        $specialist->name = $request->name;
        $specialist->service_id = $request->service_id;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('specialists', 'public');
            $imagePath = 'storage/' . $imagePath;
            $specialist->image = $imagePath;
        }

        $specialist->save();

        return redirect()->route('admin.specialists.index')->with('success', 'Специалист успешно добавлен!');
    }

    public function edit(Specialist $specialist)
    {
        $services = Service::all();
        return view('specialists.edit', compact('specialist', 'services'));
    }

    public function update(Request $request, Specialist $specialist)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'service_id' => 'required|exists:services,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $specialist->name = $request->name;
        $specialist->service_id = $request->service_id;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('specialists', 'public');
            $imagePath = 'storage/' . $imagePath;
            $specialist->image = $imagePath;
        }

        $specialist->save();

        return redirect()->route('admin.specialists.index')->with('success', 'Специалист успешно обновлен!');
    }

    public function destroy(Specialist $specialist)
    {
        // Проверка на наличие связанных сотрудников
        if ($specialist->staff()->count() > 0) {
            return redirect()->route('admin.specialists.index')->with('error', 'Невозможно удалить специалиста, так как у него есть связанные сотрудники.');
        }
    
        $specialist->delete();
    
        return redirect()->route('admin.specialists.index')->with('success', 'Специалист успешно удален!');
    }
}