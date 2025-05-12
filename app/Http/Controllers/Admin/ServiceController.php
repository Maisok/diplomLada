<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('staff')->get();
        $allStaff = Staff::all();
        return view('services.index', compact('services', 'allStaff'));
    }

    public function edit(Service $service)
    {
        return response()->json([
            'name' => $service->name,
            'price' => $service->price,
            'image' => $service->image ? asset($service->image) : null,
            'staff' => $service->staff->pluck('id')->toArray()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Zа-яА-Я\s\-]+$/u',
                Rule::unique('services')
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048',
                'dimensions:max_width=2000,max_height=2000'
            ],
            'staff' => 'nullable|array',
            'staff.*' => 'exists:staff,id',
        ], [
            'name.required' => 'Название услуги обязательно для заполнения',
            'name.max' => 'Название не должно превышать 50 символов',
            'name.regex' => 'Название может содержать только буквы, пробелы и дефисы',
            'name.unique' => 'Услуга с таким названием уже существует',
            'price.required' => 'Цена обязательна для заполнения',
            'price.numeric' => 'Цена должна быть числом',
            'price.min' => 'Цена не может быть отрицательной',
            'price.max' => 'Цена не может превышать 99,999,999.99',
            'price.regex' => 'Цена должна быть в формате 123.45',
            'image.image' => 'Файл должен быть изображением',
            'image.mimes' => 'Допустимые форматы изображений: jpeg, png, jpg, gif, svg',
            'image.max' => 'Размер изображения не должен превышать 2MB',
            'image.dimensions' => 'Размер изображения не должен превышать 2000x2000 пикселей',
            'staff.*.exists' => 'Выбран несуществующий сотрудник',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $service = new Service();
        $service->name = $request->name;
        $service->price = $request->price;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
            $service->image = 'storage/' . $imagePath;
        }

        $service->save();

        if ($request->has('staff')) {
            $service->staff()->sync($request->staff);
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Услуга успешно добавлена!');
    }

    public function update(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Zа-яА-Я\s\-]+$/u',
                Rule::unique('services')->ignore($service->id)
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048',
                'dimensions:max_width=2000,max_height=2000'
            ],
            'staff' => 'nullable|array',
            'staff.*' => 'exists:staff,id',
        ], [
            'name.required' => 'Название услуги обязательно для заполнения',
            'name.max' => 'Название не должно превышать 50 символов',
            'name.regex' => 'Название может содержать только буквы, пробелы и дефисы',
            'name.unique' => 'Услуга с таким названием уже существует',
            'price.required' => 'Цена обязательна для заполнения',
            'price.numeric' => 'Цена должна быть числом',
            'price.min' => 'Цена не может быть отрицательной',
            'price.max' => 'Цена не может превышать 99,999,999.99',
            'price.regex' => 'Цена должна быть в формате 123.45',
            'image.image' => 'Файл должен быть изображением',
            'image.mimes' => 'Допустимые форматы изображений: jpeg, png, jpg, gif, svg',
            'image.max' => 'Размер изображения не должен превышать 2MB',
            'image.dimensions' => 'Размер изображения не должен превышать 2000x2000 пикселей',
            'staff.*.exists' => 'Выбран несуществующий сотрудник',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $service->name = $request->name;
        $service->price = $request->price;

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::delete(str_replace('storage/', 'public/', $service->image));
            }
            $imagePath = $request->file('image')->store('services', 'public');
            $service->image = 'storage/' . $imagePath;
        }

        $service->save();
        $service->staff()->sync($request->staff ?? []);

        return redirect()->route('admin.services.index')
            ->with('success', 'Услуга успешно обновлена!');
    }

    public function destroy(Service $service)
    {
        if ($service->staff()->count() > 0) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Невозможно удалить услугу, так как к ней прикреплены сотрудники.');
        }

        if ($service->image) {
            Storage::delete(str_replace('storage/', 'public/', $service->image));
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Услуга успешно удалена!');
    }
}