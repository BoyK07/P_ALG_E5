<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Determine which layout to use based on user role
        if ($user->hasRole('admin') || $user->hasRole('moderator')) {
            $users = User::where('verified', false)->get();
            $products = Product::all();
            return view('admin.index', compact('products', 'users'));
        }

        if ($user->hasRole('maker')) {
            return view('maker.index');
        }

        if ($user->hasRole('buyer')) {
            return view('buyer.index');
        }
    }
}
