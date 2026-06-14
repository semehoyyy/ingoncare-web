<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\PengingatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\RiwayatKesehatanController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChatbotController;

// ==========================================
// WELCOME PAGE (Landing Page)
// ==========================================
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }

    return view('welcome');
})->name('welcome');

// ==========================================
// AUTHENTICATION ROUTES
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'prosesLogin'])->name('proseslogin');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'prosesRegister'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])
    ->name('verify.otp');

Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])
    ->name('verify.otp.post');

Route::get('/resend-otp', [AuthController::class, 'resendOtp'])
    ->name('resend.otp');

// ==========================================
// HOME/DASHBOARD
// ==========================================
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // ==========================================
    // SEARCH
    // ==========================================
    Route::get('/search', [HomeController::class, 'search'])->name('search');

    // ==========================================
    // COMMENTS
    // ==========================================
    Route::post('/comments', [CommentController::class, 'store'])
        ->name('comments.store');

    Route::post('/comments/{id}/like', [CommentController::class, 'like'])
        ->name('comments.like');

    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    // ==========================================
    // PETS / HEWAN
    // ==========================================
    Route::get('/hewan-saya', [PetController::class, 'index'])
        ->name('hewan-saya');

    Route::get('/pets/create', [PetController::class, 'create'])
        ->name('pets.create');

    Route::post('/pets', [PetController::class, 'store'])
        ->name('pets.store');

    Route::get('/pets/{pet}', [PetController::class, 'show'])
        ->name('pets.show');

    Route::get('/pets/{pet}/edit', [PetController::class, 'edit'])
        ->name('pets.edit');

    Route::put('/pets/{pet}', [PetController::class, 'update'])
        ->name('pets.update');

    Route::delete('/pets/{pet}', [PetController::class, 'destroy'])
        ->name('pets.destroy');

    // ==========================================
    // RIWAYAT KESEHATAN
    // ==========================================
    Route::get('/riwayat', [RiwayatKesehatanController::class, 'index'])
        ->name('riwayat');

    Route::get('/riwayat/create', [RiwayatKesehatanController::class, 'create'])
        ->name('riwayat.create');

    Route::post('/riwayat', [RiwayatKesehatanController::class, 'store'])
        ->name('riwayat.store');

    Route::get('/riwayat/{id}/edit', [RiwayatKesehatanController::class, 'edit'])
        ->name('riwayat.edit');

    Route::put('/riwayat/{id}', [RiwayatKesehatanController::class, 'update'])
        ->name('riwayat.update');

    Route::delete('/riwayat/{id}', [RiwayatKesehatanController::class, 'destroy'])
        ->name('riwayat.destroy');

    // ==========================================
    // PENGINGAT
    // ==========================================
    Route::get('/pengingat', [PengingatController::class, 'index'])
        ->name('pengingat.list');

    Route::get('/pengingat/create', [PengingatController::class, 'create'])
        ->name('pengingat.create');

    Route::post('/pengingat', [PengingatController::class, 'store'])
        ->name('pengingat.store');

    Route::post('/pengingat/{id}/selesai', [PengingatController::class, 'selesai'])
        ->name('pengingat.selesai');

    Route::delete('/pengingat/{id}', [PengingatController::class, 'delete'])
        ->name('pengingat.delete');

    // ==========================================
    // SETTINGS
    // ==========================================
    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings.index');

    Route::get('/settings/notifications', [SettingsController::class, 'notifications'])
        ->name('settings.notifications');

    Route::post('/settings/notifications', [SettingsController::class, 'updateNotifications'])
        ->name('settings.notifications.update');

    Route::get('/settings/security', [SettingsController::class, 'security'])
        ->name('settings.security');

    Route::put('/settings/security/password', [SettingsController::class, 'updatePassword'])
        ->name('settings.security.password');

    Route::get('/settings/privacy', [SettingsController::class, 'privacy'])
        ->name('settings.privacy');

    Route::post('/settings/privacy', [SettingsController::class, 'updatePrivacy'])
        ->name('settings.privacy.update');

    Route::get('/settings/help', [SettingsController::class, 'help'])
        ->name('settings.help');

    Route::get('/settings/download-data', [SettingsController::class, 'downloadData'])
        ->name('settings.download.data');

    Route::get('/settings/account', [SettingsController::class, 'account'])
        ->name('settings.account');

    Route::post('/settings/account', [SettingsController::class, 'updateAccount'])
        ->name('settings.account.update');

    // ==========================================
    // NOTIFICATIONS
    // ==========================================
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/read/{id}', [NotificationController::class, 'markRead']);

    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead']);

    Route::delete('/notifications/{id}', [NotificationController::class, 'delete']);

    Route::delete('/notifications', [NotificationController::class, 'deleteAll']);

    // ==========================================
    // PROFILE
    // ==========================================
    Route::get('/profil', [ProfileController::class, 'index'])
        ->name('profile.index');

    Route::get('/user/{id}', [ProfileController::class, 'show'])
        ->name('profile.show');

    Route::get('/profil/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profil', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.updatePassword');

    // ✅ HAPUS AKUN
    Route::delete('/profil/delete', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // ==========================================
    // CHATBOT
    // ==========================================
    Route::get('/chatbot', [ChatbotController::class, 'index'])
        ->name('chatbot.index');

    Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])
        ->name('chatbot.send');

    Route::get('/chatbot/history', [ChatbotController::class, 'history'])
        ->name('chatbot.history');

    Route::get('/chatbot/new', [ChatbotController::class, 'newSession'])
        ->name('chatbot.new-session');
    Route::delete('/chatbot/session/{sessionId}', [ChatbotController::class, 'deleteSession'])->name('chatbot.delete-session');
Route::post('/chatbot/session/{sessionId}/pin', [ChatbotController::class, 'pinSession'])->name('chatbot.pin-session');
});

// ==========================================
// FORUM
// ==========================================
Route::get('/forum', [ForumController::class, 'index'])
    ->name('forum.index');

Route::get('/forum/{comment}', [ForumController::class, 'show'])
    ->name('forum.show');

// ==========================================
// FORGOT PASSWORD
// ==========================================
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])
    ->name('password.update');

// ==========================================
// LEGAL PAGES
// ==========================================
Route::get('/terms', function () {
    return view('legal.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('privacy');

Route::get('/open-reset-password', function (Request $request) {
    $email = urlencode($request->query('email'));
    $token = $request->query('token');

    return redirect("ingoncare://reset-password?email={$email}&token={$token}");
});