<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->with('product')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Order::class);

        $request->validate([
            'product_id' => 'required|exists:products,product_id',
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);
        $storeCreditUsed = min($user->store_credit, $product->price);
        $orderDate = now()->toDateString();

        DB::transaction(function () use ($user, $product, $storeCreditUsed, $orderDate) {
            $order = Order::create([
                'buyer_id' => $user->id,
                'product_id' => $product->product_id,
                'store_credit_used' => $storeCreditUsed,
                'status' => 'pending',
                'order_date' => $orderDate,
            ]);

            $user->decrement('store_credit', $storeCreditUsed);
        });

        return redirect()->route('orders.index')->with('success', 'Bestelling geplaatst!');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('updateStatus', $order);

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'status_description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($order, $request) {
            OrderStatusHistory::create([
                'order_id' => $order->order_id,
                'old_status' => $order->status,
                'new_status' => $request->status,
                'changed_at' => now(),
            ]);

            $order->update([
                'status' => $request->status,
                'status_description' => $request->status_description,
            ]);
        });

        return back()->with('success', 'Bestellingstatus bijgewerkt!');
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);
        $order->delete();

        return back()->with('success', 'Bestelling verwijderd!');
    }
}

