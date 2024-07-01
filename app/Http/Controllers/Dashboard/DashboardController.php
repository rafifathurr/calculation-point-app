<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Container Content Total All Order Dashboard
        $data['total_all_order'] = Order::whereNull('deleted_by')->whereNull('deleted_at')->count();
        $data['total_all_order_last'] = Order::whereNull('deleted_by')->whereNull('deleted_at')->whereDate('created_at', '<', date('Y-m-d'))->count();

        // Container Content Total Order Dashboard
        $data['total_order'] = Order::whereNull('deleted_by')->whereNull('deleted_at')->where('type', 0)->count();
        $data['total_order_last'] = Order::whereNull('deleted_by')->whereNull('deleted_at')->where('type', 0)->whereDate('created_at', '<', date('Y-m-d'))->count();

        // Container Content Total Substraction Point Order Dashboard
        $data['total_substraction_order'] = Order::whereNull('deleted_by')->whereNull('deleted_at')->where('type', 1)->count();
        $data['total_substraction_order_last'] = Order::whereNull('deleted_by')->whereNull('deleted_at')->where('type', 1)->whereDate('created_at', '<', date('Y-m-d'))->count();

        return view('dashboard.index', $data);
    }
}
