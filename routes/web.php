<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SchedulerController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | Tasks
    |--------------------------------------------------------------------------
    */

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/create', [TaskController::class, 'create']);
    Route::post('/tasks/store', [TaskController::class, 'store']);

    Route::get('/tasks/edit/{id}', [TaskController::class, 'edit']);
    Route::post('/tasks/update/{id}', [TaskController::class, 'update']);

    Route::get('/tasks/delete/{id}', [TaskController::class, 'delete']);

    /*
    |--------------------------------------------------------------------------
    | AJAX API
    |--------------------------------------------------------------------------
    */

    Route::get('/api/tasks', [TaskController::class, 'index']);
    Route::get('/api/tasks/stats', [TaskController::class, 'index']);

    Route::patch('/api/tasks/{id}/toggle', [TaskController::class, 'completeReminder']);
    Route::delete('/api/tasks/{id}', [TaskController::class, 'delete']);

    /*
    |--------------------------------------------------------------------------
    | Reminders
    |--------------------------------------------------------------------------
    */

    Route::get('/pending-reminder', [TaskController::class, 'getPendingReminder']);

    Route::post('/reminder/complete/{id}', [TaskController::class, 'completeReminder']);
    Route::post('/reminder/dismiss/{id}', [TaskController::class, 'dismissReminder']);

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    Route::get('/notifications', [NotificationController::class, 'index']);

    Route::get('/scheduler', [SchedulerController::class, 'index']);

    Route::get('/reports', [ReportController::class, 'index']);

    Route::get('/test-notification', [NotificationController::class, 'sendTestNotification']);

    Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead']);

    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    Route::post('/notifications/purge', [NotificationController::class, 'purgeOldLogs']);

    Route::delete('/notifications/delete/{id}', [NotificationController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Firebase Token
    |--------------------------------------------------------------------------
    */

    Route::post('/save-fcm-token', function (Request $request) {

        $request->validate([
            'token' => 'required'
        ]);

        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user->update([
            'fcm_token' => $request->token
        ]);

        return response()->json([
            'success' => true,
            'message' => 'FCM Token Saved Successfully'
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout']);

});