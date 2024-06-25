<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\RuleCalculationPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RuleCalculationPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dt_route'] = route('rule-calculation-point.dataTable'); // Route DataTables
        return view('master.rule_calculation_point.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.rule_calculation_point.create');
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable()
    {
        $list_of_users = RuleCalculationPoint::whereNull('deleted_by')->whereNull('deleted_at')->get(); // All Rule Calculation Point

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($list_of_users)
            ->addIndexColumn()
            ->addColumn('availability', function ($data) {
                // Condition Availability
                if (!is_null($data->month) && !is_null($data->day)) {
                    if (!is_null($data->year)) {
                        // Get Custom Availability
                        $availability = $data->day . ' ' . date('F', mktime(0, 0, 0, $data->month, 10)) . ' ' . $data->year;
                        return $availability;
                    } else {
                        // Get Custom Availability
                        $availability = $data->day . ' ' . date('F', mktime(0, 0, 0, $data->month, 10));
                        return $availability;
                    }
                } else {
                    return 'Setiap Saat';
                }
            })
            ->addColumn('status', function ($data) {
                $status = '<div align="center">';
                // Get Status Condition
                if ($data->status == 0) {
                    $status .= '<span class="badge badge-danger p-1 px-2 rounded-pill">Tidak Aktif</span>';
                } elseif ($data->status == 1) {
                    $status .= '<span class="badge badge-success p-1 px-3 rounded-pill">Aktif</span>';
                }
                return $status;
            })
            ->addColumn('percentage', function ($data) {
                // Get Custom Percentage
                return $data->percentage . '%';
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('rule-calculation-point.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary rounded-5" title="Detail"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('rule-calculation-point.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                $btn_action .= '</div>';
                return $btn_action;
            })
            ->only(['name', 'availability', 'status', 'percentage', 'action'])
            ->rawColumns(['status', 'action'])
            ->make(true);

        return $dataTable;
    }

    public function dateConfiguration(Request $request)
    {
        try {
            return response()->json($this->getDateConfiguration($request->year, $request->month), 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
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
                'percentage' => 'required',
                'status' => 'required',
                'availability' => 'required',
            ]);

            if ($request->availability == 0) {
                DB::beginTransaction();

                $rule_calculation_point = RuleCalculationPoint::create([
                    'name' => $request->name,
                    'percentage' => $request->percentage,
                    'status' => $request->status,
                    'description' => $request->description,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);

                // Checking Store Data
                if ($rule_calculation_point) {
                    DB::commit();
                    return redirect()
                        ->route('rule-calculation-point.index')
                        ->with(['success' => 'Berhasil Menambahkan Rule Kalkulasi Point']);
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Tambah Rule Kalkulasi Point'])
                        ->withInput();
                }
            } else {
                DB::beginTransaction();

                $rule_calculation_point = RuleCalculationPoint::create([
                    'name' => $request->name,
                    'percentage' => $request->percentage,
                    'status' => $request->status,
                    'day' => $request->day,
                    'month' => $request->month,
                    'year' => $request->year == '' ? null : $request->year,
                    'description' => $request->description,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);

                // Checking Store Data
                if ($rule_calculation_point) {
                    DB::commit();
                    return redirect()
                        ->route('rule-calculation-point.index')
                        ->with(['success' => 'Berhasil Menambahkan Rule Kalkulasi Point']);
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Tambah Rule Kalkulasi Point'])
                        ->withInput();
                }
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
    public function show(string $id)
    {
        try {
            // Rule Calculation Detail by Requested Id
            $rule_calculation_point = RuleCalculationPoint::find($id);

            // Check Request Validation
            if (!is_null($rule_calculation_point)) {
                return view('master.rule_calculation_point.detail', compact('rule_calculation_point'));
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            // Rule Calculation Detail by Requested Id
            $rule_calculation_point = RuleCalculationPoint::find($id);

            // Check Request Validation
            if (!is_null($rule_calculation_point)) {
                $months = $this->getDateConfiguration(null, null);
                $days = $this->getDateConfiguration($rule_calculation_point->year, $rule_calculation_point->month);
                return view('master.rule_calculation_point.edit', compact('rule_calculation_point', 'months', 'days'));
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
                'percentage' => 'required',
                'status' => 'required',
                'availability' => 'required',
            ]);

            // Get Rule Calculation Record
            $rule_calculation_point = RuleCalculationPoint::find($id);

            // Validation User
            if (!is_null($rule_calculation_point)) {
                if ($request->availability == 0) {
                    DB::beginTransaction();

                    // Update Rule Calculation Point
                    $rule_calculation_point_update = RuleCalculationPoint::where('id', $id)->update([
                        'name' => $request->name,
                        'percentage' => $request->percentage,
                        'status' => $request->status,
                        'day' => null,
                        'month' => null,
                        'year' => null,
                        'description' => $request->description,
                        'updated_by' => Auth::user()->id,
                    ]);

                    // Checking Update Data
                    if ($rule_calculation_point_update) {
                        DB::commit();
                        return redirect()
                            ->route('rule-calculation-point.index')
                            ->with(['success' => 'Berhasil Ubah Rule Kalkulasi Point']);
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Ubah Rule Kalkulasi Point'])
                            ->withInput();
                    }
                } else {
                    DB::beginTransaction();

                    // Update Rule Calculation Point
                    $rule_calculation_point_update = RuleCalculationPoint::where('id', $id)->update([
                        'name' => $request->name,
                        'percentage' => $request->percentage,
                        'status' => $request->status,
                        'day' => $request->day,
                        'month' => $request->month,
                        'year' => $request->year == '' ? null : $request->year,
                        'description' => $request->description,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ]);

                    // Checking Update Data
                    if ($rule_calculation_point_update) {
                        DB::commit();
                        return redirect()
                            ->route('rule-calculation-point.index')
                            ->with(['success' => 'Berhasil Ubah Rule Kalkulasi Point']);
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Ubah Rule Kalkulasi Point'])
                            ->withInput();
                    }
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Permintaan Gagal!']);
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
            $rule_calculation_point_destroy = RuleCalculationPoint::where('id', $id)->update([
                'deleted_by' => Auth::user()->id,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);

            // Validation Destroy Rule Calculation Point
            if ($rule_calculation_point_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Rule Calculation Point');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus User');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }

    /**
     * Date Configuration Universal Function
     */
    private function getDateConfiguration($year_request = null, $month_request = null)
    {
        if (!is_null($year_request) && is_null($month_request)) {
            $months = [];
            for ($month = 1; $month <= 12; $month++) {
                $months[$month] = date('F', mktime(0, 0, 0, $month, 10));
            }
            return $months;
        } else {
            if (is_null($year_request) && is_null($month_request)) {
                $months = [];
                for ($month = 1; $month <= 12; $month++) {
                    $months[$month] = date('F', mktime(0, 0, 0, $month, 10));
                }
                return $months;
            } else {
                if (is_null($year_request) && !is_null($month_request)) {
                    $days = [];
                    for ($day = 1; $day <= ($d = cal_days_in_month(CAL_GREGORIAN, $month_request, 2004)); $day++) {
                        $days[$day] = $day;
                    }
                    return $days;
                } else {
                    if (!is_null($year_request) && !is_null($month_request)) {
                        $days = [];
                        for ($day = 1; $day <= ($d = cal_days_in_month(CAL_GREGORIAN, $month_request, $year_request)); $day++) {
                            $days[$day] = $day;
                        }
                        return $days;
                    } else {
                        return [];
                    }
                }
            }
        }
    }
}
