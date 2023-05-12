<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TodoController extends Controller
{
    public function index()
    {
        // $todos = Todo::where('user_id', auth()->user()->id)->get();
        // $todos = Todo::whereUserId(auth()->user()->id)->get();
        // $todos = auth()->user()->todos;

        $todos = Todo::where('user_id', auth()->user()->id)
            ->orderBy('is_complete', 'asc')
            ->orderBy('created_at', 'desc')
            // ->latest()
            ->get();

        $todosCompleted = Todo::where('user_id', auth()->user()->id)
            ->where('is_complete', true)
            ->count();
        return view('todo.index', compact('todos', 'todosCompleted'));
    }
    public function create()
    {
        return view('todo.create');
    }
    public function edit(Todo $todo)
    {
        // CODE BEFORE REFACTORING
        // if (auth()->user()->id == $todo->user_id) {
        //     return view('todo.edit', compact('todo'));
        // } else {
        //     // abort(403);
        //     // abort(403, 'Not authorized');
        //     return redirect()->route('todo.index')->with('danger', 'You are not authorized to edit this todo!');
        // }

        // CODE AFTER REFACTORING
        if (auth()->user()->id == $todo->user_id) {
            return view('todo.edit', compact('todo'));
        }
        return redirect()->route('todo.index')->with('danger', 'You are not authorized to edit this todo!');
    }
    public function store(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        // Practical
        // $todo = new Todo;
        // $todo->title = $request->title;
        // $todo->user_id = auth()->user()->id;
        // $todo->save();

        // Query Builder way
        // DB::table('todos')->insert([
        //     'title' => $request->title,
        //     'user_id' => auth()->user()->id,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // Eloquent Way - Readable
        $todo = Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => auth()->user()->id,
        ]);

        // Eloquent Way - Shortest
        // $request->user()->todos()->create($request->all());
        // $request->user()->todos()->create([
        //     'title' => ucfirst($request->title),
        // ]);

        // dd($todo);
        // dd($todo->toArray());

        return redirect()->route('todo.index')->with('success', 'Todo created successfully!');
    }
    public function complete(Todo $todo)
    {
        // CODE BEFORE REFACTORING
        // if (auth()->user()->id == $todo->user_id) {
        //     $todo->update([
        //         'is_complete' => true,
        //     ]);
        //     return redirect()->route('todo.index')->with('success', 'Todo completed successfully!');
        // } else {
        //     return redirect()->route('todo.index')->with('danger', 'You are not authorized to complete this todo!');
        // }

        // CODE AFTER REFACTORING
        if (auth()->user()->id == $todo->user_id) {
            $todo->update([
                'is_complete' => true,
            ]);
            return redirect()->route('todo.index')->with('success', 'Todo completed successfully!');
        }
        return redirect()->route('todo.index')->with('danger', 'You are not authorized to complete this todo!');
    }
    public function uncomplete(Todo $todo)
    {
        // CODE BEFORE REFACTORING
        // if (auth()->user()->id == $todo->user_id) {
        //     $todo->update([
        //         'is_complete' => false,
        //     ]);
        //     return redirect()->route('todo.index')->with('success', 'Todo uncompleted successfully!');
        // } else {
        //     return redirect()->route('todo.index')->with('danger', 'You are not authorized to uncomplete this todo!');
        // }

        // CODE AFTER REFACTORING
        if (auth()->user()->id == $todo->user_id) {
            $todo->update([
                'is_complete' => false,
            ]);
            return redirect()->route('todo.index')->with('success', 'Todo uncompleted successfully!');
        }
        return redirect()->route('todo.index')->with('danger', 'You are not authorized to uncomplete this todo!');
    }
    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|max:255'
        ]);

        // Practical
        // $todo->title = $request->title;
        // $todo->save();

        // Eloquent Way - Readable
        $todo->update([
            'title' => ucfirst($request->title)
        ]);
        return redirect()->route('todo.index')->with('success', 'Todo updated successfully!');
    }
    public function destroy(Todo $todo)
    {
        // CODE BEFORE REFACTORING
        // if (auth()->user()->id == $todo->user_id) {
        //     $todo->delete();
        //     return redirect()->route('todo.index')->with('success', 'Todo deleted successfully!');
        // } else {
        //     return redirect()->route('todo.index')->with('danger', 'You are not authorized to delete this todo!');
        // }

        // CODE AFTER REFACTORING
        if (auth()->user()->id == $todo->user_id) {
            $todo->delete();
            return redirect()
                ->route('todo.index')->with('success', 'Todo deleted successfully!');
        }
        return redirect()->route('todo.index')->with('danger', 'You are not authorized to delete this todo!');
    }
    public function destroyCompleted()
    {
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
            ->where('is_complete', true)
            ->get();
        foreach ($todosCompleted as $todo) {
            $todo->delete();
        }
        return redirect()->route('todo.index')->with('success', 'All completed todos deleted successfully!');
    }
}
