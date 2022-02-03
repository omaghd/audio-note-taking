<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AudioController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Audio/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/Audio/Create');
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(Audio $audio)
    {
        return Inertia::render('Admin/Audio/Edit');
    }

    public function update(Request $request, Audio $audio)
    {
        //
    }

    public function destroy(Audio $audio)
    {
        //
    }
}
