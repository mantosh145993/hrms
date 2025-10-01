<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('user')->latest()->paginate(10);
        $employees = User::all();
        return view('admin.tasks.index', compact('tasks','employees'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,in-progress,completed',
        ]);

        Task::create($request->all());
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        return view('admin.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $users = User::all();
        return view('admin.tasks.edit', compact('task','users'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,in-progress,completed',
        ]);

        $task->update($request->all());
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
