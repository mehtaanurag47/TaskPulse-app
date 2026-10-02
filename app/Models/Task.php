<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    /**
     * The table associated with the model.
     * (Optional but strictly maps to the database 'tasks' table)
     */
    protected $table = 'tasks';

    /**
     * The attributes that are mass assignable.
     * In saari fields me database directly entry accept karega.
     */
    protected $fillable = [
        'user_id',
        'task_name',
        'description',
        'category',
        'priority',
        'reminder_datetime',
        'status',
        'notification_sent',
        'acknowledged'
    ];

    /**
     * The attributes that should be cast to native types.
     * Boolean data flags aur timestamps ko automatically carbon instances me convert karne ke liye.
     */
    protected $casts = [
        'reminder_datetime' => 'datetime',
        'notification_sent' => 'boolean',
        'acknowledged'      => 'boolean',
    ];

    /**
     * Relationship: Ek task hamesha kisi ek specific User ka hota hai.
     * Usage Example: $task->user->name
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: Ek task ke multiple notification logs generate ho sakte hain.
     * Usage Example: $task->notifications
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(NotificationLog::class, 'task_id');
    }
}