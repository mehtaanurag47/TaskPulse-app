<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. OPTIMIZED SINGLE HIT COUNTER ARRAY (NO DATABASE CHANGE)
        // Ek hi baar me queries optimized aggregates ke sath memory load standard par calculate hongi
        $taskStats = Task::where('user_id', $userId)
            ->selectRaw("
                COUNT(*) as total,
                COUNT(CASE WHEN status = 'Pending' THEN 1 END) as pending,
                COUNT(CASE WHEN status = 'Completed' THEN 1 END) as completed,
                COUNT(CASE WHEN DATE(reminder_datetime) = CURRENT_DATE THEN 1 END) as today,
                COUNT(CASE WHEN reminder_datetime < NOW() AND status != 'Completed' THEN 1 END) as overdue,
                COUNT(CASE WHEN priority = 'High' THEN 1 END) as high,
                COUNT(CASE WHEN priority = 'Medium' THEN 1 END) as medium,
                COUNT(CASE WHEN priority = 'Low' THEN 1 END) as low
            ")
            ->first();

        // Assigning static layout data metrics securely
        $totalTasks     = $taskStats->total ?? 0;
        $pendingTasks   = $taskStats->pending ?? 0;
        $completedTasks = $taskStats->completed ?? 0;
        $todayTasks     = $taskStats->today ?? 0;
        $overdueTasks   = $taskStats->overdue ?? 0;
        
        $highTasks      = $taskStats->high ?? 0;
        $mediumTasks    = $taskStats->medium ?? 0;
        $lowTasks       = $taskStats->low ?? 0;

        // 2. RECENT TASKS FEED (LIMIT 5)
        $recentTasks = Task::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // 3. UPCOMING REMINDERS (FILTERED OPEN TRACK LOOPS)
        $upcomingReminders = Task::where('user_id', $userId)
            ->where('status', '!=', 'Completed')
            ->where('reminder_datetime', '>=', now()) // Tacked logic safely for future logs
            ->orderBy('reminder_datetime', 'asc')
            ->take(5)
            ->get();

        // 4. CATEGORY DYNAMIC PLUCKS FOR HORIZONTAL CHARTJS
        $categoryData = Task::where('user_id', $userId)
            ->select('category', DB::raw('COUNT(*) as total'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->get();

        // Plucking array metrics for clean front-end loop injections
        $categoryLabels = $categoryData->pluck('category')->toArray();
        $categoryCounts = $categoryData->pluck('total')->toArray();

        // 5. NOTIFICATION CENTER FEED PIPELINE
        $notifications = NotificationLog::where('user_id', $userId)
            ->latest()
            ->take(10)
            ->get();

        $notificationCount = NotificationLog::where('user_id', $userId)
            ->where('status', '!=', 'Read') // Safe unread filter check if database permits, else defaults safely
            ->count();

        // Return array stream to active views cleanly
        return view('dashboard', compact(
            'totalTasks',
            'pendingTasks',
            'completedTasks',
            'todayTasks',
            'overdueTasks',
            'recentTasks',
            'upcomingReminders',
            'highTasks',
            'mediumTasks',
            'lowTasks',
            'categoryLabels',
            'categoryCounts',
            'notifications',
            'notificationCount'
        ));
    }
}