<?php

namespace App\Http\Controllers;

use App\Models\UserProject;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = UserProject::where('status', 'approved')
            ->orderByDesc('is_featured')
            ->orderBy('order')
            ->paginate(12);

        return view('projects.index', compact('projects'));
    }

    public function show(UserProject $project): View
    {
        abort_unless($project->status === 'approved', 404);

        $project->increment('views_count');

        return view('projects.show', compact('project'));
    }
}
