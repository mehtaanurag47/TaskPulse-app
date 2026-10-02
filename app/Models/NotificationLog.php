<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
protected $fillable = [
    'task_id',
    'user_id',

    'title',
    'message',
    'type',
    'icon',

    'notification_time',

    'status',
    'is_read'
];

    /**
     * The attributes that should be cast to native types.
     * (Dates ko modern Carbon instances me safe handling ke liye)
     */
protected $casts = [
    'notification_time' => 'datetime',
    'is_read' => 'boolean',
];

    /**
     * Relationship: Ek notification log kisi ek specific Task se belongs karta hai.
     * Usage: $notification->task->task_name
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    /**
     * Relationship: Ek notification log kisi ek specific User se belongs karta hai.
     * Usage: $notification->user->name
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}