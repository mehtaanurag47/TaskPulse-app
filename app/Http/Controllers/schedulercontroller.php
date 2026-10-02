<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SchedulerController extends Controller
{
    /**
     * Show the Scheduler Matrix page: upcoming reminders in queue,
     * recently sent notifications, and raw scheduler run history.
     */
    public function index()
    {
        $userId = Auth::id();

        // Reminders still waiting to be sent (queued for the scheduler)
        $upcomingQueue = Task::where('user_id', $userId)
            ->where('notification_sent', 0)
            ->where('status', '!=', 'Completed')
            ->whereNotNull('reminder_datetime')
            ->orderBy('reminder_datetime', 'asc')
            ->take(15)
            ->get();

        // Reminders that have already fired
        $recentlySent = NotificationLog::where('user_id', $userId)
            ->where('type', 'Reminder')
            ->latest('notification_time')
            ->take(15)
            ->get();

        // Raw scheduler.log tail, so the actual automatic run history is visible
        $logPath = storage_path('logs/scheduler.log');
        $logLines = [];

        if (File::exists($logPath)) {
            $allLines = preg_split('/\r\n|\r|\n/', File::get($logPath));
            $allLines = array_filter($allLines, fn($line) => trim($line) !== '');
            $logLines = array_slice($allLines, -40); // last 40 lines
            $logLines = array_reverse($logLines);     // newest first
        }

        $lastRunAt = File::exists($logPath)
            ? Carbon::createFromTimestamp(File::lastModified($logPath))
            : null;

        // NOTE: this reflects routes/console.php's Schedule::command() frequency.
        // Update this label manually if you change the interval there.
        $scheduleInterval = 'Every 20 Seconds';

        return view('scheduler.index', compact(
            'upcomingQueue',
            'recentlySent',
            'logLines',
            'lastRunAt',
            'scheduleInterval'
        ));
    }
}