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
                ->paginate(10)
                ->withQueryString(),

            'filters' => request()->only(['search'])
        ]);
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();
        return back();
    }

    public function done(Topic $topic)
    {
        $topic->is_done = true;
        $topic->done_at = now();
        $topic->save();
        return back();
    }

    public function undone(Topic $topic)
    {
        $topic->is_done = false;
        $topic->done_at = null;
        $topic->save();
        return back();
    }
}
