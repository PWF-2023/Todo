<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Materi 5
        // $users = User::where('id', '!=', '1')
        //     ->orderBy('name')
        //     ->paginate(10);

        // $users = User::where('id', '!=', '1')
        //     ->orderBy('name')
        //     ->simplePaginate(10);

        $search = request('search');
        if ($search) {
            $users = User::where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
                ->where('id', '!=', '1')
                ->orderBy('name')
                ->paginate(10)
                // ->simplePaginate(10)
                ->withQueryString();
        } else {
            $users = User::where('id', '!=', '1')
                ->orderBy('name')
                ->paginate(10);
                // ->simplePaginate(10);
            // ->cursorPaginate(10);
        }
        // dd($users->toArray());
        return view('user.index', compact('users'));
    }
}
