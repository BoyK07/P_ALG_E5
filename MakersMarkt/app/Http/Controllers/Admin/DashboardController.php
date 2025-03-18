<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index() {
        return view('admin.index');
    }

    public function users() {
        $users = User::where('verified', false)->get();
        return view('admin.users', compact('users'));
    }

    public function products() {
        $products = Product::all();
        return view('admin.products', compact('products'));
    }
}
