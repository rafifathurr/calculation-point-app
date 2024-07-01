<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Menu;
use App\Models\Master\PromoPoint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PromoPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dt_route'] = route('promo-point.dataTable'); // Route DataTables
        return view('master.promo_point.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['menus'] = Menu::whereNull('deleted_by')->whereNull('deleted_at')->get();
        return view('master.promo_point.create', $data);
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable()
    {
        $list_of_promo_point = PromoPoint::whereNull('deleted_by')->whereNull('deleted_at')->get(); // All Promo Point

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($list_of_promo_point)
            ->addIndexColumn()
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
            ->addColumn('duration', function ($data) {
                return date('d F Y', strtotime($data->start_on)) . ' - ' . date('d F Y', strtotime($data->expired_on));
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('promo-point.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary rounded-5" title="Detail"><i class="fas fa-eye"></i></a>';

                // Accessing only for Owner
                if (User::find(Auth::user()->id)->hasRole('owner')) {
                    $btn_action .= '<a href="' . route('promo-point.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
                    $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                }

                $btn_action .= '</div>';
                return $btn_action;
            })
            ->only(['name', 'status', 'duration', 'point', 'action'])
            ->rawColumns(['status', 'action'])
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
                'menu' => 'required',
                'status' => 'required',
                'point' => 'required',
                'qty' => 'required',
                'start_on' => 'required',
                'expired_on' => 'required',
                'attachment' => 'required',
            ]);

            DB::beginTransaction();

            // Create Record
            $promo_point = PromoPoint::lockforUpdate()->create([
                'name' => $request->name,
                'menu_id' => $request->menu,
                'status' => $request->status,
                'point' => $request->point,
                'qty' => $request->qty,
                'start_on' => $request->start_on,
                'expired_on' => $request->expired_on,
                'description' => $request->description,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($promo_point) {
                // Image Path
                $path = 'public/uploads/promo';
                $path_store = 'storage/uploads/promo';

                // Check Exsisting Path
                if (!Storage::exists($path)) {
                    // Create new Path Directory
                    Storage::makeDirectory($path);
                }

                // File Upload Configuration
                $exploded_name = explode(' ', strtolower($request->name));
                $name_promo_config = implode('_', $exploded_name);
                $file = $request->file('attachment');
                $file_name = $promo_point->id . '_' . $name_promo_config . '.' . $file->getClientOriginalExtension();

                // Uploading File
                $file->storePubliclyAs($path, $file_name);

                // Check Upload Success
                if (Storage::exists($path . '/' . $file_name)) {
                    // Update Record for Attachment
                    $promo_point_update = PromoPoint::where('id', $promo_point->id)->update([
                        'attachment' => $path_store . '/' . $file_name,
                    ]);

                    // Validation Update Attachment Promo Point Record
                    if ($promo_point_update) {
                        DB::commit();
                        return redirect()
                            ->route('promo-point.index')
                            ->with(['success' => 'Berhasil Menambahkan Promo Point']);
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Update Foto Promo Point'])
                            ->withInput();
                    }
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Upload Foto Promo Point'])
                        ->withInput();
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Tambah Promo Point'])
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
            // Promo Point Detail by Requested Id
            $promo_point = PromoPoint::with(['menu'])->find($id);

            // Check Type Request
            if (!$request->ajax()) {
                // Check Request Validation
                if (!is_null($promo_point)) {
                    $data['promo_point'] = $promo_point;
                    return view('master.promo_point.detail', $data);
                } else {
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Data Tidak Tersedia']);
                }
            } else {
                // Check Request Validation
                if (!is_null($promo_point)) {
                    return response()->json(['promo_point' => $promo_point], 200);
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
            // Promo Point Detail by Requested Id
            $promo_point = PromoPoint::find($id);

            // Check Request Validation
            if (!is_null($promo_point)) {
                $data['promo_point'] = $promo_point;
                $data['menus'] = Menu::whereNull('deleted_by')->whereNull('deleted_at')->get();
                return view('master.promo_point.edit', $data);
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
                'menu' => 'required',
                'status' => 'required',
                'point' => 'required',
                'qty' => 'required',
                'start_on' => 'required',
                'expired_on' => 'required',
            ]);

            // Get Promo Point Record
            $promo_point = PromoPoint::find($id);

            // Validation Promo Point
            if (!is_null($promo_point)) {
                DB::beginTransaction();

                // Update Record
                $promo_point_update = PromoPoint::where('id', $id)->update([
                    'name' => $request->name,
                    'menu_id' => $request->menu,
                    'status' => $request->status,
                    'point' => $request->point,
                    'qty' => $request->qty,
                    'start_on' => $request->start_on,
                    'expired_on' => $request->expired_on,
                    'description' => $request->description,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);

                // Checking Update Data
                if ($promo_point_update) {
                    // Check Has Request File
                    if (!empty($request->allFiles())) {
                        // Image Path
                        $path = 'public/uploads/promo';
                        $path_store = 'storage/uploads/promo';

                        // Check Exsisting Path
                        if (!Storage::exists($path)) {
                            // Create new Path Directory
                            Storage::makeDirectory($path);
                        }

                        // File Last Record
                        $attachment_exploded = explode('/', $promo_point->attachment);
                        $file_name_record = $attachment_exploded[count($attachment_exploded) - 1];

                        // Remove Last Record
                        if (Storage::exists($path . '/' . $file_name_record)) {
                            Storage::delete($path . '/' . $file_name_record);
                        }

                        // File Upload Configuration
                        $exploded_name = explode(' ', strtolower($request->name));
                        $name_menu_config = implode('_', $exploded_name);
                        $file = $request->file('attachment');
                        $file_name = $promo_point->id . '_' . $name_menu_config . '.' . $file->getClientOriginalExtension();

                        /**
                         * Upload File
                         */
                        $file->storePubliclyAs($path, $file_name);

                        // Check Upload Success
                        if (Storage::exists($path . '/' . $file_name)) {
                            // Update Record for Attachment
                            $promo_point_attachment_update = PromoPoint::where('id', $id)->update([
                                'attachment' => $path_store . '/' . $file_name,
                            ]);

                            // Validation Update Attachment Promo Point Record
                            if ($promo_point_attachment_update) {
                                DB::commit();
                                return redirect()
                                    ->route('promo-point.index')
                                    ->with(['success' => 'Berhasil Ubah Promo Point']);
                            } else {
                                // Failed and Rollback
                                DB::rollBack();
                                return redirect()
                                    ->back()
                                    ->with(['failed' => 'Gagal Update Foto Promo Point'])
                                    ->withInput();
                            }
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Upload Foto Promo Point'])
                                ->withInput();
                        }
                    } else {
                        DB::commit();
                        return redirect()
                            ->route('promo-point.index')
                            ->with(['success' => 'Berhasil Ubah Promo Point']);
                    }
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Tambah Promo Point'])
                        ->withInput();
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
            $promo_point_destroy = PromoPoint::where('id', $id)->update([
                'deleted_by' => Auth::user()->id,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);

            // Validation Destroy Promo Point
            if ($promo_point_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Promo Point');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Promo Point');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
