<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\NotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // --- Date range filter ---
        $from = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : Carbon::now()->subDays(30)->startOfDay();

        $to = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : Carbon::now()->endOfDay();

        // ---------------------------------------------------------------
        // TASK REPORT
        // ---------------------------------------------------------------
        $taskQuery = Task::where('user_id', $userId)
            ->whereBetween('created_at', [$from, $to]);

        if ($request->filled('status')) {
            $taskQuery->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $taskQuery->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $taskQuery->where('category', $request->category);
        }

        $tasks = $taskQuery->orderBy('created_at', 'desc')->get();

        // Task summary stats
        $taskStats = [
            'total'       => $tasks->count(),
            'completed'   => $tasks->where('status', 'Completed')->count(),
            'in_progress' => $tasks->where('status', 'In Progress')->count(),
            'pending'     => $tasks->where('status', 'Pending')->count(),
            'overdue'     => $tasks->filter(fn($t) =>
                $t->status !== 'Completed' &&
                $t->reminder_datetime &&
                Carbon::parse($t->reminder_datetime)->isPast()
            )->count(),
        ];

        $taskStats['completion_rate'] = $taskStats['total'] > 0
            ? round(($taskStats['completed'] / $taskStats['total']) * 100)
            : 0;

        // Priority breakdown
        $priorityBreakdown = $tasks->groupBy('priority')->map->count();

        // Category breakdown
        $categoryBreakdown = $tasks->groupBy('category')->map->count();

        // ---------------------------------------------------------------
        // REMINDER REPORT
        // ---------------------------------------------------------------
        $reminderQuery = NotificationLog::where('user_id', $userId)
            ->whereBetween('notification_time', [$from, $to]);

        $reminders = $reminderQuery->latest('notification_time')->get();

        $reminderStats = [
            'total'  => $reminders->count(),
            'read'   => $reminders->where('is_read', true)->count(),
            'unread' => $reminders->where('is_read', false)->count(),
        ];

        // Daily reminder count for the chart (last 7 days within range)
        $dailyReminders = NotificationLog::where('user_id', $userId)
            ->whereBetween('notification_time', [
                max($from, Carbon::now()->subDays(6)->startOfDay()),
                $to
            ])
            ->get()
            ->groupBy(fn($n) => Carbon::parse($n->notification_time)->format('d M'))
            ->map->count();

        // Fill all 7 days even if count is 0
        $chartDays = [];
        $chartCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $label = Carbon::now()->subDays($i)->format('d M');
            $chartDays[]   = $label;
            $chartCounts[] = $dailyReminders[$label] ?? 0;
        }

        // All distinct categories for filter dropdown
        $categories = Task::where('user_id', $userId)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('reports.index', compact(
            'tasks',
            'taskStats',
            'priorityBreakdown',
            'categoryBreakdown',
            'reminders',
            'reminderStats',
            'chartDays',
            'chartCounts',
            'from',
            'to',
            'categories'
        ));
    }
}