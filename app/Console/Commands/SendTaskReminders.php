<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\NotificationLog;
use App\Services\FirebaseService;
use Carbon\Carbon;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-task-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Firebase notifications for pending task reminders';

    /**
     * Execute the console command.
     */
    public function handle(FirebaseService $firebase)
    {
        $this->info('Checking task reminders...');

        $tasks = Task::where('notification_sent', 0)
            ->where('status', '!=', 'Completed')
            ->where('reminder_datetime', '<=', Carbon::now())
            ->with('user')
            ->get();

        if ($tasks->count() == 0) {
            $this->info('No reminders found.');
            return Command::SUCCESS;
        }

        foreach ($tasks as $task) {

            if (!$task->user) {
                continue;
            }

            if (empty($task->user->fcm_token)) {
                continue;
            }

            try {

                // Send Firebase Push Notification
                $firebase->sendNotification(
                    $task->user->fcm_token,
                    '🔔 Task Reminder',
                    $task->task_name
                );

                // Save Notification Log
                NotificationLog::create([
                    'task_id'            => $task->id,
                    'user_id'            => $task->user_id,
                    'title'              => '🔔 Task Reminder',
                    'message'            => '"' . $task->task_name . '" reminder has been triggered.',
                    'type'               => 'Reminder',
                    'icon'               => 'bell',
                    'notification_time'  => now(),
                    'status'             => 'Unread',
                    'is_read'            => false,
                ]);

                // Update Task
                $task->notification_sent = 1;
                $task->save();

                $this->info("✅ Notification sent for Task ID {$task->id}");

            } catch (\Exception $e) {

                $this->error("❌ Error for Task {$task->id}: " . $e->getMessage());

            }
        }

        $this->info('🎉 All reminders processed successfully.');

        return Command::SUCCESS;
    }
}