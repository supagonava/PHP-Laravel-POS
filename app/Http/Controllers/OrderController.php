<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = new Order();
        if ($request->start_date) {
            $orders = $orders->where('created_at', '>=', $request->start_date);
        } else {
            $orders = $orders->where('created_at', '>=', date('Y-m-d 00:00:00'));
        }

        if ($request->end_date) {
            $orders = $orders->where('created_at', '<=', $request->end_date . ' 23:59:59');
        } else {
            $orders = $orders->where('created_at', '<=',  date('Y-m-d 23:59:59'));
        }

        if ($request->cashier_name) {
            $orders = $orders->whereHas('cashier', function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->cashier_name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->cashier_name . '%');
            });
        }
        if (Auth::user()->role != 'admin') {
            $orders = $orders->where('user_id', Auth::user()->id);
        }
        $orders = $orders->with(['items', 'payments', 'customer'])->latest()->paginate(10);

        $total = $orders->map(function ($i) {
            return $i->total();
        })->sum();
        $receivedAmount = $orders->map(function ($i) {
            return $i->receivedAmount();
        })->sum();

        return view('orders.index', compact('orders', 'total', 'receivedAmount'));
    }

    public function store(OrderStoreRequest $request)
    {
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'user_id' => $request->user()->id,
        ]);

        $cart = $request->user()->cart()->get();
        foreach ($cart as $item) {
            $order->items()->create([
                'price' => $item->price * $item->pivot->quantity,
                'quantity' => $item->pivot->quantity,
                'product_id' => $item->id,
            ]);
            $item->quantity = $item->quantity - $item->pivot->quantity;
            $item->save();
        }
        $request->user()->cart()->detach();
        $order->payments()->create([
            'amount' => $request->amount,
            'user_id' => $request->user()->id,
        ]);
        return 'success';
    }
}
