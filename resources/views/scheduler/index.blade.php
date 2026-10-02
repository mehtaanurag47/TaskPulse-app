@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom dynamic-border">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; background: linear-gradient(135deg, #e62222 0%, #b81414 100%);">
                <i class="bi bi-clock-history text-warning fs-3"></i>
            </div>
            <div>
                <h3 class="fw-bold dynamic-text tracking-tight mb-0">Scheduler Matrix</h3>
                <span class="fw-semibold font-monospace dynamic-subtitle" style="font-size: 11px; letter-spacing: 0.05em;">REMINDER ENGINE &amp; FCM DISPATCH TIMELINE</span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge {{ $lastRunAt && $lastRunAt->diffInMinutes(now()) < 2 ? 'bg-success' : 'bg-secondary' }} px-3 py-2 fs-7">
                <i class="bi bi-{{ $lastRunAt && $lastRunAt->diffInMinutes(now()) < 2 ? 'check-circle' : 'exclamation-circle' }}-fill me-1"></i>
                {{ $lastRunAt && $lastRunAt->diffInMinutes(now()) < 2 ? 'Scheduler Active' : 'Scheduler Idle' }}
            </span>
        </div>
    </div>

    {{-- Health summary cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border dynamic-border shadow-sm h-100" style="border-radius: 10px;">
                <div class="card-body">
                    <span class="dynamic-muted fw-medium fs-7">Scheduler Interval</span>
                    <h4 class="fw-bold dynamic-text mt-1 mb-0">{{ $scheduleInterval }}</h4>
                    <small class="dynamic-muted">app:send-task-reminders</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border dynamic-border shadow-sm h-100" style="border-radius: 10px;">
                <div class="card-body">
                    <span class="dynamic-muted fw-medium fs-7">Last Confirmed Run</span>
                    <h4 class="fw-bold dynamic-text mt-1 mb-0">
                        {{ $lastRunAt ? $lastRunAt->diffForHumans() : 'No runs logged yet' }}
                    </h4>
                    <small class="dynamic-muted">
                        {{ $lastRunAt ? $lastRunAt->format('d M Y, h:i:s A') : 'Make sure php artisan schedule:work is running' }}
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border dynamic-border shadow-sm h-100" style="border-radius: 10px;">
                <div class="card-body">
                    <span class="dynamic-muted fw-medium fs-7">Reminders Queued</span>
                    <h4 class="fw-bold dynamic-text mt-1 mb-0">{{ $upcomingQueue->count() }}</h4>
                    <small class="dynamic-muted">Waiting to be dispatched</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- Upcoming queue --}}
        <div class="col-lg-6">
            <div class="card border dynamic-border shadow-sm h-100" style="border-radius: 10px;">
                <div class="card-header dynamic-card-header border-bottom dynamic-border py-3">
                    <h6 class="fw-bold dynamic-text mb-0 fs-7">
                        <i class="bi bi-hourglass-split text-warning me-1"></i> Upcoming Reminder Queue
                    </h6>
                </div>
                <div class="card-body p-0" style="max-height: 420px; overflow-y: auto;">
                    @forelse($upcomingQueue as $task)
                        <div class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom dynamic-border">
                            <div>
                                <span class="fw-semibold dynamic-text d-block fs-7">{{ $task->task_name }}</span>
                                <small class="dynamic-muted">
                                    {{ \Carbon\Carbon::parse($task->reminder_datetime)->format('d M Y, h:i A') }}
                                    &middot;
                                    {{ \Carbon\Carbon::parse($task->reminder_datetime)->isPast() ? 'overdue' : \Carbon\Carbon::parse($task->reminder_datetime)->diffForHumans() }}
                                </small>
                            </div>
                            <span class="badge {{ \Carbon\Carbon::parse($task->reminder_datetime)->isPast() ? 'bg-danger' : 'bg-warning text-dark' }}">
                                {{ \Carbon\Carbon::parse($task->reminder_datetime)->isPast() ? 'Due' : 'Pending' }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center dynamic-muted py-5">
                            <i class="bi bi-inbox fs-2"></i>
                            <p class="mb-0 mt-2">No reminders currently queued.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recently sent --}}
        <div class="col-lg-6">
            <div class="card border dynamic-border shadow-sm h-100" style="border-radius: 10px;">
                <div class="card-header dynamic-card-header border-bottom dynamic-border py-3">
                    <h6 class="fw-bold dynamic-text mb-0 fs-7">
                        <i class="bi bi-bell-fill text-success me-1"></i> Recently Dispatched
                    </h6>
                </div>
                <div class="card-body p-0" style="max-height: 420px; overflow-y: auto;">
                    @forelse($recentlySent as $log)
                        <div class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom dynamic-border">
                            <div>
                                <span class="fw-semibold dynamic-text d-block fs-7">{{ $log->title }}</span>
                                <small class="dynamic-muted">{{ $log->message }}</small>
                            </div>
                            <small class="dynamic-muted text-nowrap ms-2">
                                {{ optional($log->notification_time)->diffForHumans() }}
                            </small>
                        </div>
                    @empty
                        <div class="text-center dynamic-muted py-5">
                            <i class="bi bi-bell-slash fs-2"></i>
                            <p class="mb-0 mt-2">No reminders have been dispatched yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- Raw scheduler log --}}
    <div class="card border dynamic-border shadow-sm mt-3" style="border-radius: 10px;">
        <div class="card-header dynamic-card-header border-bottom dynamic-border py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold dynamic-text mb-0 fs-7">
                <i class="bi bi-terminal me-1"></i> Raw Scheduler Run Log
            </h6>
            <small class="dynamic-muted">storage/logs/scheduler.log &middot; last 40 lines</small>
        </div>
        <div class="card-body p-0">
            <pre class="m-0 p-3 fs-8" style="max-height: 320px; overflow-y: auto; background: rgba(0,0,0,0.03); white-space: pre-wrap; font-family: 'Courier New', monospace; line-height: 1.6;">@if(count($logLines) > 0)
@foreach($logLines as $line){{ $line }}
@endforeach
@else
No scheduler.log entries yet. Make sure 'php artisan schedule:work' is running and routes/console.php has ->appendOutputTo(storage_path('logs/scheduler.log')).
@endif</pre>
        </div>
    </div>

</div>
@endsection