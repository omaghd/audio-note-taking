<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class TopicController extends Controller
{
    public function index()
    {
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

        return Inertia::render('Admin/Topic/Index', [
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

    public function restore($topicId)
    {
        Topic::withTrashed()->where('id', $topicId)->restore();
        return back();
    }
}
