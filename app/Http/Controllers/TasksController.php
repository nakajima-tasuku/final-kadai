<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks();
            return response()->json(['tasks' => $tasks], 200);
        } else {
            return response()->json(['error' => 'ログインしていません。'], 401);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'status' => 'required',
        ]);

        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'タスクを正常に作成できました。'], 200);
    }

    public function show(string $id)
    {
        $task = Task::findOrFail($id);

        if (\Auth::id() === $task->user_id) {
            return response()->json(['task' => $task], 200);
        } else {
            return response()->json(['error' => 'タスクの詳細が取得できませんでした。'], 401);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'content' => 'required',
            'status' => 'required',
        ]);

        $task = Task::findOrFail($id);

        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();

        return response()->json(['message' => 'タスクを正常に更新できました。'], 200);
    }

    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);

        if (\Auth::id() === $task->user_id) {
            $task->delete();
            return response()->json(['message' => 'タスクを正常に削除できました。'], 200);
        } else {
            return response()->json(['error' => 'タスクを削除できませんでした。'], 401);
        }
    }
}
