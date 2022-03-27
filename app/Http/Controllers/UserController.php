<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/User/Index', [
            'users' => User::query()
                ->when(request()->input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->without(['topics'])
                ->withCount('topics')
                ->latest()
                ->paginate(10)
                ->withQueryString(),

            'filters' => request()->only(['search'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/User/Create');
    }

    public function store()
    {
        Request::validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'role'     => 'required|digits_between:0,1',
        ]);

        User::create([
            'name'     => Request::input('name'),
            'email'    => Request::input('email'),
            'password' => Hash::make(Request::input('password')),
            'is_admin' => Request::input('role')
        ]);

        return back()->with('message', 'Added successfully!');
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/User/Edit', compact('user'));
    }

    public function update(User $user)
    {
        Request::validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255',
            'password' => 'nullable',
            'role'     => 'required|digits_between:0,1',
        ]);

        if (Request::input('email') != $user->email)
            Request::validate(['email' => 'unique:users']);

        if (Request::input('password')) {
            Request::validate([
                'password' => ['required', Rules\Password::defaults()],
            ]);

            $user->password = Hash::make(Request::input('password'));
        }

        $user->name     = Request::input('name');
        $user->email    = Request::input('email');
        $user->is_admin = Request::input('role');

        $user->save();

        return back()->with('message', 'Updated successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back();
    }
}
