<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', date("Y-m-d"));
        $endDate = $request->get('end_date', date("Y-m-d"));

        $orders = Order::with(['items', 'payments'])->where([['created_at', '>=', $startDate . " 00:00:00"], ['created_at', '<=', $endDate . " 23:59:59"]])->get();
        $customers_count = Customer::count();
        if (Auth::user()->role != 'admin') {
            $orders = $orders->where('user_id', Auth::user()->id);
        }
        return view('home', [
            'orders_count' => $orders->count(),
            'income' => $orders->map(function ($i) {
                if ($i->receivedAmount() > $i->total()) {
                    return $i->total();
                }
                return $i->receivedAmount();
            })->sum(),
            'customers_count' => $customers_count
        ]);
    }
}
