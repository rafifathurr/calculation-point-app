<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dt_route'] = route('customer.dataTable'); // Route DataTables
        return view('master.customer.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.customer.create');
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable()
    {
        $list_of_customer = Customer::whereNull('deleted_by')->whereNull('deleted_at')->get(); // All Customer

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($list_of_customer)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('customer.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary rounded-5" title="Detail"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('customer.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                $btn_action .= '</div>';
                return $btn_action;
            })
            ->only(['name', 'phone', 'address', 'point', 'action'])
            ->rawColumns(['action'])
            ->make(true);

        return $dataTable;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Request Validation
            $request->validate([
                'name' => 'required',
                'phone' => 'required',
            ]);

            // Validation Phone Number
            $phone_number_validation = Customer::whereNull('deleted_by')
                ->whereNull('deleted_at')
                ->where('phone', $request->phone)
                ->first();

            // Validation Condition Field
            if (is_null($phone_number_validation)) {
                DB::beginTransaction();

                // Create Record
                $customer = Customer::lockforUpdate()->create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);

                // Checking Store Data
                if ($customer) {
                    DB::commit();
                    return redirect()
                        ->route('customer.index')
                        ->with(['success' => 'Berhasil Menambahkan Customer']);
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Tambah Customer'])
                        ->withInput();
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Nomor Telepon Sudah Tersedia'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            // Customer Detail by Requested Id
            $customer = Customer::find($id);

            // Check Type Request
            if (!$request->ajax()) {
                // Check Request Validation
                if (!is_null($customer)) {
                    $data['customer'] = $customer;
                    return view('master.customer.detail', $data);
                } else {
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Data Tidak Tersedia']);
                }
            } else {
                // Check Request Validation
                if (!is_null($customer)) {
                    return response()->json(['customer' => $customer], 200);
                } else {
                    return response()->json(['message' => 'Data Tidak Tersedia'], 400);
                }
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            // Customer Detail by Requested Id
            $customer = Customer::find($id);

            // Check Request Validation
            if (!is_null($customer)) {
                $data['customer'] = $customer;
                return view('master.customer.edit', $data);
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Data Tidak Tersedia']);
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Request Validation
            $request->validate([
                'name' => 'required',
                'phone' => 'required',
            ]);

            // Validation Phone Number
            $phone_number_validation = Customer::whereNull('deleted_by')
                ->whereNull('deleted_at')
                ->where('phone', $request->phone)
                ->where('id', '!=', $id)
                ->first();

            // Validation Condition Field
            if (is_null($phone_number_validation)) {
                // Get Customer Record
                $customer = Customer::find($id);

                // Validation Customer
                if (!is_null($customer)) {
                    DB::beginTransaction();

                    // Update Customer Record
                    $customer_update = Customer::where('id', $id)->update([
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'updated_by' => Auth::user()->id,
                    ]);

                    // Checking Update Data
                    if ($customer_update) {
                        DB::commit();
                        return redirect()
                            ->route('customer.index')
                            ->with(['success' => 'Berhasil Ubah Customer']);
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Ubah Customer'])
                            ->withInput();
                    }
                } else {
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Permintaan Gagal!']);
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Nomor Telepon Sudah Tersedia'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            // Destroy with Softdelete
            $customer_destroy = Customer::where('id', $id)->update([
                'deleted_by' => Auth::user()->id,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);

            // Validation Destroy Customer
            if ($customer_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Customer');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Customer');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
