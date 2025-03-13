<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Web\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->check()) {
            abort(404);
        }

        $tasks = auth()->user()->tasks()->get();

        return view('auth.calendar', compact('tasks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->check()) {
            abort(404);
        }
        
        $task = Task::query()->find($id);
        if ($task) {
            $task->delete();
        }
        
        $tasks = auth()->user()->tasks()->get();

        return view('auth.calendar', compact('tasks'));
    }
}
