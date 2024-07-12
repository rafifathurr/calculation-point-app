<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dt_route'] = route('menu.dataTable'); // Route DataTables
        return view('master.menu.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.menu.create');
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable()
    {
        $list_of_menu = Menu::whereNull('deleted_by')->whereNull('deleted_at')->get(); // All Menu

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($list_of_menu)
            ->addIndexColumn()
            ->addColumn('attachment', function ($data) {
                // Set Up Image Attachment
                return '<img width="100%" src="' . asset($data->attachment) . '" alt="" class="rounded-5 border border-1-default">';
            })
            ->addColumn('price', function ($data) {
                // Condition Availability
                return 'Rp. ' . number_format($data->price, 0, ',', '.') . ',-';
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('menu.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary rounded-5" title="Detail"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('menu.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                $btn_action .= '</div>';
                return $btn_action;
            })
            ->only(['name', 'attachment', 'price', 'action'])
            ->rawColumns(['attachment', 'action'])
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
                'attachment' => 'required',
            ]);

            // Validation Menu
            $menu_name_validation = Menu::whereNull('deleted_by')
                ->whereNull('deleted_at')
                ->where('name', $request->name)
                ->orWhere('name', strtolower($request->name))
                ->first();

            // Va;idation Condition Field
            if (is_null($menu_name_validation)) {
                DB::beginTransaction();

                // Create Record
                $menu = Menu::lockforUpdate()->create([
                    'name' => $request->name,
                    'price' => $request->price,
                    'description' => $request->description,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);

                // Checking Store Data
                if ($menu) {
                    // Image Path
                    $path = 'public/uploads/menu';
                    $path_store = 'storage/uploads/menu';

                    // Check Exsisting Path
                    if (!Storage::exists($path)) {
                        // Create new Path Directory
                        Storage::makeDirectory($path);
                    }

                    // File Upload Configuration
                    $exploded_name = explode(' ', strtolower($request->name));
                    $name_menu_config = implode('_', $exploded_name);
                    $file = $request->file('attachment');
                    $file_name = $menu->id . '_' . $name_menu_config . '.' . $file->getClientOriginalExtension();

                    // Uploading File
                    $file->storePubliclyAs($path, $file_name);

                    // Check Upload Success
                    if (Storage::exists($path . '/' . $file_name)) {
                        // Update Record for Attachment
                        $menu_update = Menu::where('id', $menu->id)->update([
                            'attachment' => $path_store . '/' . $file_name,
                        ]);

                        // Validation Update Attachment Menu Record
                        if ($menu_update) {
                            DB::commit();
                            return redirect()
                                ->route('menu.index')
                                ->with(['success' => 'Berhasil Menambahkan Menu']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Update Foto Menu'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Foto Menu'])
                            ->withInput();
                    }
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Tambah Menu'])
                        ->withInput();
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Nama Menu Sudah Tersedia'])
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
            // Menu Detail by Requested Id
            $menu = Menu::find($id);

            // Check Type Request
            if (!$request->ajax()) {
                // Check Request Validation
                if (!is_null($menu)) {
                    $data['menu'] = $menu;
                    return view('master.menu.detail', $data);
                } else {
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Data Tidak Tersedia']);
                }
            } else {
                // Check Request Validation
                if (!is_null($menu)) {
                    return response()->json(['menu' => $menu], 200);
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
            // Menu Detail by Requested Id
            $menu = Menu::find($id);

            // Check Request Validation
            if (!is_null($menu)) {
                $data['menu'] = $menu;
                return view('master.menu.edit', $data);
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
            ]);

            // Validation Menu
            $menu_name_validation = Menu::whereNull('deleted_by')
                ->whereNull('deleted_at')
                ->where('name', $request->name)
                ->orWhere('name', strtolower($request->name))
                ->where('id', '!=', $id)
                ->first();

            // Va;idation Condition Field
            if (is_null($menu_name_validation)) {
                // Get Menu Record
                $menu = Menu::find($id);

                // Validation Menu
                if (!is_null($menu)) {
                    DB::beginTransaction();

                    // Update Menu Record
                    $menu_update = Menu::where('id', $id)->update([
                        'name' => $request->name,
                        'price' => $request->price,
                        'description' => $request->description,
                        'updated_by' => Auth::user()->id,
                    ]);

                    // Checking Update Data
                    if ($menu_update) {
                        // Check Has Request File
                        if (!empty($request->allFiles())) {
                            // Image Path
                            $path = 'public/uploads/menu';
                            $path_store = 'storage/uploads/menu';

                            // Check Exsisting Path
                            if (!Storage::exists($path)) {
                                // Create new Path Directory
                                Storage::makeDirectory($path);
                            }

                            // File Last Record
                            $attachment_exploded = explode('/', $menu->attachment);
                            $file_name_record = $attachment_exploded[count($attachment_exploded) - 1];

                            // Remove Last Record
                            if (Storage::exists($path . '/' . $file_name_record)) {
                                Storage::delete($path . '/' . $file_name_record);
                            }

                            // File Upload Configuration
                            $exploded_name = explode(' ', strtolower($request->name));
                            $name_menu_config = implode('_', $exploded_name);
                            $file = $request->file('attachment');
                            $file_name = $menu->id . '_' . $name_menu_config . '.' . $file->getClientOriginalExtension();

                            /**
                             * Upload File
                             */
                            $file->storePubliclyAs($path, $file_name);

                            // Check Upload Success
                            if (Storage::exists($path . '/' . $file_name)) {
                                // Update Record for Attachment
                                $menu_attachment_update = Menu::where('id', $id)->update([
                                    'attachment' => $path_store . '/' . $file_name,
                                ]);

                                // Validation Update Attachment Menu Record
                                if ($menu_attachment_update) {
                                    DB::commit();
                                    return redirect()
                                        ->route('menu.index')
                                        ->with(['success' => 'Berhasil Ubah Menu']);
                                } else {
                                    // Failed and Rollback
                                    DB::rollBack();
                                    return redirect()
                                        ->back()
                                        ->with(['failed' => 'Gagal Update Foto Menu'])
                                        ->withInput();
                                }
                            } else {
                                // Failed and Rollback
                                DB::rollBack();
                                return redirect()
                                    ->back()
                                    ->with(['failed' => 'Gagal Upload Foto Menu'])
                                    ->withInput();
                            }
                        } else {
                            DB::commit();
                            return redirect()
                                ->route('menu.index')
                                ->with(['success' => 'Berhasil Ubah Menu']);
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Ubah Menu'])
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
                    ->with(['failed' => 'Nama Menu Sudah Tersedia'])
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
            $menu_destroy = Menu::where('id', $id)->update([
                'deleted_by' => Auth::user()->id,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);

            // Validation Destroy Menu
            if ($menu_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Menu');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Menu');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
