<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\PointGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PointGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dt_route'] = route('point-grade.dataTable'); // Route DataTables
        return view('master.point_grade.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.point_grade.create');
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable()
    {
        $list_of_point_grade = PointGrade::whereNull('deleted_by')->whereNull('deleted_at')->get(); // All Point Grade Record

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($list_of_point_grade)
            ->addIndexColumn()
            ->addColumn('range', function ($data) {
                return $data->range_min . ' - ' . $data->range_max;
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('point-grade.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary rounded-5" title="Detail"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('point-grade.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                $btn_action .= '</div>';
                return $btn_action;
            })
            ->only(['name', 'range', 'action'])
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
                'range_min' => 'required',
                'range_max' => 'required',
            ]);

            // Validation Range Point Grade
            $range_validation = PointGrade::whereNull('deleted_by')
                ->whereNull('deleted_at')
                ->where('range_min', $request->range_min)
                ->where('range_max', $request->range_max)
                ->first();

            // Validation Name Range Point Grade
            $name_validation = PointGrade::whereNull('deleted_by')
                ->whereNull('deleted_at')
                ->where('name', $request->name)
                ->where('name', strtolower($request->name))
                ->first();

            // Validation Condition Field
            if (is_null($range_validation) && is_null($name_validation)) {
                DB::beginTransaction();

                // Create Record
                $point_grade = PointGrade::lockforUpdate()->create([
                    'name' => $request->name,
                    'range_min' => $request->range_min,
                    'range_max' => $request->range_max,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);

                // Checking Store Data
                if ($point_grade) {
                    DB::commit();
                    return redirect()
                        ->route('point-grade.index')
                        ->with(['success' => 'Berhasil Menambahkan Point Grade']);
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Tambah Point Grade'])
                        ->withInput();
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Nama atau Range Sudah Tersedia'])
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
    public function show(string $id)
    {
        try {
            // Point Grade Detail by Requested Id
            $point_grade = PointGrade::find($id);

            // Check Request Validation
            if (!is_null($point_grade)) {
                $data['point_grade'] = $point_grade;
                return view('master.point_grade.detail', $data);
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
            // Point Grade Detail by Requested Id
            $point_grade = PointGrade::find($id);

            // Check Request Validation
            if (!is_null($point_grade)) {
                $data['point_grade'] = $point_grade;
                return view('master.point_grade.edit', $data);
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
                'range_min' => 'required',
                'range_max' => 'required',
            ]);

            // Validation Range Point Grade
            $range_validation = PointGrade::whereNull('deleted_by')
                ->whereNull('deleted_at')
                ->where('range_min', $request->range_min)
                ->where('range_max', $request->range_max)
                ->where('id', '!=', $id)
                ->first();

            // Validation Name Range Point Grade
            $name_validation = PointGrade::whereNull('deleted_by')
                ->whereNull('deleted_at')
                ->where('name', $request->name)
                ->where('name', strtolower($request->name))
                ->where('id', '!=', $id)
                ->first();

            // Validation Condition Field
            if (is_null($range_validation) && is_null($name_validation)) {
                // Get Point Grade Record
                $point_grade = PointGrade::find($id);

                // Validation Point Grade
                if (!is_null($point_grade)) {
                    DB::beginTransaction();

                    // Update Record
                    $point_grade_update = PointGrade::where('id', $id)->update([
                        'name' => $request->name,
                        'range_min' => $request->range_min,
                        'range_max' => $request->range_max,
                        'updated_by' => Auth::user()->id,
                    ]);

                    // Checking Store Data
                    if ($point_grade_update) {
                        DB::commit();
                        return redirect()
                            ->route('point-grade.index')
                            ->with(['success' => 'Berhasil Ubah Point Grade']);
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Ubah Point Grade'])
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
                    ->with(['failed' => 'Nama atau Range Sudah Tersedia'])
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
            $point_grade_destroy = PointGrade::where('id', $id)->update([
                'deleted_by' => Auth::user()->id,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);

            // Validation Destroy Point Grade
            if ($point_grade_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Point Grade');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Point Grade');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
