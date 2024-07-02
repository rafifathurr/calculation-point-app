<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;

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

    /**
     * Return Data of Order Statistics
     */
    public function orderStatistic()
    {
        try {
            // Create Data Statistic Chart Bar Per Day
            for ($day = 1; $day <= date('d'); $day++) {
                // Config Per Day
                $date = date('Y') . '-' . date('m') . '-' . $day;

                // Total Add Order Per Day
                $total_order_per_day = Order::whereNull('deleted_by')->whereNull('deleted_at')->where('type', 0)->whereDate('created_at', $date)->count();

                // Total Substraction Order Per Day
                $total_substraction_order_per_day = Order::whereNull('deleted_by')->whereNull('deleted_at')->where('type', 1)->whereDate('created_at', $date)->count();

                // Add to Array
                $data['bar']['days'][] = $day;
                $data['bar']['total_order'][] = $total_order_per_day;
                $data['bar']['total_substraction_order'][] = $total_substraction_order_per_day;
            }

            // Total All Order Current
            $total_all_order = Order::whereNull('deleted_by')->whereNull('deleted_at')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();

            // Total Add Order Current
            $total_order = Order::whereNull('deleted_by')->whereNull('deleted_at')->where('type', 0)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();

            // Total Substraction Order Current
            $total_substraction_order = Order::whereNull('deleted_by')->whereNull('deleted_at')->where('type', 1)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();

            // Add to Array
            $data['donuts']['total_order'] = $total_order;
            $data['donuts']['total_substraction_order'] = $total_substraction_order;
            $data['donuts']['total_order_percentage'] = intval(round(($total_order / $total_all_order) * 100));
            $data['donuts']['total_substraction_order_percentage'] = intval(round(($total_substraction_order / $total_all_order) * 100));

            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
