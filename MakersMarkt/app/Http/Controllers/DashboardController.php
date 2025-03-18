<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Determine which layout to use based on user role
        if ($user->hasRole('admin') || $user->hasRole('moderator')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('maker')) {
            return view('maker.index');
        }

        if ($user->hasRole('buyer')) {
            return view('buyer.index');
        }
    }
}
