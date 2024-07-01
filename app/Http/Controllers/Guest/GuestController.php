<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\PointGrade;
use App\Models\Master\PromoPoint;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home()
    {
        // Get List of Current Active Promotion
        $data['promo_point'] = PromoPoint::with(['menu'])
            ->whereNull('deleted_by')
            ->whereNull('deleted_at')
            ->where('status', 1)
            ->whereDate('start_on', '<=', date('Y-m-d'))
            ->whereDate('expired_on', '>=', date('Y-m-d'))
            ->get();
        return view('guest.home', $data);
    }

    /**
     * Display Check of Guest Page
     */
    public function check()
    {
        return view('guest.check');
    }

    /**
     * Show Data Resource of Customer
     */
    public function getData(Request $request)
    {
        try {
            if (!is_null($request->phone)) {
                // Get Record Customer
                $customer = Customer::whereNull('deleted_by')
                    ->whereNull('deleted_at')
                    ->where('phone', $request->phone)
                    ->first();

                if (!is_null($customer)) {
                    //Get Grade based on Point
                    $grade = PointGrade::whereNull('deleted_by')
                        ->whereNull('deleted_at')
                        ->where('range_min', '<=', $customer->point)
                        ->where('range_max', '>=', $customer->point)
                        ->first();

                    return view('guest.includes.detail', ['customer' => $customer, 'grade' => $grade]);
                } else {
                    return response()->json(null, 200);
                }
            } else {
                return response()->json(null, 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
