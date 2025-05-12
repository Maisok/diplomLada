<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\NewAppointment;
use App\Models\Branch;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ServiceIndexController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        // Проверка на наличие параметров запроса
        $hasSearchParams = $request->filled('search') || $request->filled('price_from') || $request->filled('price_to');

        // Поиск по названию
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Сортировка по цене
        if ($request->filled('price_from') && $request->filled('price_to')) {
            $query->whereBetween('price', [$request->price_from, $request->price_to]);
        } elseif ($request->filled('price_from')) {
            $query->where('price', '>=', $request->price_from);
        } elseif ($request->filled('price_to')) {
            $query->where('price', '<=', $request->price_to);
        }

        // Если параметры запроса не заданы, выводим все услуги
        $services = $hasSearchParams ? $query->get() : Service::all();

        return view('service', compact('services'));
    }

    public function show(Service $service)
    {
        // Загружаем вопросы для данного сервиса с отношениями
        $questions = $service->questions()
            ->with(['answers', 'user', 'answers.user'])
            ->latest()
            ->get();

            if (auth()->check()) {
                auth()->user()->unreadQuestionNotifications()
                    ->whereHas('question', fn($q) => $q->where('service_id', $service->id))
                    ->update(['is_read' => true]);
            }
    
        // Получаем филиалы, где есть сотрудники, связанные с этой услугой
        $branches = Branch::whereHas('staff.services', function($query) use ($service) {
            $query->where('services.id', $service->id);
        })->get();
    
        return view('showservices', compact('service', 'branches', 'questions'));
    }
    

    public function getStaff(Request $request, Service $service)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id'
        ]);

        // Получаем сотрудников для выбранного филиала и услуги
        $staff = Staff::where('branch_id', $request->branch_id)
            ->whereHas('services', function($query) use ($service) {
                $query->where('services.id', $service->id);
            })
            ->get();

        return response()->json($staff);
    }

    public function getAvailableTimes(Request $request, Service $service)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date|after_or_equal:today',
            'branch_id' => 'required|exists:branches,id'
        ]);
    
        $staff = Staff::findOrFail($request->staff_id);
        $branch = Branch::findOrFail($request->branch_id);
    
        // Получаем рабочие часы филиала
        $workStart = $branch->work_time_start->format('H:i');
        $workEnd = $branch->work_time_end->format('H:i');
    
        // Получаем существующие записи на выбранную дату
        $existingAppointments = NewAppointment::where('staff_id', $request->staff_id)
            ->whereDate('start_time', $request->date)
            ->whereIn('status', ['pending', 'active'])
            ->get();
    
        // Генерируем доступные временные слоты
        $availableTimes = [];
        $start = strtotime("$request->date $workStart");
        $end = strtotime("$request->date $workEnd") - 3600; // Минус 1 час (длительность записи)
    
        for ($time = $start; $time <= $end; $time += 1800) { // Шаг 30 минут
            $timeFormatted = date('H:i', $time);
            $isAvailable = true;
    
            // Проверяем каждую существующую запись
            foreach ($existingAppointments as $appointment) {
                $appointmentStart = strtotime($appointment->start_time);
                $appointmentEnd = strtotime($appointment->end_time);
                
                // Блокируем время за 1 час до и после существующей записи
                if (($time >= $appointmentStart - 3600 && $time < $appointmentEnd) || 
                    ($time >= $appointmentStart && $time < $appointmentEnd + 3600)) {
                    $isAvailable = false;
                    break;
                }
            }
    
            if ($isAvailable) {
                $availableTimes[] = $timeFormatted;
            }
        }
    
        return response()->json([
            'available_times' => $availableTimes,
            'work_hours' => "$workStart - $workEnd"
        ]);
    }

    public function storeAppointment(Request $request, Service $service, Staff $staff, Branch $branch)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'staff_id' => [
                'required',
                Rule::exists('staff', 'id')->where('branch_id', $request->branch_id)
            ],
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
        ]);

        $startTime = $validated['date'] . ' ' . $validated['time'];
        $endTime = date('Y-m-d H:i', strtotime($startTime . ' +1 hour'));

        // Проверка доступности времени для специалиста
        $this->validateStaffAvailability($validated['staff_id'], $startTime, $endTime);

        // Проверка что у пользователя нет другой записи на этот день
        $this->checkUserAppointments($validated['date']);

        // Проверка рабочего времени филиала
        $this->checkBranchWorkingHours($validated['branch_id'], $startTime, $endTime);

        // Создание записи (используем NewAppointment)
        $appointment = NewAppointment::create([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
            'branch_id' => $validated['branch_id'],
            'staff_id' => $validated['staff_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending',
        ]);

        $successMessage = "Запись на услугу '{$service->name}' успешно создана!\n" .
                     "Дата: " . date('d.m.Y', strtotime($startTime)) . "\n" .
                     "Время: " . date('H:i', strtotime($startTime)) . "\n" .
                     "Специалист: {$staff->first_name} {$staff->last_name}\n" .
                     "Филиал: {$branch->name}\n" .
                     "Статус: Ожидает подтверждения";

    return redirect()->route('services.show', $service)
        ->with('success', $successMessage);
    }

    protected function validateStaffAvailability($staffId, $startTime, $endTime)
    {
        $conflictingAppointment = NewAppointment::where('staff_id', $staffId)
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function($query) use ($startTime, $endTime) {
                        $query->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    })
                    // Блокируем час до и после существующей записи
                    ->orWhere(function($query) use ($startTime) {
                        $query->where('start_time', '<=', date('Y-m-d H:i:s', strtotime($startTime) + 3600))
                            ->where('end_time', '>=', date('Y-m-d H:i:s', strtotime($startTime) - 3600));
                    });
            })
            ->whereIn('status', ['pending', 'active'])
            ->exists();
            
        if ($conflictingAppointment) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], [])->errors()->add(
                    'time', 
                    'Выбранное время недоступно. Пожалуйста, выберите другое время с учетом часового интервала между записями.'
                )
            );
        }
    }

    protected function checkUserAppointments($date)
    {
        $existingAppointment = NewAppointment::where('user_id', Auth::id())
            ->whereDate('start_time', $date)
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($existingAppointment) {
            abort(422, 'У вас уже есть запись на этот день.');
        }
    }

    protected function checkBranchWorkingHours($branchId, $startTime, $endTime)
    {
        $branch = Branch::findOrFail($branchId);
        $startHour = date('H:i', strtotime($startTime));
        $endHour = date('H:i', strtotime($endTime));
        $dayOfWeek = date('N', strtotime($startTime)); // 1-понедельник, 7-воскресенье
    
        // Проверяем что время в пределах рабочего дня филиала
        if ($startHour < $branch->work_time_start->format('H:i') || 
            $endHour > $branch->work_time_end->format('H:i')) {
            abort(422, 'Выбранное время вне рабочих часов филиала ('.$branch->work_time_start->format('H:i').' - '.$branch->work_time_end->format('H:i').')');
        }
    }
}