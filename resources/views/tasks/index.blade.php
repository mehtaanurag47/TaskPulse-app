@extends('layouts.app')

@section('content')
<style>
/* =============================================
   ROOT VARIABLES — LIGHT MODE
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

[data-bs-theme="dark"], .dark-mode {
    --text-main:          #f1f5f9;
    --text-muted:         #94a3b8;
    --bg-card:            #1e293b;
    --bg-topbar:          #0f172a;
    --bg-icon-btn:        #1e293b;
    --sidebar-border:     #334155;
    --sidebar-bg-hover:   #1e293b;
    --sidebar-text-hover: #f1f5f9;
}

.dynamic-text   { color: var(--text-main) !important; }
.dynamic-muted  { color: var(--text-muted) !important; }
.dynamic-card   { background-color: var(--bg-card) !important; }
.dynamic-border { border-color: var(--sidebar-border) !important; }

/* =============================================
   STAT CARDS
   ============================================= */
.stat-card-accent {
    background-color: var(--bg-card) !important;
    border: 1px solid var(--sidebar-border) !important;
    border-radius: 16px !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04) !important;
}

/* =============================================
   SEARCH BAR
   ============================================= */
.task-search-wrap {
    background-color: var(--bg-card);
    border: 1px solid var(--sidebar-border);
    border-radius: 12px;
    padding: 0 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
    max-width: 420px;
}
.task-search-wrap input {
    border: none;
    outline: none;
    background: transparent;
    color: var(--text-main);
    font-size: 14px;
    width: 100%;
    padding: 10px 0;
}
.task-search-wrap input::placeholder { color: var(--text-muted); }
.task-search-wrap i { color: var(--text-muted); font-size: 15px; }

/* =============================================
   FILTER TABS
   ============================================= */
.filter-tabs-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.filter-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    border-radius: 999px;
    border: 1px solid var(--sidebar-border);
    background-color: var(--bg-card);
    color: var(--text-muted);
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
    line-height: 1;
}
.filter-tab-btn:hover {
    background-color: var(--sidebar-bg-hover);
    color: var(--text-main);
}
.filter-tab-btn .tab-count {
    background-color: var(--bg-icon-btn);
    color: var(--text-muted);
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    padding: 1px 7px;
    min-width: 22px;
    text-align: center;
    transition: all 0.15s ease;
}

/* Active states */
.filter-tab-btn.active-all {
    background-color: #e62222;
    color: #fff;
    border-color: #e62222;
}
.filter-tab-btn.active-all .tab-count {
    background-color: rgba(255,255,255,0.25);
    color: #fff;
}
.filter-tab-btn.active-pending {
    background-color: #3b82f6;
    color: #fff;
    border-color: #3b82f6;
}
.filter-tab-btn.active-pending .tab-count {
    background-color: rgba(255,255,255,0.25);
    color: #fff;
}
.filter-tab-btn.active-inprogress {
    background-color: #f59e0b;
    color: #fff;
    border-color: #f59e0b;
}
.filter-tab-btn.active-inprogress .tab-count {
    background-color: rgba(255,255,255,0.25);
    color: #fff;
}
.filter-tab-btn.active-completed {
    background-color: #22c55e;
    color: #fff;
    border-color: #22c55e;
}
.filter-tab-btn.active-completed .tab-count {
    background-color: rgba(255,255,255,0.25);
    color: #fff;
}
.filter-tab-btn.active-overdue {
    background-color: #ef4444;
    color: #fff;
    border-color: #ef4444;
}
.filter-tab-btn.active-overdue .tab-count {
    background-color: rgba(255,255,255,0.25);
    color: #fff;
}

/* =============================================
   TASK CARD ROWS
   ============================================= */
.task-list-wrap {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.task-card-row {
    background-color: var(--bg-card);
    border: 1px solid var(--sidebar-border);
    border-radius: 14px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: box-shadow 0.15s ease, border-color 0.15s ease;
    position: relative;
}
.task-card-row:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.07);
    border-color: #cbd5e1;
}
.task-card-row.row-completed-state {
    border-left: 4px solid #22c55e;
}
[data-bs-theme="dark"] .task-card-row.row-completed-state,
.dark-mode .task-card-row.row-completed-state {
    background-color: rgba(34, 197, 94, 0.05);
}

/* Checkbox */
.task-check-circle {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid var(--sidebar-border);
    flex-shrink: 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
    background: transparent;
}
.task-check-circle.checked {
    background-color: #22c55e;
    border-color: #22c55e;
}
.task-check-circle.checked::after {
    content: '✓';
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    line-height: 1;
}
/* Hidden real checkbox */
.task-check-input { display: none; }

/* Content */
.task-card-body { flex: 1; min-width: 0; }
.task-card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.task-card-title.text-completed-strike {
    text-decoration: line-through;
    opacity: 0.55;
}
.task-card-desc {
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 7px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 560px;
}
.task-card-meta {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}
.task-meta-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12.5px;
    color: var(--text-muted);
    font-weight: 500;
}
.task-meta-chip i { font-size: 13px; }

/* Badges */
.priority-badge {
    font-size: 11.5px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    flex-shrink: 0;
}
.status-badge {
    font-size: 12px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    flex-shrink: 0;
}
.status-dot { font-size: 7px; vertical-align: middle; }

/* Category chip */
.category-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    background-color: var(--bg-icon-btn);
    border: 1px solid var(--sidebar-border);
    border-radius: 6px;
    padding: 3px 10px;
    flex-shrink: 0;
}

/* Action buttons — always visible */
.task-action-btns {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}
.task-btn-edit,
.task-btn-delete {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    border: 1px solid var(--sidebar-border);
    background-color: var(--bg-icon-btn);
    font-size: 15px;
    cursor: pointer;
    transition: all 0.15s ease;
    flex-shrink: 0;
    text-decoration: none;
}
.task-btn-edit {
    color: var(--text-main);
}
.task-btn-edit:hover {
    background-color: #e0f2fe;
    border-color: #7dd3fc;
    color: #0369a1;
}
.task-btn-delete {
    color: #ef4444;
    border-color: rgba(239,68,68,0.3);
}
.task-btn-delete:hover {
    background-color: rgba(239,68,68,0.1);
    border-color: #ef4444;
    color: #dc2626;
}

/* =============================================
   EMPTY STATE
   ============================================= */
.task-empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-muted);
}
.task-empty-state i { font-size: 48px; opacity: 0.2; display: block; margin-bottom: 12px; }

/* =============================================
   BUTTONS
   ============================================= */
.btn-theme-dark {
    background-color: #e62222 !important;
    color: #ffffff !important;
    border: none !important;
    transition: all 0.2s ease;
}
.btn-theme-dark:hover { background-color: #c91c1c !important; color: #fff !important; }

/* =============================================
   MODAL
   ============================================= */
.modal-content {
    background-color: var(--bg-card) !important;
    border: 1px solid var(--sidebar-border) !important;
    color: var(--text-main) !important;
}
.modal-body .form-label    { color: var(--text-main) !important; }
.modal-body .form-control,
.modal-body .form-select {
    background-color: var(--bg-icon-btn) !important;
    color: var(--text-main) !important;
    border-color: var(--sidebar-border) !important;
}
.modal-body .form-control::placeholder { color: var(--text-muted) !important; }
.modal-body .form-control:focus,
.modal-body .form-select:focus {
    background-color: var(--bg-card) !important;
    color: var(--text-main) !important;
    border-color: #e62222 !important;
    box-shadow: 0 0 0 3px rgba(230,34,34,0.1) !important;
}
.modal-footer {
    background-color: var(--bg-topbar) !important;
    border-top: 1px solid var(--sidebar-border) !important;
}
.modal-footer .btn-outline-secondary {
    color: var(--text-main) !important;
    border-color: var(--sidebar-border) !important;
}
.modal-footer .btn-outline-secondary:hover {
    background-color: var(--sidebar-bg-hover) !important;
}
.star-accent-btn {
    background-color: #e62222 !important;
    color: white !important;
    border: none !important;
}
.star-accent-btn:hover { background-color: #c91c1c !important; }
</style>

<div class="container-fluid px-0" id="task-module-container">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-sm-row flex-column justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold dynamic-text mb-1" style="font-weight: 800; letter-spacing: -0.02em;">Task Management</h2>
            <p class="dynamic-muted mb-0" style="font-size:13.5px;">
                Star Cement TaskPulse — Monitor operational timelines, execution filters, and parameter states.
            </p>
        </div>
       
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4" id="kpi-metrics-row">
        <div class="col">
            <div class="card stat-card-accent rounded-3 p-3 h-100">
                <span class="text-uppercase fw-bold dynamic-muted d-block mb-1" style="font-size:11px;">Total Tasks</span>
                <h3 class="fw-bold dynamic-text mb-0 count-total">{{ $tasks->count() }}</h3>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card-accent rounded-3 p-3 h-100">
                <span class="text-uppercase fw-bold dynamic-muted d-block mb-1" style="font-size:11px;">Pending</span>
                <h3 class="fw-bold text-primary mb-0 count-pending">{{ $tasks->where('status','Pending')->count() }}</h3>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card-accent rounded-3 p-3 h-100">
                <span class="text-uppercase fw-bold dynamic-muted d-block mb-1" style="font-size:11px;">In Progress</span>
                <h3 class="fw-bold text-info mb-0 count-inprogress">{{ $tasks->where('status','In Progress')->count() }}</h3>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card-accent rounded-3 p-3 h-100">
                <span class="text-uppercase fw-bold dynamic-muted d-block mb-1" style="font-size:11px;">Completed</span>
                <h3 class="fw-bold text-success mb-0 count-completed">{{ $tasks->where('status','Completed')->count() }}</h3>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card-accent rounded-3 p-3 h-100">
                <span class="text-uppercase fw-bold dynamic-muted d-block mb-1" style="font-size:11px;">Overdue</span>
                <h3 class="fw-bold text-danger mb-0 count-overdue">{{ $tasks->where('status','Overdue')->count() }}</h3>
            </div>
        </div>
    </div>

    {{-- SEARCH + FILTER TABS --}}
    <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
        {{-- Search --}}
        <div class="task-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text"
                   id="taskSearchInput"
                   placeholder="Search tasks or assignees..."
                   onkeyup="debouncedSearch()">
        </div>

        {{-- Filter Tabs --}}
        <div class="filter-tabs-row" id="filterTabsRow">
            <button class="filter-tab-btn active-all" data-status="" onclick="setActiveTab(this, 'active-all')">
                All <span class="tab-count" id="tab-count-all">{{ $tasks->count() }}</span>
            </button>
            <button class="filter-tab-btn" data-status="Pending" onclick="setActiveTab(this, 'active-pending')">
                Pending <span class="tab-count" id="tab-count-pending">{{ $tasks->where('status','Pending')->count() }}</span>
            </button>
            <button class="filter-tab-btn" data-status="In Progress" onclick="setActiveTab(this, 'active-inprogress')">
                In Progress <span class="tab-count" id="tab-count-inprogress">{{ $tasks->where('status','In Progress')->count() }}</span>
            </button>
            <button class="filter-tab-btn" data-status="Completed" onclick="setActiveTab(this, 'active-completed')">
                Completed <span class="tab-count" id="tab-count-completed">{{ $tasks->where('status','Completed')->count() }}</span>
            </button>
            <button class="filter-tab-btn" data-status="Overdue" onclick="setActiveTab(this, 'active-overdue')">
                Overdue <span class="tab-count" id="tab-count-overdue">{{ $tasks->where('status','Overdue')->count() }}</span>
            </button>
        </div>
    </div>

    {{-- TASK CARD LIST --}}
    <div class="task-list-wrap" id="taskListWrap">
        @forelse($tasks as $task)
            <div class="task-card-row {{ $task->status == 'Completed' ? 'row-completed-state' : '' }}"
                 id="task-row-{{ $task->id }}"
                 data-status="{{ $task->status }}"
                 data-name="{{ strtolower($task->task_name) }}"
                 data-category="{{ strtolower($task->category ?? '') }}"
                 data-assignee="">
                {{-- Checkbox circle --}}
                <div class="task-check-circle {{ $task->status == 'Completed' ? 'checked' : '' }}"
                     onclick="toggleTaskCheckbox(this, {{ $task->id }})"
                     title="Mark complete">
                </div>
                <input type="hidden" class="task-check-input" data-id="{{ $task->id }}"
                       value="{{ $task->status == 'Completed' ? '1' : '0' }}">

                {{-- Body --}}
                <div class="task-card-body">
                    <div class="task-card-title {{ $task->status == 'Completed' ? 'text-completed-strike' : '' }}">
                        {{ $task->task_name }}
                    </div>
                    <div class="task-card-desc">
                        {{ $task->description ?? 'No description provided.' }}
                    </div>
                    <div class="task-card-meta">
                        {{-- Priority --}}
                        @if($task->priority == 'Critical')
                            <span class="priority-badge bg-danger text-white">Critical</span>
                        @elseif($task->priority == 'High')
                            <span class="priority-badge text-white" style="background:#f97316;">High</span>
                        @elseif($task->priority == 'Medium')
                            <span class="priority-badge bg-warning-subtle text-warning-emphasis">Medium</span>
                        @else
                            <span class="priority-badge bg-success-subtle text-success">Low</span>
                        @endif

                        {{-- Status --}}
                        @if($task->status == 'Completed')
                            <span class="status-badge bg-success-subtle text-success">
                                <i class="bi bi-circle-fill status-dot"></i> Completed
                            </span>
                        @elseif($task->status == 'In Progress')
                            <span class="status-badge bg-warning-subtle text-warning-emphasis">
                                <i class="bi bi-circle-fill status-dot"></i> In Progress
                            </span>
                        @elseif($task->status == 'Overdue')
                            <span class="status-badge bg-danger text-white">
                                <i class="bi bi-circle-fill status-dot"></i> Overdue
                            </span>
                        @else
                            <span class="status-badge bg-primary-subtle text-primary">
                                <i class="bi bi-circle-fill status-dot"></i> Pending
                            </span>
                        @endif

                        {{-- Category --}}
                        @if($task->category)
                        <span class="category-chip">
                            @if($task->category == 'Maintenance') <i class="bi bi-wrench"></i>
                            @elseif($task->category == 'Quality') <i class="bi bi-bezier2"></i>
                            @elseif($task->category == 'Logistics') <i class="bi bi-truck"></i>
                            @elseif($task->category == 'Safety') <i class="bi bi-shield-check"></i>
                            @elseif($task->category == 'Admin') <i class="bi bi-file-earmark-text"></i>
                            @else <i class="bi bi-building"></i>
                            @endif
                            {{ $task->category }}
                        </span>
                        @endif

                        {{-- Reminder time --}}
                        <span class="task-meta-chip">
                            <i class="bi bi-clock"></i>
                            {{ date('M d, Y • h:i A', strtotime($task->reminder_datetime)) }}
                        </span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="task-action-btns">
                    <a href="/tasks/edit/{{ $task->id }}"
                       class="task-btn-edit"
                       title="Edit Task">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <button class="task-btn-delete"
                            title="Delete Task"
                            onclick="executeSoftDelete({{ $task->id }})">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="task-empty-state" id="taskEmptyState">
                <i class="bi bi-folder-x"></i>
                <span class="fw-medium">No tasks found.</span>
            </div>
        @endforelse

        {{-- JS-driven empty state (hidden by default) --}}
        <div class="task-empty-state d-none" id="taskEmptyStateJs">
            <i class="bi bi-folder-x"></i>
            <span class="fw-medium">No records found matching your filter.</span>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="taskControlModal" tabindex="-1" aria-hidden="true" style="backdrop-filter: blur(4px);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header text-white" style="background-color: #e62222;">
                <h5 class="modal-title fw-bold" id="modalTemplateHeader">Create Task</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="taskSubmissionForm" onsubmit="handleFormSubmission(event)">
                <input type="hidden" id="formTaskId" name="id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Task Name</label>
                        <input type="text" id="formTaskName" name="task_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea id="formDescription" name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category</label>
                        <input type="text" id="formCategory" name="category" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Priority</label>
                            <select id="formPriority" name="priority" class="form-select" required>
                                <option value="Critical">Critical</option>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select id="formStatus" name="status" class="form-select" required>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reminder Date & Time</label>
                        <input type="datetime-local" id="formReminderDatetime" name="reminder_datetime" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn star-accent-btn" id="formSubmitActionBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let searchDebounceTimeout;
    let taskModalInstance;
    let activeStatus = '';   // '' = All

    document.addEventListener("DOMContentLoaded", function () {
        taskModalInstance = new bootstrap.Modal(document.getElementById('taskControlModal'));
        // Make edit buttons visible on hover via CSS (handled in CSS already)
    });

    /* ── Tab switching ───────────────────────────────── */
    function setActiveTab(btn, activeClass) {
        // Remove all active classes
        document.querySelectorAll('.filter-tab-btn').forEach(b => {
            b.className = 'filter-tab-btn';
        });
        btn.classList.add(activeClass);
        activeStatus = btn.dataset.status;
        applyClientFilter();
    }

    /* ── Debounced search ────────────────────────────── */
    function debouncedSearch() {
        clearTimeout(searchDebounceTimeout);
        searchDebounceTimeout = setTimeout(() => applyClientFilter(), 250);
    }

    /* ── Client-side filter (instant, no API round-trip) */
    function applyClientFilter() {
        const search  = document.getElementById('taskSearchInput').value.toLowerCase().trim();
        const rows    = document.querySelectorAll('.task-card-row[data-status]');
        let visible   = 0;

        rows.forEach(row => {
            const statusMatch  = !activeStatus || row.dataset.status === activeStatus;
            const nameMatch    = !search
                || row.dataset.name.includes(search)
                || row.dataset.category.includes(search)
                || (row.dataset.assignee && row.dataset.assignee.includes(search));
            const show = statusMatch && nameMatch;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        // Toggle empty state
        const emptyJs = document.getElementById('taskEmptyStateJs');
        const emptyBlade = document.getElementById('taskEmptyState');
        if (emptyJs) emptyJs.classList.toggle('d-none', visible > 0);
        if (emptyBlade) emptyBlade.style.display = visible > 0 ? 'none' : '';
    }

    /* ── API filter (called after mutations to refresh counts) */
    function triggerApiFilter() {
        const search = document.getElementById('taskSearchInput').value;
        fetch(`/api/tasks?search=${encodeURIComponent(search)}&status=${encodeURIComponent(activeStatus)}`)
            .then(res => res.json())
            .then(data => rebuildTaskCards(data))
            .catch(err => console.error("Filter error:", err));
    }

    /* ── Toggle completion ──────────────────────────── */
    function toggleTaskCheckbox(circleEl, taskId) {
        const row        = document.getElementById(`task-row-${taskId}`);
        const isChecked  = circleEl.classList.contains('checked');
        const nowChecked = !isChecked;

        if (nowChecked) {
            circleEl.classList.add('checked');
            row.classList.add('row-completed-state');
            row.dataset.status = 'Completed';
            const title = row.querySelector('.task-card-title');
            if (title) title.classList.add('text-completed-strike');
        } else {
            circleEl.classList.remove('checked');
            row.classList.remove('row-completed-state');
            row.dataset.status = 'Pending';
            const title = row.querySelector('.task-card-title');
            if (title) title.classList.remove('text-completed-strike');
        }

        fetch(`/api/tasks/${taskId}/toggle`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ completed: nowChecked })
        }).then(res => res.json()).then(response => {
            if (response.success) { refreshStatsCounter(); }
        });
    }

    /* ── Refresh KPI counters ───────────────────────── */
    function refreshStatsCounter() {
        fetch('/api/tasks/stats')
            .then(res => res.json())
            .then(stats => {
                document.querySelector('.count-total').innerText      = stats.total;
                document.querySelector('.count-pending').innerText    = stats.pending;
                document.querySelector('.count-inprogress').innerText = stats.in_progress;
                document.querySelector('.count-completed').innerText  = stats.completed;
                document.querySelector('.count-overdue').innerText    = stats.overdue;
                // Update tab counts
                if (document.getElementById('tab-count-all'))        document.getElementById('tab-count-all').innerText        = stats.total;
                if (document.getElementById('tab-count-pending'))    document.getElementById('tab-count-pending').innerText    = stats.pending;
                if (document.getElementById('tab-count-inprogress')) document.getElementById('tab-count-inprogress').innerText = stats.in_progress;
                if (document.getElementById('tab-count-completed'))  document.getElementById('tab-count-completed').innerText  = stats.completed;
                if (document.getElementById('tab-count-overdue'))    document.getElementById('tab-count-overdue').innerText    = stats.overdue;
            }).catch(err => console.error("Stats error:", err));
    }

    /* ── Delete ────────────────────────────────────── */
    function executeSoftDelete(taskId) {
        if (!confirm('Delete this task?')) return;
        fetch(`/api/tasks/${taskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        }).then(res => res.json()).then(data => {
            if (data.success) {
                const row = document.getElementById(`task-row-${taskId}`);
                if (row) row.remove();
                refreshStatsCounter();
                applyClientFilter();
            }
        }).catch(err => console.error("Delete error:", err));
    }

    /* ── Modals ────────────────────────────────────── */
    function openCreateModal() {
        document.getElementById('taskSubmissionForm').reset();
        document.getElementById('formTaskId').value     = '';
        document.getElementById('formCategory').value  = 'Admin';
        document.getElementById('formPriority').value  = 'Medium';
        document.getElementById('formStatus').value    = 'Pending';
        document.getElementById('modalTemplateHeader').innerText  = 'Deploy New Task';
        document.getElementById('formSubmitActionBtn').innerText  = 'Create Task';
        if (taskModalInstance) taskModalInstance.show();
    }

    function openEditModal(taskObject) {
        document.getElementById('formTaskId').value         = taskObject.id;
        document.getElementById('formTaskName').value       = taskObject.task_name;
        document.getElementById('formDescription').value    = taskObject.description || '';
        document.getElementById('formCategory').value       = taskObject.category;
        document.getElementById('formPriority').value       = taskObject.priority;
        document.getElementById('formStatus').value         = taskObject.status;
        if (taskObject.reminder_datetime) {
            document.getElementById('formReminderDatetime').value = taskObject.reminder_datetime.substring(0, 16);
        }
        document.getElementById('modalTemplateHeader').innerText = 'Edit Task';
        document.getElementById('formSubmitActionBtn').innerText = 'Save Changes';
        if (taskModalInstance) taskModalInstance.show();
    }

    function handleFormSubmission(event) {
        event.preventDefault();
        const taskId   = document.getElementById('formTaskId').value;
        const formData = new FormData();
        formData.append('task_name',         document.getElementById('formTaskName').value);
        formData.append('description',       document.getElementById('formDescription').value);
        formData.append('category',          document.getElementById('formCategory').value);
        formData.append('priority',          document.getElementById('formPriority').value);
        formData.append('reminder_datetime', document.getElementById('formReminderDatetime').value);
        formData.append('status',            document.getElementById('formStatus').value);

        fetch('/tasks/update/' + taskId, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        }).then(response => response.text()).then(() => {
            alert('Task updated successfully.');
            location.reload();
        }).catch(error => console.error(error));
    }

    /* ── Rebuild cards from API response ──────────── */
    function rebuildTaskCards(tasksArray) {
        const wrap = document.getElementById('taskListWrap');
        // Remove existing dynamic cards only (keep empty state divs)
        wrap.querySelectorAll('.task-card-row').forEach(r => r.remove());

        const emptyJs = document.getElementById('taskEmptyStateJs');

        if (tasksArray.length === 0) {
            if (emptyJs) emptyJs.classList.remove('d-none');
            return;
        }
        if (emptyJs) emptyJs.classList.add('d-none');

        tasksArray.forEach(task => {
            const isCompleted  = task.status === 'Completed';
            const rowClass     = isCompleted ? 'row-completed-state' : '';
            const strikeClass  = isCompleted ? 'text-completed-strike' : '';

            let categoryIcon = `<i class="bi bi-building"></i>`;
            if      (task.category === 'Maintenance') categoryIcon = `<i class="bi bi-wrench"></i>`;
            else if (task.category === 'Quality')     categoryIcon = `<i class="bi bi-bezier2"></i>`;
            else if (task.category === 'Logistics')   categoryIcon = `<i class="bi bi-truck"></i>`;
            else if (task.category === 'Safety')      categoryIcon = `<i class="bi bi-shield-check"></i>`;
            else if (task.category === 'Admin')       categoryIcon = `<i class="bi bi-file-earmark-text"></i>`;

            let priorityBadge = `<span class="priority-badge bg-success-subtle text-success">Low</span>`;
            if      (task.priority === 'Critical') priorityBadge = `<span class="priority-badge bg-danger text-white">Critical</span>`;
            else if (task.priority === 'High')     priorityBadge = `<span class="priority-badge text-white" style="background:#f97316;">High</span>`;
            else if (task.priority === 'Medium')   priorityBadge = `<span class="priority-badge bg-warning-subtle text-warning-emphasis">Medium</span>`;

            let statusBadge = `<span class="status-badge bg-primary-subtle text-primary"><i class="bi bi-circle-fill status-dot"></i> Pending</span>`;
            if      (task.status === 'Completed')   statusBadge = `<span class="status-badge bg-success-subtle text-success"><i class="bi bi-circle-fill status-dot"></i> Completed</span>`;
            else if (task.status === 'In Progress') statusBadge = `<span class="status-badge bg-warning-subtle text-warning-emphasis"><i class="bi bi-circle-fill status-dot"></i> In Progress</span>`;
            else if (task.status === 'Overdue')     statusBadge = `<span class="status-badge bg-danger text-white"><i class="bi bi-circle-fill status-dot"></i> Overdue</span>`;

            const formattedDate = new Date(task.reminder_datetime).toLocaleString('en-US', {
                month: 'short', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
            }).replace(',', ' •');

            const taskJson = JSON.stringify(task).replace(/"/g, '&quot;');

            const div = document.createElement('div');
            div.className = `task-card-row ${rowClass}`;
            div.id = `task-row-${task.id}`;
            div.dataset.status   = task.status;
            div.dataset.name     = (task.task_name || '').toLowerCase();
            div.dataset.category = (task.category || '').toLowerCase();
            div.dataset.assignee = '';
            div.innerHTML = `
                <div class="task-check-circle ${isCompleted ? 'checked' : ''}"
                     onclick="toggleTaskCheckbox(this, ${task.id})" title="Mark complete"></div>
                <div class="task-card-body">
                    <div class="task-card-title ${strikeClass}">${task.task_name}</div>
                    <div class="task-card-desc">${task.description || 'No description provided.'}</div>
                    <div class="task-card-meta">
                        ${priorityBadge}
                        ${statusBadge}
                        ${task.category ? `<span class="category-chip">${categoryIcon} ${task.category}</span>` : ''}
                        <span class="task-meta-chip"><i class="bi bi-clock"></i>${formattedDate}</span>
                    </div>
                </div>
                <div class="task-action-btns">
                    <a href="/tasks/edit/${task.id}" class="task-btn-edit" title="Edit Task">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <button class="task-btn-delete" title="Delete Task" onclick="executeSoftDelete(${task.id})">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>`;
            wrap.insertBefore(div, emptyJs);
        });
    }
</script>
@endsection