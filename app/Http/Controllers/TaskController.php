<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a clean listing of the user's tasks with integrated search/filtering.
     */
    public function index(Request $request)
    {
        // 1. Build base query isolated strictly to the authenticated user
        $query = Task::where('user_id', Auth::id());

        // 2. Intercept live asynchronous search indexing inputs
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('task_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // 3. Intercept operational status filter adjustments
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->latest()->get();

        // 4. Return API datasets for JavaScript DOM rebuild pipelines
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($tasks);
        }

        // 5. Standard fallback for initial synchronous page layout deliveries
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created task in storage securely.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_name'         => 'required|string|max:255',
            'category'          => 'nullable|string|max:100',
            'priority'          => 'required|string|in:Critical,High,Medium,Low',
            'reminder_datetime' => 'required',
            'status'            => 'nullable|string|in:Pending,In Progress,Completed,Overdue,Cancelled',
            'description'       => 'nullable|string'
        ]);

        $task = Task::create([
            'user_id'           => Auth::id(),
            'task_name'         => strip_tags($validated['task_name']),
            'description'       => strip_tags($request->description),
            'category'          => $validated['category'] ?? 'Production',
            'priority'          => $validated['priority'],
            'reminder_datetime' => $validated['reminder_datetime'],
            'status'            => $validated['status'] ?? 'Pending',
            'notification_sent' => false,
            'acknowledged'      => false
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'task' => $task]);
        }

        return redirect('/dashboard')->with('success', 'Task Created Successfully');
    }

    /**
     * Reminder polling API
     */
public function getPendingReminder()
{
    $dueTask = Task::where('user_id', Auth::id())
        ->where('acknowledged', 0)
        ->where('reminder_datetime', '<=', now())
        ->orderBy('reminder_datetime', 'asc')
        ->first();

    if ($dueTask) {
        return response()->json([
            'id' => $dueTask->id,
            'task_name' => $dueTask->task_name
        ]);
    }

    return response()->json([]);
}

    /**
     * Show the form for editing the specified task resource.
     */
    public function edit($id)
    {
        $task = Task::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified task resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'task_name'         => 'required|string|max:255',
            'category'          => 'required|string|max:100',
            'priority'          => 'required|string|in:Critical,High,Medium,Low',
            'reminder_datetime' => 'required',
            'status'            => 'required|string|in:Pending,In Progress,Completed,Overdue,Cancelled',
            'description'       => 'nullable|string'
        ]);

        $task = Task::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $task->update([
            'task_name'         => strip_tags($validated['task_name']),
            'description'       => strip_tags($request->description),
            'category'          => $validated['category'],
            'priority'          => $validated['priority'],
            'reminder_datetime' => $validated['reminder_datetime'],
            'status'            => $validated['status']
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Task Updated Successfully']);
        }

        return redirect('/dashboard')->with('success', 'Task Updated Successfully');
    }

    /**
     * Remove the specified task from storage safely.
     */
    public function delete($id)
    {
        $task = Task::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $task->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task Deleted Successfully'
            ]);
        }

        return redirect('/dashboard')->with('success', 'Task Deleted Successfully');
    }

    /**
     * Complete reminder status toggle operations.
     */
public function completeReminder($id)
{
    $task = Task::findOrFail($id);

    $task->update([
        'status' => 'Completed',
        'acknowledged' => 1
    ]);

    return response()->json([
        'success' => true
    ]);
}

    /**
     * Dismiss reminder
     */
public function dismissReminder($id)
{
    return response()->json([
        'success' => true
    ]);
}
}