<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class AudioTopicController extends Controller
{
    public function index(Audio $audio)
    {
        return Inertia::render('Admin/Audio/Topics', [
            'audio' => $audio->load([
                'topics' => function ($query) {
                    if (request()->input('done'))
                        $query->where('is_done', 1);
                    else if (request()->input('undone'))
                        $query->where('is_done', 0);
                },
                'topics.user'
            ])
        ]);
    }

    public function store(Audio $audio)
    {
        Request::validate([
            'title' => 'required|string',
            'time'  => 'required|numeric'
        ]);

        $topic           = new Topic();
        $topic->title    = Request::input('title');
        $topic->time     = Request::input('time');
        $topic->user_id  = Request::user()->id;
        $topic->audio_id = $audio->id;

        $topic->save();

        return back()->with('message', 'Added successfully!');
    }
}
