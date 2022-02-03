<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Inertia\Inertia;

class TopicController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Topic/Index', [
            'topics' => Topic::query()
                ->when(request()->input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->with(['user', 'audio'])
                ->latest()
                ->paginate(5)
                ->withQueryString(),

            'filters' => request()->only(['search'])
        ]);
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();
        return back();
    }
}
