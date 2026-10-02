<!DOCTYPE html>
<html>
<head>
    <title>Create Task - TaskPulse</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        body{
            background:#f4f6f9;
        }

        .card{
            border-radius:15px;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <span class="navbar-brand">
            TaskPulse - Create Task
        </span>
    </div>
</nav>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow">

                <div class="card-header bg-success text-white">
                    <h4>Create New Task</h4>
                </div>

                <div class="card-body">

                    <form method="POST" action="/tasks/store">

                        @csrf

                        <div class="mb-3">
                            <label class="form-label">
                                Task Name
                            </label>

                            <input
                                type="text"
                                name="task_name"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                class="form-control"
                                rows="4"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Priority
                            </label>

                            <select
                                name="priority"
                                class="form-control"
                                required>

                                <option value="">
                                    Select Priority
                                </option>

                                <option value="High">
                                    High
                                </option>

                                <option value="Medium">
                                    Medium
                                </option>

                                <option value="Low">
                                    Low
                                </option>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Reminder Date & Time
                            </label>

                            <input
                                type="datetime-local"
                                name="reminder_datetime"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-control"
                                required>

                                <option value="Pending">
                                    Pending
                                </option>

                                <option value="In Progress">
                                    In Progress
                                </option>

                                <option value="Completed">
                                    Completed
                                </option>

                                <option value="Cancelled">
                                    Cancelled
                                </option>

                            </select>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-success">

                            Save Task

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>