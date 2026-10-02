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
    --bg-soft:        #f8fafc;
    --bg-icon-btn:    #f1f5f9;
    --sidebar-border: #e2e8f0;
}
[data-bs-theme="dark"],
.dark-mode {
    --text-main:      #f1f5f9;
    --text-muted:     #94a3b8;
    --bg-card:        #1e293b;
    --bg-soft:        #0f172a;
    --bg-icon-btn:    #1e293b;
    --sidebar-border: #334155;
}

/* =============================================
   ADAPTIVE UTILITIES
   ============================================= */
.dynamic-text  { color: var(--text-main) !important; }
.dynamic-muted { color: var(--text-muted) !important; }

/* =============================================
   CARD
   ============================================= */
.form-card {
    background-color: var(--bg-card) !important;
    border: 1px solid var(--sidebar-border) !important;
    border-radius: 16px;
}

/* =============================================
   FORM INPUTS
   ============================================= */
.form-control,
.form-select {
    background-color: var(--bg-icon-btn) !important;
    color: var(--text-main) !important;
    border-color: var(--sidebar-border) !important;
    border-radius: 10px;
    font-size: 14.5px;
}
.form-control::placeholder { color: var(--text-muted) !important; opacity: 0.7; }
.form-control:focus,
.form-select:focus {
    background-color: var(--bg-card) !important;
    color: var(--text-main) !important;
    border-color: #e11d48 !important;
    box-shadow: 0 0 0 3px rgba(225,29,72,0.12) !important;
}
.form-label {
    color: var(--text-main) !important;
    font-size: 14px;
    font-weight: 600;
}

/* =============================================
   INFO BOX
   ============================================= */
.info-box {
    background-color: var(--bg-icon-btn) !important;
    border: 1px solid var(--sidebar-border) !important;
    border-radius: 10px;
}

/* =============================================
   DIVIDER
   ============================================= */
.footer-divider {
    border-top: 1px solid var(--sidebar-border) !important;
}

/* =============================================
   BUTTONS
   ============================================= */
.btn-submit {
    background-color: #e11d48 !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 10px;
    font-size: 14.5px;
    font-weight: 700;
    transition: background 0.2s ease;
}
.btn-submit:hover { background-color: #be123c !important; }

.btn-cancel {
    background: transparent !important;
    color: var(--text-muted) !important;
    border: 1px solid var(--sidebar-border) !important;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}
.btn-cancel:hover {
    background-color: var(--bg-icon-btn) !important;
    color: var(--text-main) !important;
}

/* =============================================
   DATETIME PICKER ICON
   ============================================= */
input[type="datetime-local"]::-webkit-calendar-picker-indicator { filter: invert(0); }
[data-bs-theme="dark"] input[type="datetime-local"]::-webkit-calendar-picker-indicator,
.dark-mode input[type="datetime-local"]::-webkit-calendar-picker-indicator { filter: invert(1); }

/* =============================================
   LIVE BADGE
   ============================================= */
.live-badge {
    background-color: var(--bg-icon-btn) !important;
    border: 1px solid var(--sidebar-border) !important;
    color: var(--text-muted) !important;
    border-radius: 999px;
    padding: 6px 14px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.03em;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
</style>

<div class="container-fluid px-0">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-sm-row flex-column justify-content-between align-items-start align-items-sm-center gap-3 mb-4 pb-2">
        <div>
            <h2 class="dynamic-text mb-1" style="font-weight: 700; letter-spacing: -0.02em;">Create a New Task</h2>
            <p class="dynamic-muted mb-0" style="font-size: 14px;">Fill out the details below to deploy and track a plant operation objective.</p>
        </div>
        <div>
            <span class="live-badge">
                <span class="spinner-grow spinner-grow-sm text-success" role="status" style="width: 6px; height: 6px; --bs-spinner-animation-speed: 1.5s;"></span>
                SYSTEM LIVE
            </span>
        </div>
    </div>

    {{-- ERROR ALERT --}}
    @if ($errors->any())
        <div class="alert border-0 mb-4 d-flex align-items-center gap-3 p-3"
             style="background-color: rgba(225,29,72,0.08); border-left: 4px solid #e11d48 !important; border-radius: 10px;">
            <i class="bi bi-exclamation-triangle-fill fs-4" style="color: #e11d48;"></i>
            <div>
                <span class="fw-bold dynamic-text d-block">Form verification failed</span>
                <small class="dynamic-muted">Please check the fields highlighted in red below to re-submit.</small>
            </div>
        </div>
    @endif

    <form method="POST" action="/tasks/store">
        @csrf
        <div class="row g-4">

            {{-- LEFT — Main Fields --}}
            <div class="col-xl-8 col-lg-7">
                <div class="form-card p-4 p-md-5 h-100">

                    <div class="mb-4">
                        <label for="task_name" class="form-label mb-2">Task Title <span class="text-danger">*</span></label>
                        <input type="text"
                               id="task_name"
                               name="task_name"
                               class="form-control py-2 px-3 @error('task_name') is-invalid @enderror"
                               placeholder="e.g., Inspect Kiln #3"
                               value="{{ old('task_name') }}"
                               required
                               autocomplete="off">
                        @error('task_name')
                            <div class="text-danger mt-2 small fw-semibold"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label mb-2">Description</label>
                        <textarea id="description"
                                  name="description"
                                  class="form-control p-3 @error('description') is-invalid @enderror"
                                  placeholder="Provide step-by-step technical context instructions here..."
                                  rows="7"
                                  style="resize: none;">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger mt-2 small fw-semibold"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="info-box p-3 d-flex gap-3 align-items-center">
                        <i class="bi bi-shield-check dynamic-muted fs-4"></i>
                        <p class="dynamic-muted mb-0" style="font-size: 12.5px; line-height: 1.5;">
                            Deploying this parameters profile automatically syncs telemetry loops with schedule nodes. Ensure verification protocols pass local safety indices.
                        </p>
                    </div>

                </div>
            </div>

            {{-- RIGHT — Settings --}}
            <div class="col-xl-4 col-lg-5">
                <div class="form-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div>

                        <div class="mb-4">
                            <label for="category" class="form-label mb-2">Category <span class="text-danger">*</span></label>
                            <select id="category" name="category" class="form-select py-2 px-3" required>
                                <option value="" disabled {{ old('category') == '' ? 'selected' : '' }}>Select Department</option>
                                <option value="Production"  {{ old('category') == 'Production'  ? 'selected' : '' }}>🏭 Production</option>
                                <option value="Logistics"   {{ old('category') == 'Logistics'   ? 'selected' : '' }}>🚚 Logistics</option>
                                <option value="Quality"     {{ old('category') == 'Quality'     ? 'selected' : '' }}>🔬 Quality</option>
                                <option value="Maintenance" {{ old('category') == 'Maintenance' ? 'selected' : '' }}>🔧 Maintenance</option>
                                <option value="Safety"      {{ old('category') == 'Safety'      ? 'selected' : '' }}>🛡️ Safety</option>
                                <option value="Admin"       {{ old('category') == 'Admin'       ? 'selected' : '' }}>📋 Admin</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="priority" class="form-label mb-2">Priority <span class="text-danger">*</span></label>
                            <select id="priority" name="priority" class="form-select py-2 px-3" required>
                                <option value="" disabled {{ old('priority') == '' ? 'selected' : '' }}>Assign Priority</option>
                                
                                <option value="High"     {{ old('priority') == 'High'     ? 'selected' : '' }}>🟠 High</option>
                                <option value="Medium"   {{ old('priority') == 'Medium'   ? 'selected' : '' }}>🟡 Medium</option>
                                <option value="Low"      {{ old('priority') == 'Low'      ? 'selected' : '' }}>🟢 Low</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="reminder_datetime" class="form-label mb-2">Reminder <span class="text-danger">*</span></label>
                            <input type="datetime-local"
                                   id="reminder_datetime"
                                   name="reminder_datetime"
                                   class="form-control py-2 px-3 @error('reminder_datetime') is-invalid @enderror"
                                   value="{{ old('reminder_datetime') }}"
                                   required>
                            @error('reminder_datetime')
                                <div class="text-danger mt-2 small fw-semibold"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label for="status" class="form-label mb-2">Status</label>
                            <select id="status" name="status" class="form-select py-2 px-3" required>
                                <option value="Pending"     {{ old('status') == 'Pending'     ? 'selected' : '' }}>Pending Review</option>
                                <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>Active (In Progress)</option>
                                <option value="Completed"   {{ old('status') == 'Completed'   ? 'selected' : '' }}>Pre-Completed Log</option>
                            </select>
                        </div>

                    </div>

                    <div class="pt-4 mt-4 footer-divider d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-submit w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-file-earmark-check-fill"></i> Create Task
                        </button>
                        <a href="/tasks" class="btn btn-cancel w-100 py-2 text-center">
                            Cancel
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>
@endsection