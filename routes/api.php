<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiPetController;
use App\Http\Controllers\Api\ApiPengingatController;
use App\Http\Controllers\Api\ApiRiwayatController;
use App\Http\Controllers\Api\ApiProfileController;
use App\Http\Controllers\Api\ApiChatbotController;
use App\Http\Controllers\Api\ApiForumController;

use App\Http\Controllers\Api\ApiFollowController;
use App\Http\Controllers\Api\ApiNotificationController;

/*
|--------------------------------------------------------------------------
| API Routes - IngonCare Mobile
|--------------------------------------------------------------------------
|
| Endpoint untuk Flutter mobile app.
| Base URL: http://<ip>:8000/api
|
*/

// ==========================================
// PUBLIC ROUTES (tanpa auth)
// ==========================================

Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/login-direct', [ApiAuthController::class, 'loginDirect']);
Route::post('/verify-otp', [ApiAuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [ApiAuthController::class, 'resendOtp']);
Route::post('/forgot-password', [ApiAuthController::class, 'forgotPassword']);
Route::post('/reset-password', [ApiAuthController::class, 'resetPassword']);
Route::post('/reset-password-direct', [ApiAuthController::class, 'resetPasswordDirect']);

// Forum public (bisa dilihat tanpa login)
Route::get('/forum', [ApiForumController::class, 'index']);
Route::get('/forum/{id}', [ApiForumController::class, 'show']);
Route::get('/forum-search', [ApiForumController::class, 'search']);

// ==========================================
// PROTECTED ROUTES (perlu token Sanctum)
// ==========================================

Route::middleware('auth:sanctum')->group(function () {

    // --- Auth ---
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/me', [ApiAuthController::class, 'me']);

    // --- Profile ---
    Route::get('/profile', [ApiProfileController::class, 'index']);
    Route::post('/profile', [ApiProfileController::class, 'update']);
    Route::put('/profile/password', [ApiProfileController::class, 'updatePassword']);
    Route::delete('/profile', [ApiProfileController::class, 'destroy']);

    // --- Pets ---
    Route::get('/pets', [ApiPetController::class, 'index']);
    Route::get('/pets/{id}', [ApiPetController::class, 'show']);
    Route::post('/pets', [ApiPetController::class, 'store']);
    Route::post('/pets/{id}', [ApiPetController::class, 'update']); // POST karena multipart/form-data
    Route::delete('/pets/{id}', [ApiPetController::class, 'destroy']);

    // --- Pengingat ---
    Route::get('/pengingat', [ApiPengingatController::class, 'index']);
    Route::post('/pengingat', [ApiPengingatController::class, 'store']);
    Route::post('/pengingat/{id}/selesai', [ApiPengingatController::class, 'selesai']);
    Route::delete('/pengingat/{id}', [ApiPengingatController::class, 'destroy']);

    // --- Riwayat Kesehatan ---
    Route::get('/riwayat', [ApiRiwayatController::class, 'index']);
    Route::post('/riwayat', [ApiRiwayatController::class, 'store']);
    Route::put('/riwayat/{id}', [ApiRiwayatController::class, 'update']);
    Route::delete('/riwayat/{id}', [ApiRiwayatController::class, 'destroy']);

    // --- Chatbot ---
    Route::get('/chatbot/sessions', [ApiChatbotController::class, 'sessions']);
    Route::get('/chatbot/history', [ApiChatbotController::class, 'history']);
    Route::get('/chatbot/new-session', [ApiChatbotController::class, 'newSession']);
    Route::post('/chatbot/send', [ApiChatbotController::class, 'sendMessage']);
    Route::delete('/chatbot/session/{sessionId}', [ApiChatbotController::class, 'deleteSession']);

    // --- Forum (actions yang butuh login) ---
    Route::post('/forum', [ApiForumController::class, 'store']);
    Route::post('/forum/{id}/like', [ApiForumController::class, 'like']);
    Route::delete('/forum/{id}', [ApiForumController::class, 'destroy']);

    // --- Follow ---
    Route::post('/users/{id}/follow', [ApiFollowController::class, 'follow']);
    Route::post('/users/{id}/unfollow', [ApiFollowController::class, 'unfollow']);
    Route::get('/users/{id}/followers', [ApiFollowController::class, 'followers']);
    Route::get('/users/{id}/following', [ApiFollowController::class, 'following']);
    Route::get('/users/{id}/profile', [ApiFollowController::class, 'userProfile']);
    Route::get('/users/search', [ApiFollowController::class, 'searchUsers']);

    // --- Notifications ---
    Route::get('/notifications', [ApiNotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [ApiNotificationController::class, 'markRead']);
    Route::post('/notifications/read-all', [ApiNotificationController::class, 'markAllRead']);
    Route::delete('/notifications/{id}', [ApiNotificationController::class, 'destroy']);
});
