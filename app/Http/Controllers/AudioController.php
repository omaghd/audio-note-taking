<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class AudioController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Audio/Index', [
            'audios' => Audio::query()
                ->when(request()->input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
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
        return Inertia::render('Admin/Audio/Create');
    }

    public function store()
    {
        Request::validate([
            'title' => 'required|string',
            'audio' => 'required|mimes:mp3',
        ]);

        $audio        = new Audio;
        $file         = Request::file('audio');
        $audio->path  = $file->storePubliclyAs("public/audios", $file->getClientOriginalName());
        $audio->title = Request::input('title');

        $audio->save();

        return back()->with('message', 'Added successfully!');
    }

    public function edit(Audio $audio)
    {
        return Inertia::render('Admin/Audio/Edit', compact('audio'));
    }

    public function update(Audio $audio)
    {
        Request::validate([
            'title' => 'required|string',
            'audio' => 'nullable|mimes:mp3',
        ]);

        if (Request::hasFile('audio')) {
            $file        = Request::file('audio');
            $audio->path = $file->storePubliclyAs("public/audios", $file->getClientOriginalName());
        }
        $audio->title = Request::input('title');

        $audio->save();

        return back()->with('message', 'Updated successfully!');
    }

    public function destroy(Audio $audio)
    {
        $audio->topics()->delete();
        $audio->delete();

        return back();
    }
}
