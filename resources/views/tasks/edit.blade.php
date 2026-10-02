@extends('layouts.app')

@section('content')
<style>
/* =============================================
   ROOT VARIABLES — LIGHT MODE
   ============================================= */
:root {
    --text-main:      #0f172a;
    --text-muted:     #64748b;
    --bg-card:        #ffffff;
    --bg-icon-btn:    #f1f5f9;
    --sidebar-border: #e2e8f0;
}
[data-bs-theme="dark"],
.dark-mode {
    --text-main:      #f1f5f9;
    --text-muted:     #94a3b8;
    --bg-card:        #1e293b;
    --bg-icon-btn:    #1e293b;
    --sidebar-border: #334155;
}

/* =============================================
   ADAPTIVE UTILITIES
   ============================================= */
.dynamic-text  { color: var(--text-main) !important; }
.dynamic-muted { color: var(--text-muted) !important; }

/* =============================================
   FORM CARD
   ============================================= */
.form-card {
    background-color: var(--bg-card) !important;
    border: 1px solid var(--sidebar-border) !important;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
}

/* =============================================
   FORM ELEMENTS
   ============================================= */
.form-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted) !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}
.form-control,
.form-select {
    background-color: var(--bg-icon-btn) !important;
    color: var(--text-main) !important;
    border: 1px solid var(--sidebar-border) !important;
    border-radius: 10px;
    padding: 11px 16px;
    font-size: 15px;
    transition: all 0.2s ease;
}
.form-control::placeholder { color: var(--text-muted) !important; opacity: 0.7; }
.form-control:focus,
.form-select:focus {
    background-color: var(--bg-card) !important;
    color: var(--text-main) !important;
    border-color: #ef1c25 !important;
    box-shadow: 0 0 0 4px rgba(239,28,37,0.1) !important;
}

/* =============================================
   FOOTER DIVIDER
   ============================================= */
.footer-divider {
    border-top: 1px solid var(--sidebar-border) !important;
}

/* =============================================
   BUTTONS
   ============================================= */
.btn-update {
    background: #ef1c25 !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: 12px 26px;
    border-radius: 10px;
    border: none !important;
    box-shadow: 0 4px 12px rgba(239,28,37,0.2);
    transition: all 0.2s ease;
}
.btn-update:hover {
    background: #c41818 !important;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(239,28,37,0.3);
}
.btn-cancel {
    background-color: var(--bg-icon-btn) !important;
    color: var(--text-muted) !important;
    font-weight: 500;
    padding: 12px 22px;
    border-radius: 10px;
    border: 1px solid var(--sidebar-border) !important;
    transition: all 0.2s ease;
}
.btn-cancel:hover {
    color: var(--text-main) !important;
}

/* =============================================
   DATETIME PICKER ICON
   ============================================= */
input[type="datetime-local"]::-webkit-calendar-picker-indicator { filter: invert(0); }
[data-bs-theme="dark"] input[type="datetime-local"]::-webkit-calendar-picker-indicator,
.dark-mode input[type="datetime-local"]::-webkit-calendar-picker-indicator { filter: invert(1); }
</style>

<div class="container-fluid px-0">

    {{-- PAGE HEADER --}}
    <div class="mb-4">
        <h2 class="fw-bold dynamic-text mb-1" style="letter-spacing: -0.5px; font-size: 26px;">Edit Task</h2>
        <p class="dynamic-muted mb-0" style="font-size: 14px;">Modify active configuration parameters, operational deadlines, and execution track states.</p>
    </div>

    {{-- FORM CARD --}}
    <div class="form-card">
        <div class="card-body p-4 p-md-5">
           <form method="POST" action="/tasks/update/{{ $task->id }}">
    @csrf

    <div class="mb-4">
        <label class="form-label">Task Title</label>
        <input type="text"
               name="task_name"
               class="form-control"
               value="{{ old('task_name', $task->task_name) }}"
               required>
    </div>

    <div class="mb-4">
        <label class="form-label">Description</label>
        <textarea name="description"
                  class="form-control"
                  rows="4">{{ old('description', $task->description) }}</textarea>
    </div>

    <div class="mb-4">
        <label class="form-label">Category</label>
        <select name="category" class="form-select" required>
            <option value="" disabled {{ old('category', $task->category) == '' ? 'selected' : '' }}>Select Department</option>
            <option value="Production"  {{ old('category', $task->category) == 'Production'  ? 'selected' : '' }}>Production</option>
            <option value="Logistics"   {{ old('category', $task->category) == 'Logistics'   ? 'selected' : '' }}>Logistics</option>
            <option value="Quality"     {{ old('category', $task->category) == 'Quality'     ? 'selected' : '' }}>Quality</option>
            <option value="Maintenance" {{ old('category', $task->category) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
            <option value="Safety"      {{ old('category', $task->category) == 'Safety'      ? 'selected' : '' }}>Safety</option>
            <option value="Admin"       {{ old('category', $task->category) == 'Admin'       ? 'selected' : '' }}>Admin</option>
        </select>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <label class="form-label">Priority</label>
            <select name="priority" class="form-select" required>
                <option value="" disabled {{ old('priority', $task->priority) == '' ? 'selected' : '' }}>Assign Priority</option>
                <option value="High"   {{ old('priority', $task->priority) == 'High'   ? 'selected' : '' }}>High</option>
                <option value="Medium" {{ old('priority', $task->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                <option value="Low"    {{ old('priority', $task->priority) == 'Low'    ? 'selected' : '' }}>Low</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Reminder Date & Time</label>
            <input type="datetime-local"
                   name="reminder_datetime"
                   class="form-control"
                   value="{{ old('reminder_datetime', $task->reminder_datetime ? date('Y-m-d\TH:i', strtotime($task->reminder_datetime)) : '') }}"
                   required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Pending"     {{ old('status', $task->status) == 'Pending'     ? 'selected' : '' }}>Pending Review</option>
                <option value="In Progress" {{ old('status', $task->status) == 'In Progress' ? 'selected' : '' }}>Active (In Progress)</option>
                <option value="Completed"   {{ old('status', $task->status) == 'Completed'   ? 'selected' : '' }}>Pre-Completed Log</option>
                <option value="Cancelled"   {{ old('status', $task->status) == 'Cancelled'   ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

    </div>

    <div class="d-flex align-items-center gap-3 pt-4 footer-divider">
        <button type="submit" class="btn btn-update">
            <i class="bi bi-check2-all me-1"></i>
            Update Task
        </button>

        <a href="/tasks" class="btn btn-cancel">
            Cancel
        </a>
    </div>

</form>
        </div>
    </div>

</div>
@endsection
