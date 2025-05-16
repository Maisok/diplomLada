<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SpecialistController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\ServiceIndexController;
use App\Http\Controllers\SpecialistIndexController;
use App\Http\Controllers\StaffIndexController;
use App\Http\Controllers\NewAppointmentController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\AdminAppointmentController;
use App\Http\Controllers\StaffServiceController;
use App\Http\Controllers\StaffClientController;
use App\Http\Controllers\StaffAppointmentController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\ServiceAnswerController;
use App\Http\Controllers\ServiceQuestionController;
use Illuminate\Http\Request;
use App\Models\Service;
    

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::prefix('/services')->group(function() {
        Route::get('/', [ServiceController::class, 'index'])->name('admin.services.index');
        Route::post('/', [ServiceController::class, 'store'])->name('admin.services.store');
        Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('admin.services.edit');
        Route::put('/{service}', [ServiceController::class, 'update'])->name('admin.services.update');
        Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');
    });

    Route::get('/export/appointments', [\App\Http\Controllers\ExportController::class, 'exportAllAppointments'])
    ->name('export.appointments');
    
    Route::get('/export/new-appointments', [\App\Http\Controllers\ExportController::class, 'exportNewAppointments'])
        ->name('export.new-appointments');
    
    Route::get('/specialists', [SpecialistController::class, 'index'])->name('admin.specialists.index');
    Route::get('/specialists/create', [SpecialistController::class, 'create'])->name('admin.specialists.create');
    Route::post('/specialists', [SpecialistController::class, 'store'])->name('admin.specialists.store');
    Route::get('/specialists/{specialist}/edit', [SpecialistController::class, 'edit'])->name('admin.specialists.edit');
    Route::put('/specialists/{specialist}', [SpecialistController::class, 'update'])->name('admin.specialists.update');
    Route::delete('/specialists/{specialist}', [SpecialistController::class, 'destroy'])->name('admin.specialists.destroy');

    Route::get('/certificates', [App\Http\Controllers\Admin\GiftCertificateController::class, 'index'])
    ->name('admin.certificates.index');

Route::put('/certificates/{certificate}', [App\Http\Controllers\Admin\GiftCertificateController::class, 'update'])
    ->name('admin.certificates.update');

// Остальные роуты админки...
Route::get('/services', [App\Http\Controllers\Admin\ServiceController::class, 'index'])
    ->name('admin.services.index');

Route::get('/staff', [App\Http\Controllers\Admin\StaffController::class, 'index'])
    ->name('admin.staff.index');

    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('admin.appointments.index');
    Route::patch('/appointments/{appointment}/update-status', [AdminAppointmentController::class, 'updateStatus'])
        ->name('admin.appointments.update-status');
    // Филиалы
    Route::get('/branches', [BranchController::class, 'index'])->name('admin.branches.index');
    Route::post('/branches', [BranchController::class, 'store'])->name('admin.branches.store');
    Route::get('/branches/{branch}/edit', [BranchController::class, 'edit'])->name('admin.branches.edit');
    Route::put('/branches/{branch}', [BranchController::class, 'update'])->name('admin.branches.update');
    Route::delete('/branches/{branch}', [BranchController::class, 'destroy'])->name('admin.branches.destroy');
    
    // Сотрудники
    Route::get('/staff', [StaffController::class, 'index'])->name('admin.staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('admin.staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('admin.staff.store');
    Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('admin.staff.edit');
    Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('admin.staff.update');
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('admin.staff.destroy');

});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {
    

});


Route::middleware(['staff.auth'])->group(function () {
});



Route::middleware(['auth', 'verified'])->group(function () {
  
});

// Только для гостей
Route::middleware(['guest'])->group(function () {
   
});

Route::get('/', function () {
    $randomServices = Service::inRandomOrder()->limit(3)->get();
    return view('welcome', ['randomServices' => $randomServices]);
})->name('welcome');

Route::get('/showservices', [ServiceIndexController::class, 'index'])->name('services');
Route::get('/showservices/{service}', [ServiceIndexController::class, 'show'])->name('services.show');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('/showspecialists', [SpecialistIndexController::class, 'index'])->name('specialists');
Route::get('/showspecialists/{specialist}', [SpecialistIndexController::class, 'show'])->name('specialists.show');
Route::get('/staff/{staff}', [StaffIndexController::class, 'show'])->name('staff.show');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile/verify-email/{token}', [ProfileController::class, 'verifyEmailChange'])
    ->name('profile.verify-email');

    Route::post('/profile/verify-email/resend', [ProfileController::class, 'resendVerificationEmail'])
    ->name('profile.verify-email.resend');



    Route::get('/services', [ServiceIndexController::class, 'index'])->name('services.index');
    Route::get('/services/{service}', [ServiceIndexController::class, 'show'])->name('services.show');
    Route::get('/services/{service}/staff', [ServiceIndexController::class, 'getStaff'])->name('services.staff');
    Route::get('/services/{service}/times', [ServiceIndexController::class, 'getAvailableTimes'])->name('services.times');
    Route::post('/services/{service}/appointments', [ServiceIndexController::class, 'storeAppointment'])
        ->name('services.store-appointment')
        ->middleware('auth');

        Route::patch('/appointments/{appointment}/cancel', [DashboardController::class, 'cancel'])
    ->name('appointments.cancel')
    ->middleware('auth');

    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');

  


    Route::get('/staff/{staff}/appointment', [NewAppointmentController::class, 'create'])->name('appointment.create');
    Route::post('/staff/{staff}/appointment', [NewAppointmentController::class, 'store'])->name('appointment.store');
  
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');


});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

});


Route::get('/auth/yandex', [RegisterController::class, 'yandex'])->name('auth.yandex');
Route::get('/auth/yandex/redirect', [RegisterController::class, 'yandexRedirect']);

Route::get('/email/verify', function () {
    return view('auth.verify');
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    \Log::info('Verification attempt', [
        'user' => $request->user(),
        'id' => $request->id,
        'hash' => $request->hash,
        'expected' => sha1($request->user()->email),
        'is_signed' => $request->hasValidSignature()
    ]);

    if (!$request->hasValidSignature()) {
        \Log::error('Invalid signature', ['url' => $request->fullUrl()]);
        abort(403, 'Недействительная подпись ссылки');
    }

    if ($request->user()->hasVerifiedEmail()) {
        return redirect('/')->with('info', 'Email уже подтверждён');
    }

    $request->fulfill();

    return redirect('/')->with('success', 'Email успешно подтверждён!');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Новая ссылка отправлена!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');



// Маршруты сброса пароля
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');


    Route::middleware('auth')->group(function () {
        Route::post('/gift-certificates', [DashboardController::class, 'storeCertificate'])->name('gift-certificates.store');
        Route::get('/gift-certificates/{certificate}/download', [DashboardController::class, 'downloadCertificate'])->name('gift-certificates.download');
        Route::get('/gift-certificates/verify/{code}', [DashboardController::class, 'verify'])
    ->name('gift-certificates.verify');
    Route::get('/gift-certificates/verify/{code}', [DashboardController::class, 'verify'])
    ->name('gift-certificates.verify');
    });

Route::prefix('staffauth')->name('staff.')->group(function () {
    // Аутентификация
    Route::get('/login', [StaffAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StaffAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [StaffAuthController::class, 'logout'])->name('logout');
    
    // Защищенные маршруты
    Route::middleware('staff')->group(function () {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
        Route::get('/appointments', [StaffAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/clients', [StaffClientController::class, 'index'])->name('clients.index');
        Route::get('/services', [StaffServiceController::class, 'index'])->name('services.index');
    });
});

Route::put('/staffauth/appointments/{appointment}/status', [StaffAppointmentController::class, 'updateStatus'])
    ->name('staff.appointments.updateStatus');


Route::group(['prefix' => 'questions'], function () {
    Route::post('/{service}', [ServiceQuestionController::class, 'store'])->name('services.questions.store');
    Route::put('/{question}', [ServiceQuestionController::class, 'update'])->name('services.questions.update');
    Route::delete('/{question}', [ServiceQuestionController::class, 'destroy'])
    ->name('services.questions.destroy')
    ->middleware('can:delete,question');
    Route::post('/{question}/close', [ServiceQuestionController::class, 'close'])
    ->name('services.questions.close')
    ->middleware('can:close,question');
});

Route::group(['prefix' => 'answers'], function () {
    Route::post('/{question}', [ServiceAnswerController::class, 'store'])->name('services.answers.store');
    Route::put('/{answer}', [ServiceAnswerController::class, 'update'])->name('services.answers.update');
    Route::delete('/{answer}', [ServiceAnswerController::class, 'destroy'])->name('services.answers.destroy');
});




