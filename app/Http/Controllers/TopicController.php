<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Inertia\Inertia;

class TopicController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Topic/Index');
    }

    public function destroy(Topic $topic)
    {
        //
    }
}
