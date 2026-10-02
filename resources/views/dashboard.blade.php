@extends('layouts.app')

@section('content')
@php
    $dashboardTaskSource = collect($tasks ?? $recentTasks ?? []);
    $dashboardReminderNotifications = $dashboardTaskSource
        ->filter(function ($task) {
            return in_array(($task->status ?? ''), ['Pending', 'In Progress'])
                && !empty($task->reminder_datetime);
        })
        ->merge(isset($upcomingReminders) ? collect($upcomingReminders) : collect())
        ->unique(function ($task) {
            return $task->id ?? (($task->task_name ?? 'task') . '-' . ($task->reminder_datetime ?? 'time'));
        })
        ->sortBy('reminder_datetime')
        ->values();
    $dashboardReminders = (isset($upcomingReminders) && count($upcomingReminders) > 0)
        ? collect($upcomingReminders)
        : $dashboardTaskSource
            ->filter(function ($task) {
                return !empty($task->reminder_datetime) && ($task->status ?? '') !== 'Completed';
            })
            ->sortBy('reminder_datetime')
            ->take(5);
    $dashboardEvents = (isset($notifications) && count($notifications) > 0)
        ? collect($notifications)
        : $dashboardTaskSource->take(6);
    $dashboardNotificationCount = $notificationCount;
@endphp
<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom dynamic-border">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; background: linear-gradient(135deg, #e62222 0%, #b81414 100%);">
                <i class="bi bi-star-fill text-warning fs-3 animate-pulse"></i>
            </div>
            <div>
                <h3 class="fw-bold dynamic-text tracking-tight mb-0" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">Star Cement Limited</h3>
                <span class="fw-semibold font-monospace dynamic-subtitle" style="font-size: 11px; letter-spacing: 0.05em;">TASKPULSE CONTROL CONSOLE</span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            

            <div class="dropdown">
                
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border dynamic-border dynamic-dropdown p-2 mt-2" style="border-radius: 8px; min-width: 220px;">
                    <li class="px-3 py-2 border-bottom dynamic-border mb-1">
                        <span class="fw-bold dynamic-text d-block fs-7">Operations Workspace</span>
                        <small class="dynamic-muted text-truncate d-block">Session secured and synchronized</small>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2 py-2 text-danger fw-semibold d-flex align-items-center gap-2"
                           href="#"
                           onclick="event.preventDefault(); document.getElementById('logout-action-form').submit();">
                            <i class="bi bi-box-arrow-right fs-5"></i> Sign Out Console
                        </a>
                    </li>
                </ul>
                <form id="logout-action-form" action="/logout" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-12">
            <h2 class="fw-bold dynamic-text tracking-tight mb-1">Welcome Back </h2>
            <p class="dynamic-muted mb-0 fs-7">
                Monitor live operations, real-time telemetry filters, and active task vectors seamlessly.
            </p>
        </div>
    </div>

    {{-- âœ… HERO CARD: Always dark background â€” text-white always visible --}}
    <div class="hero-dark-card mb-4">
        <div class="p-4 p-md-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="hero-badge mb-3 d-inline-block">
                        Enterprise Console Hub
                    </span>
                    <h1 class="fw-bold tracking-tight mb-2 text-white" style="font-size: 1.75rem;">
                        TaskPulse Productivity Control Matrix
                    </h1>
                    <p class="mb-4 fs-7 text-white" style="max-width: 600px; opacity: 0.6;">
                        Analyze real-time distribution arrays, monitor active workspace variables, and isolate priority bottlenecks immediately.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="hero-stat-badge">
                            Total Registry: <strong class="ms-1 text-white">{{ $totalTasks ?? 0 }}</strong>
                        </span>
                        <span class="hero-stat-badge">
                            Active Shifts: <strong class="ms-1 text-white">{{ $todayTasks ?? 0 }}</strong>
                        </span>
                    </div>
                </div>
                <div class="col-lg-4 text-center text-lg-end d-none d-lg-block" style="border-left: 1px solid rgba(255,255,255,0.1);">
                    <div class="pe-4">
                        <h1 class="display-4 fw-bold text-white mb-0 tracking-tight">
                            {{ (isset($totalTasks) && $totalTasks > 0) ? round(($completedTasks/$totalTasks)*100) : 0 }}%
                        </h1>
                        <p class="fw-medium mb-0 fs-7 text-white" style="opacity: 0.6;">Mean Closure Velocity Score</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- COMMAND SNAPSHOT --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card dynamic-card border dynamic-border shadow-sm rounded-3 h-100 dashboard-feature-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="dynamic-muted fw-medium fs-7">Closure Gap</span>
                        <i class="bi bi-activity text-danger fs-5"></i>
                    </div>
                    <h4 class="fw-bold dynamic-text mb-1">{{ max(($totalTasks ?? 0) - ($completedTasks ?? 0), 0) }}</h4>
                    <small class="dynamic-muted">Tasks still moving through the console.</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card dynamic-card border dynamic-border shadow-sm rounded-3 h-100 dashboard-feature-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="dynamic-muted fw-medium fs-7">High Priority Load</span>
                        <i class="bi bi-lightning-charge-fill text-warning fs-5"></i>
                    </div>
                    <h4 class="fw-bold dynamic-text mb-1">{{ $highTasks ?? 0 }}</h4>
                    <small class="dynamic-muted">Escalated work requiring sharper focus.</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card dynamic-card border dynamic-border shadow-sm rounded-3 h-100 dashboard-feature-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="dynamic-muted fw-medium fs-7">Reminder Queue</span>
                        <i class="bi bi-bell-fill text-primary fs-5"></i>
                    </div>
                    <h4 class="fw-bold dynamic-text mb-1">{{ isset($upcomingReminders) ? count($upcomingReminders) : 0 }}</h4>
                    <small class="dynamic-muted">Scheduled alerts waiting in timeline.</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card dynamic-card border dynamic-border shadow-sm rounded-3 h-100 dashboard-feature-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="dynamic-muted fw-medium fs-7">Live Console Time</span>
                        <i class="bi bi-clock-history text-success fs-5"></i>
                    </div>
                    <h4 id="taskpulseClock" class="fw-bold dynamic-text mb-1">--:--</h4>
                    <small class="dynamic-muted">Current session heartbeat.</small>
                </div>
            </div>
        </div>
    </div>
    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card dynamic-card border dynamic-border shadow-sm rounded-3 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="dynamic-muted fw-medium fs-7 mb-1">Total Tracked</p>
                            <h3 class="fw-bold dynamic-text tracking-tight mb-0">{{ $totalTasks ?? 0 }}</h3>
                        </div>
                        <div class="dynamic-badge-light border dynamic-border rounded-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-stack fs-5 dynamic-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card dynamic-card border dynamic-border shadow-sm rounded-3 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-success fw-medium fs-7 mb-1">Completed Vault</p>
                            <h3 class="fw-bold text-success tracking-tight mb-0">{{ $completedTasks ?? 0 }}</h3>
                        </div>
                        <div class="bg-success-subtle text-success rounded-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-patch-check-fill fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card dynamic-card border dynamic-border shadow-sm rounded-3 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="fw-medium fs-7 mb-1" style="color: #e62222;">Pending Processing</p>
                            <h3 class="fw-bold tracking-tight mb-0" style="color: #e62222;">{{ $pendingTasks ?? 0 }}</h3>
                        </div>
                        <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: rgba(230, 34, 34, 0.1); color: #e62222;">
                            <i class="bi bi-hourglass-split fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card dynamic-card border dynamic-border shadow-sm rounded-3 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-danger fw-medium fs-7 mb-1">Overdue Violations</p>
                            <h3 class="fw-bold text-danger tracking-tight mb-0">{{ $overdueTasks ?? 0 }}</h3>
                        </div>
                        <div class="bg-danger-subtle text-danger rounded-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-exclamation-octagon-fill fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">

            {{-- TASK SEARCH CONSOLE --}}
            <div class="card dynamic-card border dynamic-border shadow-sm mb-4 rounded-3">
                <div class="card-body p-3">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-7">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text dynamic-badge-light dynamic-border border"><i class="bi bi-search dynamic-muted"></i></span>
                                <input id="taskPulseSearch" type="text" class="form-control dynamic-card dynamic-text dynamic-border border" placeholder="Filter visible task registry">
                            </div>
                        </div>
                        <div class="col-md-5 d-flex gap-2 justify-content-md-end">
                            <button type="button" class="btn btn-sm dynamic-badge-light border dynamic-border dynamic-text fw-semibold rounded-2" onclick="clearTaskPulseSearch()">
                                <i class="bi bi-x-circle me-1"></i> Clear
                            </button>
                           
                        </div>
                    </div>
                </div>
            </div>
            {{-- RECENT TASKS TABLE --}}
            <div class="card dynamic-card border dynamic-border shadow-sm mb-4 rounded-3 overflow-hidden">
                <div class="card-header dynamic-card-header border-bottom dynamic-border py-3 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold dynamic-text mb-0">Recent Operational Log Registry</h6>
                    <a href="/tasks" class="btn dynamic-badge-light border dynamic-border dynamic-text btn-sm fw-semibold rounded-2 fs-8 px-3">
                        Access All Indexes
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle dynamic-table table-hover mb-0" style="font-size: 14px;">
                        <thead class="dynamic-table-head border-bottom dynamic-border text-uppercase tracking-wider" style="font-size: 11px; font-weight: 600;">
                            <tr>
                                <th scope="col" class="ps-4 py-3">Task Objective</th>
                                <th scope="col" class="py-3">Categorization</th>
                                <th scope="col" class="py-3">Priority Factor</th>
                                <th scope="col" class="pe-4 py-3">State</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @if(isset($recentTasks) && count($recentTasks) > 0)
                                @foreach($recentTasks as $task)
                                    <tr class="dynamic-tr">
                                        <td class="py-3 ps-4 fw-semibold dynamic-text">{{ $task->task_name }}</td>
                                        <td>
                                            <span class="badge dynamic-badge-light dynamic-text border dynamic-border rounded-2 px-3 py-2 fw-medium">
                                                {{ $task->category ?? 'General Default' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($task->priority=='High')
                                                <span class="badge bg-danger-subtle text-danger rounded-2 px-2 py-1 fs-8">High</span>
                                            @elseif($task->priority=='Medium')
                                                <span class="badge bg-warning-subtle text-warning-emphasis rounded-2 px-2 py-1 fs-8">Medium</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success rounded-2 px-2 py-1 fs-8">Low</span>
                                            @endif
                                        </td>
                                        <td class="pe-4">
                                            @if($task->status=='Completed')
                                                <span class="badge bg-success text-white rounded-pill px-3 py-1 fs-8"><i class="bi bi-circle-fill" style="font-size: 5px;"></i> Completed</span>
                                            @elseif($task->status=='Pending')
                                                <span class="badge text-white rounded-pill px-3 py-1 fs-8" style="background-color: #e62222;"><i class="bi bi-circle-fill" style="font-size: 5px;"></i> Pending</span>
                                            @else
                                                <span class="badge bg-secondary text-white rounded-pill px-3 py-1 fs-8">{{ $task->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center py-5 dynamic-muted">
                                        <i class="bi bi-shield-slash fs-2 mb-2 d-block opacity-50"></i>
                                        <span class="fs-7">No operational data streams recognized inside current scope.</span>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- CHARTS --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card dynamic-card border dynamic-border shadow-sm rounded-3">
                        <div class="card-header dynamic-card-header border-bottom-0 pt-3 px-3 pb-0">
                            <h6 class="fw-bold dynamic-text mb-0 fs-7">Volume Metrics Distribution</h6>
                        </div>
                        <div class="card-body p-3">
                            <div style="position: relative; height:200px;">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card dynamic-card border dynamic-border shadow-sm rounded-3">
                        <div class="card-header dynamic-card-header border-bottom-0 pt-3 px-3 pb-0">
                            <h6 class="fw-bold dynamic-text mb-0 fs-7">Priority Stratification Density</h6>
                        </div>
                        <div class="card-body p-3">
                            <div style="position: relative; height:200px; display: flex; justify-content: center;">
                                <canvas id="priorityChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-xl-6">
                    {{-- FOCUS QUEUE --}}
                    <div class="card dynamic-card border dynamic-border shadow-sm rounded-3 h-100">
                        <div class="card-header dynamic-card-header border-bottom dynamic-border py-3 px-3 d-flex align-items-center justify-content-between">
                            <h6 class="fw-bold dynamic-text mb-0 fs-7">Priority Focus Queue</h6>
                            <span class="badge bg-danger-subtle text-danger rounded-2 px-2 py-1 fs-8">High Signal</span>
                        </div>
                        <div class="card-body p-3 pt-2">
                            @if(isset($recentTasks) && count($recentTasks) > 0)
                                @php $focusTasks = collect($recentTasks)->where('status', '!=', 'Completed')->take(4); @endphp
                                @forelse($focusTasks as $task)
                                    <div class="p-2 border dynamic-border rounded-2 mb-2 dynamic-hover-bg">
                                        <div class="d-flex justify-content-between gap-2">
                                            <span class="fw-semibold dynamic-text fs-7 text-truncate">{{ $task->task_name }}</span>
                                            <span class="badge dynamic-badge-light dynamic-text border dynamic-border rounded-2 fs-8">{{ $task->priority ?? 'Normal' }}</span>
                                        </div>
                                        <small class="dynamic-muted">{{ $task->category ?? 'General Default' }} · {{ $task->status ?? 'Open' }}</small>
                                    </div>
                                @empty
                                    <div class="text-center py-4 dynamic-muted">
                                        <i class="bi bi-check2-circle fs-4 opacity-50 d-block mb-1"></i>
                                        <small class="fs-8">No active focus items detected.</small>
                                    </div>
                                @endforelse
                            @else
                                <div class="text-center py-4 dynamic-muted">
                                    <small class="fs-8">Focus queue waiting for task input.</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    {{-- QUICK NOTES --}}
                    <div class="card dynamic-card border dynamic-border shadow-sm rounded-3 h-100">
                        <div class="card-header dynamic-card-header border-bottom dynamic-border py-3 px-3 d-flex align-items-center justify-content-between">
                            <h6 class="fw-bold dynamic-text mb-0 fs-7">Console Scratchpad</h6>
                            <i class="bi bi-journal-text dynamic-muted"></i>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <textarea id="taskPulseScratchpad" class="form-control dynamic-card dynamic-text dynamic-border border fs-7 flex-grow-1 taskpulse-scratchpad" rows="5" placeholder="Keep quick dashboard notes here..."></textarea>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small id="scratchpadSavedState" class="dynamic-muted fs-8">Saved locally in this browser.</small>
                                <button type="button" class="btn btn-sm dynamic-badge-light border dynamic-border dynamic-text rounded-2 fw-semibold" onclick="clearTaskPulseScratchpad()">
                                    <i class="bi bi-trash3 me-1"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- SYSTEM DIRECTIVES --}}
            <div class="card dynamic-card border dynamic-border shadow-sm mb-4 rounded-3">
                <div class="card-body p-3">
                    <h6 class="fw-bold dynamic-text mb-3 fs-7">System Directives</h6>
                    <div class="d-flex flex-column gap-2">
                        <a href="/tasks/create" class="btn text-white rounded-2 py-2 fw-semibold fs-7 d-flex align-items-center justify-content-center gap-2" style="background-color: #e62222; border: none;">
                            <i class="bi bi-plus-circle"></i> Initialize Core Task
                        </a>
                        <a href="/tasks" class="btn dynamic-badge-light border dynamic-border dynamic-text rounded-2 py-2 fw-semibold fs-7 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-hdd-network"></i> Open Data Index
                        </a>
                        <button class="btn dynamic-badge-light border dynamic-border text-danger rounded-2 py-2 fw-semibold fs-7 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-file-earmark-bar-graph-fill"></i> Generate Report
                        </button>
                    </div>
                </div>
            </div>

            {{-- PRODUCTIVITY INDEX --}}
            <div class="card dynamic-card border dynamic-border shadow-sm mb-4 rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold dynamic-text mb-0 fs-7">Productivity Index Yield</h6>
                            <small class="dynamic-muted fs-8">Ratio tracking calculated parameters</small>
                        </div>
                        <h4 class="text-success fw-bold tracking-tight mb-0">
                            {{ (isset($totalTasks) && $totalTasks > 0) ? round(($completedTasks/$totalTasks)*100) : 0 }}%
                        </h4>
                    </div>
                    <div class="progress mt-3" style="height: 6px; border-radius: 100px; background-color: var(--bg-icon-btn); border: 1px solid var(--sidebar-border);">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ (isset($totalTasks) && $totalTasks > 0) ? round(($completedTasks/$totalTasks)*100) : 0 }}%"
                             aria-valuenow="{{ (isset($totalTasks) && $totalTasks > 0) ? round(($completedTasks/$totalTasks)*100) : 0 }}"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>

            {{-- UPCOMING REMINDERS --}}
            <div class="card dynamic-card border dynamic-border shadow-sm mb-4 rounded-3">
                <div class="card-header dynamic-card-header border-bottom dynamic-border py-3 px-3">
                    <h6 class="fw-bold dynamic-text mb-0 fs-7">Imminent Task Thresholds</h6>
                </div>
                <div class="card-body p-3 pt-2">
                    @if($dashboardReminders->count() > 0)
                        @foreach($dashboardReminders as $task)
                            <div class="p-2 border dynamic-border rounded-2 mb-2 dynamic-hover-bg d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-danger-subtle text-danger rounded-2 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; flex-shrink: 0;">
                                        <i class="bi bi-alarm-fill fs-6"></i>
                                    </div>
                                    <div class="text-truncate" style="max-width: 220px;">
                                        <span class="fw-semibold dynamic-text d-block fs-7 text-truncate">{{ $task->task_name ?? 'Scheduled Task' }}</span>
                                        <small class="dynamic-muted font-monospace d-block" style="font-size: 11px;">
                                            {{ date('M d, Y - h:i A', strtotime($task->reminder_datetime ?? $task->notification_time ?? $task->created_at ?? now())) }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 dynamic-muted">
                            <i class="bi bi-calendar-check fs-4 opacity-50 d-block mb-1"></i>
                            <small class="fs-8">No scheduled thresholds pending deployment.</small>
                        </div>
                    @endif
                </div>
            </div>

            {{-- EVENT STREAM --}}
            <div class="card dynamic-card border dynamic-border shadow-sm rounded-3">
                <div class="card-header dynamic-card-header border-bottom dynamic-border py-3 px-3">
                    <h6 class="fw-bold dynamic-text mb-0 fs-7">Real-Time Event Stream</h6>
                </div>
                <div class="card-body p-3 pt-2">
                    <div class="position-relative ps-1">
                        @if($dashboardEvents->count() > 0)
                            @foreach($dashboardEvents as $notification)
                                <div class="mb-3 border-start border-2 ps-3 position-relative pb-1" style="border-color: #e62222 !important;">
                                    <div class="position-absolute rounded-circle" style="width: 8px; height: 8px; left: -5px; top: 6px; background-color: #e62222;"></div>
                                    <span class="fw-semibold dynamic-text d-block fs-7">{{ $notification->title ?? $notification->task_name ?? 'Telemetry Notification Fired' }}</span>
                                    <small class="dynamic-muted font-monospace" style="font-size: 11px;">
                                        {{ date('M d, Y - h:i A', strtotime($notification->notification_time ?? $notification->reminder_datetime ?? $notification->created_at ?? now())) }}
                                    </small>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3 dynamic-muted">
                                <small class="fs-8">Event logging pool idle.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* =============================================
   ROOT VARIABLES â€” LIGHT MODE
   ============================================= */
:root {
    --text-main:          #0f172a;
    --text-muted:         #64748b;
    --bg-card:            #ffffff;
    --bg-topbar:          #f8fafc;
    --bg-icon-btn:        #f1f5f9;
    --sidebar-border:     #e2e8f0;
    --sidebar-bg-hover:   #f1f5f9;
    --sidebar-text-hover: #0f172a;
}

/* =============================================
   DARK MODE OVERRIDES
   ============================================= */
[data-bs-theme="dark"],
.dark-mode {
    --text-main:          #f1f5f9;
    --text-muted:         #94a3b8;
    --bg-card:            #1e293b;
    --bg-topbar:          #0f172a;
    --bg-icon-btn:        #1e293b;
    --sidebar-border:     #334155;
    --sidebar-bg-hover:   #1e293b;
    --sidebar-text-hover: #f1f5f9;
}

/* =============================================
   ADAPTIVE UTILITIES
   ============================================= */
.dynamic-text          { color: var(--text-main) !important; }
.dynamic-muted         { color: var(--text-muted) !important; }
.dynamic-border        { border-color: var(--sidebar-border) !important; }
.dynamic-card          { background-color: var(--bg-card) !important; }
.dynamic-card-header   { background-color: var(--bg-topbar) !important; }
.dynamic-dropdown      { background-color: var(--bg-card) !important; }
.dynamic-hover-bg:hover{ background-color: var(--sidebar-bg-hover) !important; }
.dynamic-badge-light   { background-color: var(--bg-icon-btn) !important; }
.dynamic-btn-white     { background-color: var(--bg-topbar) !important; color: var(--text-main) !important; }

/* =============================================
   HERO DARK CARD â€” Always dark, text always white
   ============================================= */
.hero-dark-card {
    background: linear-gradient(135deg, #111c3a 0%, #080d1a 100%) !important;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.08);
    overflow: hidden;
}
.hero-badge {
    color: #ff4d4d;
    background-color: rgba(230, 34, 34, 0.15);
    border: 1px solid rgba(230, 34, 34, 0.3);
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}
.hero-stat-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(255,255,255,0.08);
    color: rgba(255,255,255,0.75);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 6px;
    padding: 6px 14px;
    font-size: 13px;
    font-weight: 500;
}

/* =============================================
   SUBTITLE BRAND COLOR
   ============================================= */
.dynamic-subtitle {
    color: #e62222 !important;
    transition: color 0.25s ease-in-out;
}
[data-bs-theme="dark"] .dynamic-subtitle,
.dark-mode .dynamic-subtitle {
    color: #ff4d4d !important;
}

/* =============================================
   DROPDOWN
   ============================================= */
.dynamic-dropdown h6,
.dynamic-dropdown .fw-bold,
.dynamic-dropdown .fw-semibold,
.dynamic-dropdown .dynamic-text { color: var(--text-main) !important; }
.dynamic-dropdown small,
.dynamic-dropdown .dynamic-muted { color: var(--text-muted) !important; }
.dynamic-dropdown .dropdown-item { color: var(--text-main) !important; }
.dynamic-dropdown .dropdown-item:hover {
    background-color: var(--sidebar-bg-hover) !important;
    color: var(--sidebar-text-hover) !important;
}

/* =============================================
   TABLE
   ============================================= */
.dynamic-table { color: var(--text-main) !important; }
.dynamic-table-head th {
    background-color: var(--bg-icon-btn) !important;
    color: var(--text-muted) !important;
    border-bottom-color: var(--sidebar-border) !important;
}
.dynamic-tr td {
    border-bottom-color: var(--sidebar-border) !important;
    background-color: transparent !important;
    color: var(--text-main) !important;
}
.dynamic-tr:hover td {
    background-color: var(--sidebar-bg-hover) !important;
    color: var(--sidebar-text-hover) !important;
}

/* =============================================
   MODAL
   ============================================= */
.modal-content.dynamic-dropdown h5,
.modal-content.dynamic-dropdown h6,
.modal-content.dynamic-dropdown p { color: var(--text-main) !important; }
.modal-content.dynamic-dropdown .dynamic-muted { color: var(--text-muted) !important; }

/* btn-close visibility fix */
.dynamic-btn-close { filter: none; }
[data-bs-theme="dark"] .dynamic-btn-close,
.dark-mode .dynamic-btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
}

/* =============================================
   PULSE ANIMATION
   ============================================= */
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50%       { transform: scale(1.08); }
}
.animate-pulse { animation: pulse 3s infinite ease-in-out; }

/* =============================================
   ADDED DASHBOARD FEATURES
   ============================================= */
.dashboard-feature-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.dashboard-feature-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.10) !important;
}
#taskPulseSearch::placeholder,
#taskPulseScratchpad::placeholder {
    color: var(--text-muted);
    opacity: 0.75;
}
.taskpulse-row-hidden {
    display: none !important;
}
.taskpulse-scratchpad {
    min-height: 158px;
    resize: vertical;
}
.row.g-4 > [class*="col-"] > .card:last-child {
    margin-bottom: 0;
}
@media print {
    .dropdown, .btn, #taskPulseSearch, #taskPulseScratchpad { display: none !important; }
    .card, .hero-dark-card { box-shadow: none !important; break-inside: avoid; }
}
</style>

<script>
let statusChart = null;
let priorityChart = null;

function updateTaskPulseClock() {
    const clock = document.getElementById('taskpulseClock');
    if (!clock) return;
    clock.innerText = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function bindTaskPulseSearch() {
    const searchInput = document.getElementById('taskPulseSearch');
    if (!searchInput) return;

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        document.querySelectorAll('.dynamic-table tbody tr.dynamic-tr').forEach(row => {
            row.classList.toggle('taskpulse-row-hidden', query && !row.innerText.toLowerCase().includes(query));
        });
    });
}

function clearTaskPulseSearch() {
    const searchInput = document.getElementById('taskPulseSearch');
    if (!searchInput) return;
    searchInput.value = '';
    searchInput.dispatchEvent(new Event('input'));
}

function printTaskPulseDashboard() {
    window.print();
}

function bindTaskPulseScratchpad() {
    const pad = document.getElementById('taskPulseScratchpad');
    const state = document.getElementById('scratchpadSavedState');
    if (!pad) return;

    pad.value = localStorage.getItem('taskpulse_dashboard_scratchpad') || '';
    pad.addEventListener('input', function () {
        localStorage.setItem('taskpulse_dashboard_scratchpad', this.value);
        if (state) state.innerText = 'Saved ' + new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    });
}

function clearTaskPulseScratchpad() {
    const pad = document.getElementById('taskPulseScratchpad');
    const state = document.getElementById('scratchpadSavedState');
    if (!pad) return;
    pad.value = '';
    localStorage.removeItem('taskpulse_dashboard_scratchpad');
    if (state) state.innerText = 'Scratchpad cleared.';
}
function buildTaskEngineCharts() {
    if (statusChart)   statusChart.destroy();
    if (priorityChart) priorityChart.destroy();

    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark'
                || document.body.classList.contains('dark-mode')
                || document.documentElement.classList.contains('dark-mode');

    const textColor = isDark ? '#f1f5f9' : '#0f172a';
    const gridColor = isDark ? '#334155' : '#e2e8f0';
    const cardBg    = isDark ? '#1e293b' : '#ffffff';

    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        statusChart = new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: ['Completed', 'Pending', 'Overdue'],
                datasets: [{
                    label: 'Tasks',
                    data: [{{ $completedTasks ?? 0 }}, {{ $pendingTasks ?? 0 }}, {{ $overdueTasks ?? 0 }}],
                    backgroundColor: ['#10b981', '#e62222', '#ef4444'],
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: textColor, font: { size: 11 } } },
                    y: { border: { dash: [4,4] }, grid: { color: gridColor }, ticks: { color: textColor, font: { size: 11 } } }
                }
            }
        });
    }

    const priorityCtx = document.getElementById('priorityChart');
    if (priorityCtx) {
        priorityChart = new Chart(priorityCtx, {
            type: 'doughnut',
            data: {
                labels: ['High', 'Medium', 'Low'],
                datasets: [{
                    data: [{{ $highTasks ?? 0 }}, {{ $mediumTasks ?? 0 }}, {{ $lowTasks ?? 0 }}],
                    backgroundColor: ['#ef4444', '#f59e0b', '#10b981'],
                    borderWidth: 3,
                    borderColor: cardBg
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, padding: 12, color: textColor, font: { size: 11 } }
                    }
                },
                cutout: '78%'
            }
        });
    }
}

document.addEventListener("DOMContentLoaded", function () {
    buildTaskEngineCharts();
    updateTaskPulseClock();
    setInterval(updateTaskPulseClock, 30000);
    bindTaskPulseSearch();
    bindTaskPulseScratchpad();

    /* Watch theme toggle button */
    const themeBtn = document.getElementById('settingsThemeBtn');
    if (themeBtn) {
        themeBtn.addEventListener('click', () => setTimeout(buildTaskEngineCharts, 80));
    }

    /* Watch for Bootstrap data-bs-theme change AND custom dark-mode class */
    const observer = new MutationObserver(() => setTimeout(buildTaskEngineCharts, 80));
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-bs-theme', 'class'] });
    observer.observe(document.body,            { attributes: true, attributeFilter: ['class'] });

});
</script>
@endsection