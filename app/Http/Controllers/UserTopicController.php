<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class UserTopicController extends Controller
{
    public function __invoke(User $user)
    {
        if (Request::filled('trash'))
            $this->authorize('is-admin');

        $topics = Request::filled('trash')
            ? Topic::onlyTrashed()
                ->orderByDesc('deleted_at')
            : Topic::query()
                ->when(request()->input('done'), function ($query) {
                    $query->where('is_done', 1);
                })
                ->when(request()->input('undone'), function ($query) {
                    $query->where('is_done', 0);
                })
                ->latest();

        return Inertia::render('Admin/User/Topics', [
            'user'   => $user,
            'topics' => $topics
                ->when(request()->input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->with(['user', 'audio'])
                ->paginate(10)
                ->withQueryString(),

            'filters' => request()->only(['search'])
        ]);
    }
}
