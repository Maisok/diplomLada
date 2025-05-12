<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewAppointment;
use App\Models\GiftCertificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\PDF;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $appointments = NewAppointment::with(['service', 'staff', 'branch'])
            ->where('user_id', $user->id)
            ->orderBy('start_time', 'desc')
            ->get();
        
        $certificates = $user->giftCertificates()->latest()->get();
        
        // Передаем константы сертификата в представление
        $certificateData = [
            'min_amount' => 1000, // или GiftCertificate::MIN_AMOUNT если модель доступна
            'max_amount' => 1000000,
            'recipient_name_length' => 100,
            'message_max_length' => 500
        ];
            
        return view('dashboard', compact('user', 'appointments', 'certificates', 'certificateData'));
    }

    public function cancel(Request $request, NewAppointment $appointment)
    {
        // Проверяем что запись принадлежит текущему пользователю
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Недостаточно прав для отмены этой записи');
        }

        // Проверяем что запись еще можно отменить (только pending)
        if ($appointment->status !== 'pending') {
            return back()->with('error', 'Можно отменять только записи со статусом "Ожидает подтверждения"');
        }

        // Обновляем статус записи
        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Запись успешно отменена');
    }

    public function storeCertificate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => [
                'required',
                'numeric',
                'min:' . GiftCertificate::MIN_AMOUNT,
                'max:' . GiftCertificate::MAX_AMOUNT
            ],
            'recipient_name' => [
                'nullable',
                'string',
                'max:' . GiftCertificate::RECIPIENT_NAME_LENGTH
            ],
            'message' => [
                'nullable',
                'string',
                'max:' . GiftCertificate::MESSAGE_MAX_LENGTH
            ]
        ], [
            'amount.required' => 'Укажите сумму сертификата',
            'amount.numeric' => 'Сумма должна быть числом',
            'amount.min' => 'Минимальная сумма сертификата: ' . GiftCertificate::MIN_AMOUNT . ' руб.',
            'amount.max' => 'Максимальная сумма сертификата: ' . GiftCertificate::MAX_AMOUNT . ' руб.',
            'recipient_name.max' => 'Имя получателя не должно превышать ' . GiftCertificate::RECIPIENT_NAME_LENGTH . ' символов',
            'message.max' => 'Сообщение не должно превышать ' . GiftCertificate::MESSAGE_MAX_LENGTH . ' символов'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $certificate = GiftCertificate::create([
            'user_id' => auth()->id(),
            'code' => $this->generateUniqueCode(),
            'amount' => $request->amount,
            'recipient_name' => $request->recipient_name,
            'message' => $request->message,
            'expires_at' => now()->addYears(GiftCertificate::DEFAULT_EXPIRATION_YEARS),
        ]);

        return back()->with([
            'certificate_success' => 'Сертификат успешно создан! Вы можете скачать его ниже.',
            'certificate_id' => $certificate->id
        ]);
    }

    protected function generateUniqueCode()
    {
        do {
            $code = Str::upper(Str::random(10));
        } while (GiftCertificate::where('code', $code)->exists());

        return $code;
    }

    public function downloadCertificate(GiftCertificate $certificate)
    {
        if ($certificate->user_id !== auth()->id()) {
            abort(403);
        }
    
        // Создаем экземпляр PDF
        $pdf = app('dompdf.wrapper');
        
        // Настройки
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'dejavu sans',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'dpi' => 96
        ]);
    
        // Генерация QR-кода через Google API
      
    
        // Загрузка шаблона
        $pdf->loadView('pdf.gift-certificate', [
            'certificate' => $certificate,
        ]);
    
        return $pdf->download("certificate-{$certificate->code}.pdf");
    }
    
    public function verify($code)
    {
        $certificate = GiftCertificate::where('code', $code)->firstOrFail();
        
        return view('gift-certificates.verify', [
            'certificate' => $certificate,
            'isValid' => !$certificate->is_used && $certificate->expires_at > now()
        ]);
    }
}