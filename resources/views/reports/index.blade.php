@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom dynamic-border">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm"
                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #e62222 0%, #b81414 100%);">
                <i class="bi bi-bar-chart-line-fill text-warning fs-3"></i>
            </div>
            <div>
                <h3 class="fw-bold dynamic-text mb-0">Reports</h3>
                <span class="fw-semibold font-monospace dynamic-subtitle" style="font-size: 11px; letter-spacing: 0.05em;">TASK REPORT &amp; REMINDER REPORT</span>
            </div>
        </div>
        <button class="btn btn-sm btn-outline-danger" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Print Report
        </button>
    </div>

    {{-- Filters --}}
    <div class="card border dynamic-border shadow-sm mb-4" style="border-radius: 10px;">
        <div class="card-body py-3">
            <form method="GET" action="/reports" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label dynamic-muted fs-7 mb-1">From</label>
                    <input type="date" name="from" class="form-control form-control-sm"
                           value="{{ $from->format('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label dynamic-muted fs-7 mb-1">To</label>
                    <input type="date" name="to" class="form-control form-control-sm"
                           value="{{ $to->format('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label dynamic-muted fs-7 mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="Pending"     {{ request('status') == 'Pending'     ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed"   {{ request('status') == 'Completed'   ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label dynamic-muted fs-7 mb-1">Priority</label>
                    <select name="priority" class="form-select form-select-sm">
                        <option value="">All Priorities</option>
                        <option value="High"   {{ request('priority') == 'High'   ? 'selected' : '' }}>High</option>
                        <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="Low"    {{ request('priority') == 'Low'    ? 'selected' : '' }}>Low</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label dynamic-muted fs-7 mb-1">Category</label>
                    <select name="category" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-danger btn-sm flex-grow-1">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="/reports" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION 1: TASK REPORT --}}
    {{-- ============================================================ --}}
    <h5 class="fw-bold dynamic-text mb-3">
        <i class="bi bi-list-task text-danger me-1"></i> Task Report
        <small class="dynamic-muted fw-normal fs-7 ms-2">
            {{ $from->format('d M Y') }} – {{ $to->format('d M Y') }}
        </small>
    </h5>

    {{-- Task summary cards --}}
    <div class="row g-3 mb-4">
        @php
            $taskCards = [
                ['label' => 'Total Tasks',       'value' => $taskStats['total'],           'icon' => 'stack',              'color' => 'text-primary'],
                ['label' => 'Completed',         'value' => $taskStats['completed'],        'icon' => 'check-circle-fill',  'color' => 'text-success'],
                ['label' => 'In Progress',       'value' => $taskStats['in_progress'],      'icon' => 'arrow-repeat',       'color' => 'text-warning'],
                ['label' => 'Pending',           'value' => $taskStats['pending'],          'icon' => 'hourglass-split',    'color' => 'text-secondary'],
                ['label' => 'Overdue',           'value' => $taskStats['overdue'],          'icon' => 'exclamation-circle', 'color' => 'text-danger'],
                ['label' => 'Completion Rate',   'value' => $taskStats['completion_rate'].'%', 'icon' => 'trophy-fill',    'color' => 'text-success'],
            ];
        @endphp
        @foreach($taskCards as $card)
            <div class="col-md-2 col-6">
                <div class="card border dynamic-border shadow-sm text-center h-100" style="border-radius: 10px;">
                    <div class="card-body py-3">
                        <i class="bi bi-{{ $card['icon'] }} fs-4 {{ $card['color'] }}"></i>
                        <h4 class="fw-bold dynamic-text mt-1 mb-0">{{ $card['value'] }}</h4>
                        <small class="dynamic-muted">{{ $card['label'] }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Priority & Category breakdown + chart --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border dynamic-border shadow-sm h-100" style="border-radius: 10px;">
                <div class="card-header dynamic-card-header border-bottom dynamic-border py-2">
                    <span class="fw-bold dynamic-text fs-7">Priority Breakdown</span>
                </div>
                <div class="card-body">
                    @foreach(['High' => 'danger', 'Medium' => 'warning', 'Low' => 'success'] as $p => $color)
                        @php $cnt = $priorityBreakdown[$p] ?? 0; @endphp
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="dynamic-text fs-7">{{ $p }}</span>
                            <span class="badge bg-{{ $color }}">{{ $cnt }}</span>
                        </div>
                        <div class="progress mb-3" style="height: 4px;">
                            <div class="progress-bar bg-{{ $color }}"
                                 style="width: {{ $taskStats['total'] > 0 ? round(($cnt / $taskStats['total']) * 100) : 0 }}%">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border dynamic-border shadow-sm h-100" style="border-radius: 10px;">
                <div class="card-header dynamic-card-header border-bottom dynamic-border py-2">
                    <span class="fw-bold dynamic-text fs-7">Category Breakdown</span>
                </div>
                <div class="card-body p-0" style="max-height: 200px; overflow-y: auto;">
                    @forelse($categoryBreakdown as $cat => $cnt)
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom dynamic-border">
                            <span class="dynamic-text fs-7">{{ $cat }}</span>
                            <span class="badge bg-secondary">{{ $cnt }}</span>
                        </div>
                    @empty
                        <p class="dynamic-muted text-center py-3 fs-7">No data</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border dynamic-border shadow-sm h-100" style="border-radius: 10px;">
                <div class="card-header dynamic-card-header border-bottom dynamic-border py-2">
                    <span class="fw-bold dynamic-text fs-7">Daily Reminders Sent (Last 7 Days)</span>
                </div>
                <div class="card-body">
                    <canvas id="dailyReminderChart" style="max-height: 160px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Task table --}}
    <div class="card border dynamic-border shadow-sm mb-5" style="border-radius: 10px;">
        <div class="card-header dynamic-card-header border-bottom dynamic-border py-3 d-flex justify-content-between">
            <span class="fw-bold dynamic-text fs-7">Task List ({{ $tasks->count() }} tasks)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr class="dynamic-card-header">
                            <th class="dynamic-text fs-7 fw-semibold ps-3">#</th>
                            <th class="dynamic-text fs-7 fw-semibold">Task Name</th>
                            <th class="dynamic-text fs-7 fw-semibold">Category</th>
                            <th class="dynamic-text fs-7 fw-semibold">Priority</th>
                            <th class="dynamic-text fs-7 fw-semibold">Status</th>
                            <th class="dynamic-text fs-7 fw-semibold">Reminder</th>
                            <th class="dynamic-text fs-7 fw-semibold">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $i => $task)
                            <tr>
                                <td class="dynamic-muted fs-7 ps-3">{{ $i + 1 }}</td>
                                <td class="dynamic-text fs-7 fw-semibold">{{ $task->task_name }}</td>
                                <td class="dynamic-muted fs-7">{{ $task->category ?? '—' }}</td>
                                <td>
                                    @php $pColor = ['High' => 'danger', 'Medium' => 'warning', 'Low' => 'success'][$task->priority] ?? 'secondary'; @endphp
                                    <span class="badge bg-{{ $pColor }}">{{ $task->priority }}</span>
                                </td>
                                <td>
                                    @php $sColor = ['Completed' => 'success', 'In Progress' => 'warning', 'Pending' => 'secondary'][$task->status] ?? 'secondary'; @endphp
                                    <span class="badge bg-{{ $sColor }}">{{ $task->status }}</span>
                                </td>
                                <td class="dynamic-muted fs-7">
                                    {{ $task->reminder_datetime ? \Carbon\Carbon::parse($task->reminder_datetime)->format('d M Y, h:i A') : '—' }}
                                </td>
                                <td class="dynamic-muted fs-7">
                                    {{ \Carbon\Carbon::parse($task->created_at)->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center dynamic-muted py-4">No tasks found for this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION 2: REMINDER REPORT --}}
    {{-- ============================================================ --}}
    <h5 class="fw-bold dynamic-text mb-3">
        <i class="bi bi-bell-fill text-danger me-1"></i> Reminder Report
        <small class="dynamic-muted fw-normal fs-7 ms-2">
            {{ $from->format('d M Y') }} – {{ $to->format('d M Y') }}
        </small>
    </h5>

    {{-- Reminder summary cards --}}
    <div class="row g-3 mb-4">
        @php
            $reminderCards = [
                ['label' => 'Total Sent',  'value' => $reminderStats['total'],  'icon' => 'bell-fill',       'color' => 'text-primary'],
                ['label' => 'Read',        'value' => $reminderStats['read'],   'icon' => 'check2-all',      'color' => 'text-success'],
                ['label' => 'Unread',      'value' => $reminderStats['unread'], 'icon' => 'bell-slash-fill', 'color' => 'text-danger'],
            ];
        @endphp
        @foreach($reminderCards as $card)
            <div class="col-md-4">
                <div class="card border dynamic-border shadow-sm text-center h-100" style="border-radius: 10px;">
                    <div class="card-body py-3">
                        <i class="bi bi-{{ $card['icon'] }} fs-3 {{ $card['color'] }}"></i>
                        <h3 class="fw-bold dynamic-text mt-1 mb-0">{{ $card['value'] }}</h3>
                        <small class="dynamic-muted">{{ $card['label'] }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Reminder log table --}}
    <div class="card border dynamic-border shadow-sm" style="border-radius: 10px;">
        <div class="card-header dynamic-card-header border-bottom dynamic-border py-3">
            <span class="fw-bold dynamic-text fs-7">Reminder Log ({{ $reminders->count() }} entries)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr class="dynamic-card-header">
                            <th class="dynamic-text fs-7 fw-semibold ps-3">#</th>
                            <th class="dynamic-text fs-7 fw-semibold">Title</th>
                            <th class="dynamic-text fs-7 fw-semibold">Message</th>
                            <th class="dynamic-text fs-7 fw-semibold">Sent At</th>
                            <th class="dynamic-text fs-7 fw-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reminders as $i => $reminder)
                            <tr>
                                <td class="dynamic-muted fs-7 ps-3">{{ $i + 1 }}</td>
                                <td class="dynamic-text fs-7 fw-semibold">{{ $reminder->title }}</td>
                                <td class="dynamic-muted fs-7">{{ $reminder->message }}</td>
                                <td class="dynamic-muted fs-7">
                                    {{ optional($reminder->notification_time)->format('d M Y, h:i A') }}
                                </td>
                                <td>
                                    <span class="badge {{ $reminder->is_read ? 'bg-success' : 'bg-danger' }}">
                                        {{ $reminder->is_read ? 'Read' : 'Unread' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center dynamic-muted py-4">No reminders sent in this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('dailyReminderChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartDays) !!},
            datasets: [{
                label: 'Reminders Sent',
                data: {!! json_encode($chartCounts) !!},
                backgroundColor: 'rgba(185, 28, 28, 0.75)',
                borderColor: '#b91c1c',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, precision: 0 }
                }
            }
        }
    });
});
</script>
@endpush

<style>
@media print {
    .sidebar, .topbar, .btn, form { display: none !important; }
    .page-content { margin: 0 !important; padding: 0 !important; }
    .card { break-inside: avoid; }
}
</style>
@endsection