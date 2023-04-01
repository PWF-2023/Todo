<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', auth()->user()->id)->get();
        // $todos = auth()->user()->todos;
        dd($todos);
        return view('todo.index');
    }
    public function create()
    {
        return view('todo.create');
    }
    public function edit()
    {
        return view('todo.edit');
    }
}
