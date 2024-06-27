<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('guest.home');
    }

    public function check()
    {
        return view('guest.check');
    }

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
                    return view('guest.includes.detail', ['customer' => $customer]);
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
