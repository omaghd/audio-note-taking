<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\User;
use Inertia\Inertia;

class UserTopicController extends Controller
{
    public function __invoke(User $user)
    {
        return Inertia::render('Admin/User/Topics', [
            'user'   => $user,
            'topics' => Topic::query()
                ->when(request()->input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->where('user_id', $user->id)
                ->without(['user'])
                ->with('audio')
                ->latest()
                ->paginate(5)
                ->withQueryString(),

            'filters' => request()->only(['search'])
        ]);
    }
}
