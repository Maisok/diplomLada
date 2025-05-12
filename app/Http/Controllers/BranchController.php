<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with('staff')->get();
        
        return view('admin.branches.index', [
            'branches' => $branches,
            'MAX_NAME_LENGTH' => Branch::MAX_NAME_LENGTH,
            'MAX_ADDRESS_LENGTH' => Branch::MAX_ADDRESS_LENGTH,
            'MAX_PHONE_LENGTH' => Branch::MAX_PHONE_LENGTH,
            'MAX_EMAIL_LENGTH' => Branch::MAX_EMAIL_LENGTH,
            'MAX_IMAGE_SIZE_KB' => Branch::MAX_IMAGE_SIZE_KB,
        ]);
    }

    public function edit(Branch $branch)
    {
        return response()->json([
            'name' => $branch->name,
            'address' => $branch->address,
            'phone' => $branch->phone,
            'email' => $branch->email,
            'work_time_start' => $branch->work_time_start->format('H:i'),
            'work_time_end' => $branch->work_time_end->format('H:i'),
            'image' => $branch->image ? asset($branch->image) : null
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:' . Branch::MAX_NAME_LENGTH,
            ],
          'address' => [
        'required',
        'string',
        'max:' . Branch::MAX_ADDRESS_LENGTH,
        'regex:/^г\.[а-яА-ЯёЁ\s-]+,\sул\.[а-яА-ЯёЁ\s-]+,\s\d+[а-яА-ЯёЁ\/]*$/u'
    ],
           'phone' => [
            'required',
            'string',
            'max:' . Branch::MAX_PHONE_LENGTH,
            'regex:/^8\s\d{3}\s\d{3}\s\d{2}\s\d{2}$/',
            Rule::unique('branches')
        ],
            'email' => [
                'required',
                'email',
                'max:' . Branch::MAX_EMAIL_LENGTH,
                Rule::unique('branches'),
                Rule::unique('users', 'email')
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:' . Branch::MAX_IMAGE_SIZE_KB,
            ],
            'work_time_start' => [
                'required',
                'date_format:H:i'
            ],
            'work_time_end' => [
                'required',
                'date_format:H:i',
                'after:work_time_start'
            ],
        ], [
            'name.required' => 'Название филиала обязательно для заполнения',
            'name.max' => 'Название не должно превышать ' . Branch::MAX_NAME_LENGTH . ' символов',
            'name.unique' => 'Филиал с таким названием уже существует',
            'address.required' => 'Адрес обязателен для заполнения',
             'address.regex' => 'Адрес должен быть в формате: "г. Москва, ул. Примерная, 123" или "г. Москва, ул. Примерная, 123а" или "г. Москва, ул. Примерная, 123/2"',
            'address.max' => 'Адрес не должен превышать ' . Branch::MAX_ADDRESS_LENGTH . ' символов',
            'phone.required' => 'Телефон обязателен для заполнения',
            'phone.max' => 'Телефон не должен превышать ' . Branch::MAX_PHONE_LENGTH . ' символов',
             'phone.regex' => 'Телефон должен быть в формате: 8 888 888 88 88',
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Введите корректный email адрес',
            'email.max' => 'Email не должен превышать ' . Branch::MAX_EMAIL_LENGTH . ' символов',
            'email.unique' => 'Этот email уже используется в системе',
            'image.image' => 'Файл должен быть изображением',
            'image.mimes' => 'Допустимые форматы изображений: jpeg, png, jpg, gif, svg',
            'image.max' => 'Размер изображения не должен превышать ' . (Branch::MAX_IMAGE_SIZE_KB / 1024) . 'MB',
            'image.dimensions' => 'Размер изображения не должен превышать ' . Branch::MAX_IMAGE_WIDTH . 'x' . Branch::MAX_IMAGE_HEIGHT . ' пикселей',
            'work_time_start.required' => 'Время начала работы обязательно',
            'work_time_start.date_format' => 'Неверный формат времени начала работы',
            'work_time_end.required' => 'Время окончания работы обязательно',
            'work_time_end.date_format' => 'Неверный формат времени окончания работы',
            'work_time_end.after' => 'Время окончания работы должно быть позже времени начала',
            'phone.unique' => 'Этот телефон уже занят',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('branches', 'public');
            $data['image'] = 'storage/' . $path;
        }

        Branch::create($data);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Филиал успешно создан');
    }

    public function update(Request $request, Branch $branch)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:' . Branch::MAX_NAME_LENGTH,
               
            ],
           'address' => [
            'required',
            'string',
            'max:' . Branch::MAX_ADDRESS_LENGTH,
            'regex:/^г\.[а-яА-ЯёЁ\s-]+,\sул\.[а-яА-ЯёЁ\s-]+,\s\d+[а-яА-ЯёЁ\/]*$/u'
        ],
            'phone' => [
            'required',
            'string',
            'max:' . Branch::MAX_PHONE_LENGTH,
            'regex:/^8\s\d{3}\s\d{3}\s\d{2}\s\d{2}$/',
            Rule::unique('branches')->ignore($branch->id)
        ],
            'email' => [
                'required',
                'email',
                'max:' . Branch::MAX_EMAIL_LENGTH,
                Rule::unique('branches')->ignore($branch->id),
                Rule::unique('users', 'email')
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:' . Branch::MAX_IMAGE_SIZE_KB,
                'dimensions:max_width=' . Branch::MAX_IMAGE_WIDTH . ',max_height=' . Branch::MAX_IMAGE_HEIGHT
            ],
            'work_time_start' => [
                'required',
                'date_format:H:i'
            ],
            'work_time_end' => [
                'required',
                'date_format:H:i',
                'after:work_time_start'
            ],
        ], [
            'name.required' => 'Название филиала обязательно для заполнения',
            'name.max' => 'Название не должно превышать ' . Branch::MAX_NAME_LENGTH . ' символов',
            'name.unique' => 'Филиал с таким названием уже существует',
            'address.required' => 'Адрес обязателен для заполнения',
          'address.regex' => 'Адрес должен быть в формате: "г. Москва, ул. Примерная, 123" или "г. Москва, ул. Примерная, 123а" или "г. Москва, ул. Примерная, 123/2"',
            'address.max' => 'Адрес не должен превышать ' . Branch::MAX_ADDRESS_LENGTH . ' символов',
            'phone.required' => 'Телефон обязателен для заполнения',
            'phone.unique' => 'Этот телефон уже занят',
            'phone.max' => 'Телефон не должен превышать ' . Branch::MAX_PHONE_LENGTH . ' символов',
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Введите корректный email адрес',
            'email.max' => 'Email не должен превышать ' . Branch::MAX_EMAIL_LENGTH . ' символов',
            'email.unique' => 'Этот email уже используется в системе',
            'image.image' => 'Файл должен быть изображением',
            'image.mimes' => 'Допустимые форматы изображений: jpeg, png, jpg, gif, svg',
            'image.max' => 'Размер изображения не должен превышать ' . (Branch::MAX_IMAGE_SIZE_KB / 1024) . 'MB',
            'work_time_start.required' => 'Время начала работы обязательно',
            'work_time_start.date_format' => 'Неверный формат времени начала работы',
            'work_time_end.required' => 'Время окончания работы обязательно',
            'work_time_end.date_format' => 'Неверный формат времени окончания работы',
            'work_time_end.after' => 'Время окончания работы должно быть позже времени начала',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except(['image', '_token', '_method']);

        if ($request->hasFile('image')) {
            if ($branch->image) {
                Storage::delete(str_replace('storage/', 'public/', $branch->image));
            }
            $path = $request->file('image')->store('branches', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $branch->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Филиал успешно обновлен'
        ]);
    }

    public function destroy(Branch $branch)
    {
        if ($branch->staff()->count() > 0) {
            return redirect()->route('admin.branches.index')
                ->with('error', 'Нельзя удалить филиал с сотрудниками');
        }

        if ($branch->image) {
            Storage::delete(str_replace('storage/', 'public/', $branch->image));
        }

        $branch->delete();

        return redirect()->route('admin.branches.index')
            ->with('success', 'Филиал успешно удален');
    }
}