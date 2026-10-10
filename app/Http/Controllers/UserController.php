<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function show(string $username): View
    {
        $user = User::where('username', $username)
            ->whereNull('deletion_requested_at')
            ->firstOrFail();

        $projects = $user->projects()
            ->where('status', 'approved')
            ->orderByDesc('created_at')
            ->get();

        return view('users.show', compact('user', 'projects'));
    }
}
