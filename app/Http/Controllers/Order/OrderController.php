<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\Menu;
use App\Models\Master\PromoPoint;
use App\Models\Master\RuleCalculationPoint;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Order\OrderRulePoint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dt_route'] = route('order.dataTable'); // Route DataTables
        return view('order.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($type)
    {
        if (in_array($type, [0, 1])) {
            $data['customers'] = Customer::whereNull('deleted_by')->whereNull('deleted_at')->get();
            $data['type'] = $type;

            if ($type == 0) {
                $data['title'] = 'Tambah Order';
                $total_percentage = 0;
                $total_percentage += RuleCalculationPoint::whereNull('deleted_by')->whereNull('deleted_at')->where('status', 1)->whereNull('day')->whereNull('month')->whereNull('year')->sum('percentage');
                $total_percentage += RuleCalculationPoint::whereNull('deleted_by')->whereNull('deleted_at')->where('status', 1)->where('day', date('d'))->where('month', date('m'))->sum('percentage');
                $total_percentage += RuleCalculationPoint::whereNull('deleted_by')->whereNull('deleted_at')->where('status', 1)->where('day', date('d'))->where('month', date('m'))->where('year', date('Y'))->sum('percentage');
                $data['total_percentage'] = $total_percentage / 100;
            } else {
                $data['title'] = 'Penukaran Point';
            }

            return view('order.create', $data);
        } else {
            return redirect()
                ->back()
                ->with(['failed' => 'Permintaan Tidak Sesuai!']);
        }
    }

    /**
     * Show List of Catalogue Menu
     */
    public function catalogueMenu(Request $request)
    {
        try {
            if (!is_null($request->type)) {
                if ($request->type == 0) {
                    if (!is_null($request->search)) {
                        // Get Menu Record
                        $menu = Menu::whereNull('deleted_by')
                            ->whereNull('deleted_at')
                            ->whereNotNull('price')
                            ->where('name', 'like', '%' . $request->search . '%')
                            ->paginate(6);
                    } else {
                        // Get Menu Record
                        $menu = Menu::whereNull('deleted_by')->whereNull('deleted_at')->whereNotNull('price')->paginate(6);
                    }

                    if (count($menu) > 0) {
                        return view('order.includes.catalogue.menu', ['menus' => $menu]);
                    } else {
                        return view('order.includes.catalogue.notfound');
                    }
                } else {
                    if (!is_null($request->search)) {
                        // Get Menu Record
                        $menu = PromoPoint::with(['menu'])
                            ->whereNull('deleted_by')
                            ->whereNull('deleted_at')
                            ->where('status', 1)
                            ->where('point', '<=', $request->point)
                            ->whereDate('start_on', '<=', date('Y-m-d'))
                            ->whereDate('expired_on', '>=', date('Y-m-d'))
                            ->whereHas('menu', function ($query) use ($request) {
                                return $query->where('name', 'like', '%' . $request->search . '%');
                            })
                            ->paginate(6);
                    } else {
                        // Get Menu Record
                        $menu = PromoPoint::with(['menu'])
                            ->whereNull('deleted_by')
                            ->whereNull('deleted_at')
                            ->where('status', 1)
                            ->where('point', '<=', $request->point)
                            ->whereDate('start_on', '<=', date('Y-m-d'))
                            ->whereDate('expired_on', '>=', date('Y-m-d'))
                            ->paginate(6);
                    }

                    if (count($menu) > 0) {
                        return view('order.includes.catalogue.promo_point', ['menus' => $menu]);
                    } else {
                        return view('order.includes.catalogue.notfound');
                    }
                }
            } else {
                return view('order.includes.default');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable()
    {
        $list_of_orders = Order::whereNull('deleted_by')->whereNull('deleted_at')->get(); // All Order

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($list_of_orders)
            ->addIndexColumn()
            ->addColumn('created_at', function ($data) {
                // Date Time Convert
                return date('d F Y H:i:s', strtotime($data->created_at));
            })
            ->addColumn('type', function ($data) {
                return $data->type == 0 ? 'Tambah Order' : 'Penukaran Point';
            })
            ->addColumn('total_price', function ($data) {
                if (!is_null($data->total_price)) {
                    return 'Rp. ' . number_format($data->total_price, 0, ',', '.') . ',-';
                } else {
                    return null;
                }
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('order.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary rounded-5" title="Detail"><i class="fas fa-eye"></i></a>';

                // Accessing only for Cashier
                if (User::find(Auth::user()->id)->hasRole('cashier')) {
                    $btn_action .= '<a href="' . route('order.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
                    $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                    $btn_action .= '</div>';
                }
                return $btn_action;
            })
            ->only(['name', 'created_at', 'type', 'total_price', 'total_point', 'action'])
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
                'type' => 'required',
                'customer' => 'required',
                'order_item' => 'required',
            ]);

            // Validation Request Type Order
            if ($request->type == 0) {
                // Request Validation
                $request->validate([
                    'total_price' => 'required',
                    'total_point' => 'required',
                ]);

                DB::beginTransaction();

                // Create Order Record
                $order = Order::lockForUpdate()->create([
                    'type' => $request->type,
                    'customer_id' => $request->customer,
                    'total_price' => $request->total_price,
                    'total_point' => $request->total_point,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);

                // Validation Store Order
                if ($order) {
                    foreach ($request->order_item as $menu_id => $order_item) {
                        $order_item_request[] = [
                            'order_id' => $order->id,
                            'menu_id' => $menu_id,
                            'qty' => $order_item['qty'],
                            'price' => $order_item['price'],
                            'point' => $order_item['point'],
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ];
                    }

                    // Insert Order Item
                    $order_item = OrderItem::lockForUpdate()->insert($order_item_request);

                    // Validation Store Order Item
                    if ($order_item) {
                        // Get All Current Rule Available
                        $rule_all = RuleCalculationPoint::whereNull('deleted_by')->whereNull('deleted_at')->where('status', 1)->whereNull('day')->whereNull('month')->whereNull('year')->get()->toArray();
                        $rule_date_without_year = RuleCalculationPoint::whereNull('deleted_by')->whereNull('deleted_at')->where('status', 1)->where('day', date('d'))->where('month', date('m'))->get()->toArray();
                        $rule_date_with_year = RuleCalculationPoint::whereNull('deleted_by')->whereNull('deleted_at')->where('status', 1)->where('day', date('d'))->where('month', date('m'))->where('year', date('Y'))->get()->toArray();

                        // Merge to single array
                        $result_rule = array_merge($rule_all, $rule_date_without_year, $rule_date_with_year);

                        if (!empty($result_rule)) {
                            foreach ($result_rule as $rule_calculation_point) {
                                $point_per_rule = $request->total_price * ($rule_calculation_point['percentage'] / 100);
                                $order_rule_point_request[] = [
                                    'order_id' => $order->id,
                                    'rule_calculation_point_id' => $rule_calculation_point['id'],
                                    'percentage' => $rule_calculation_point['percentage'],
                                    'point' => $point_per_rule,
                                    'created_by' => Auth::user()->id,
                                    'updated_by' => Auth::user()->id,
                                ];
                            }

                            // Insert Order Rule Point
                            $order_rule_point = OrderRulePoint::lockForUpdate()->insert($order_rule_point_request);

                            // Validation Store Order Rule Point
                            if ($order_rule_point) {
                                // Get Last Record Customer
                                $customer_record = Customer::find($order->customer_id);

                                // Calculation Point
                                $add_point = $customer_record->point + $order->total_point;

                                // Update Customer Point
                                $customer_update = Customer::where('id', $order->customer_id)->update([
                                    'point' => $add_point,
                                    'updated_by' => Auth::user()->id,
                                ]);

                                // Validation Update Customer Point
                                if ($customer_update) {
                                    DB::commit();
                                    return redirect()
                                        ->route('order.index')
                                        ->with(['success' => 'Berhasil Menambahkan Order']);
                                } else {
                                    // Failed and Rollback
                                    DB::rollBack();
                                    return redirect()
                                        ->back()
                                        ->with(['failed' => 'Gagal Tambah Point Customer'])
                                        ->withInput();
                                }
                            } else {
                                // Failed and Rollback
                                DB::rollBack();
                                return redirect()
                                    ->back()
                                    ->with(['failed' => 'Gagal Tambah Rule Item Point'])
                                    ->withInput();
                            }
                        } else {
                            // Get Last Record Customer
                            $customer_record = Customer::find($order->customer_id);

                            // Calculation Point
                            $add_point = $customer_record->point + $order->total_point;

                            // Update Customer Point
                            $customer_update = Customer::where('id', $order->customer_id)->update([
                                'point' => $add_point,
                                'updated_by' => Auth::user()->id,
                            ]);

                            // Validation Update Customer Point
                            if ($customer_update) {
                                DB::commit();
                                return redirect()
                                    ->route('order.index')
                                    ->with(['success' => 'Berhasil Menambahkan Order']);
                            } else {
                                // Failed and Rollback
                                DB::rollBack();
                                return redirect()
                                    ->back()
                                    ->with(['failed' => 'Gagal Tambah Point Customer'])
                                    ->withInput();
                            }
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Tambah Item Order'])
                            ->withInput();
                    }
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Tambah Order'])
                        ->withInput();
                }
            } else {
                // Request Validation
                $request->validate([
                    'total_point' => 'required',
                ]);

                DB::beginTransaction();

                // Create Order Record
                $order = Order::lockForUpdate()->create([
                    'type' => $request->type,
                    'customer_id' => $request->customer,
                    'total_point' => $request->total_point,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);

                // Validation Store Order
                if ($order) {
                    foreach ($request->order_item as $menu_id => $order_item) {
                        $order_item_request[] = [
                            'order_id' => $order->id,
                            'menu_id' => $menu_id,
                            'promo_point_id' => $order_item['promo_point'],
                            'qty' => $order_item['qty'],
                            'point' => $order_item['point'],
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ];
                    }

                    // Insert Order Item
                    $order_item = OrderItem::lockForUpdate()->insert($order_item_request);

                    // Validation Store Order Item
                    if ($order_item) {
                        // Get Last Record Customer
                        $customer_record = Customer::find($order->customer_id);

                        // Calculation Point
                        $substraction_point = $customer_record->point - $order->total_point;

                        // Update Customer Point
                        $customer_update = Customer::where('id', $order->customer_id)->update([
                            'point' => $substraction_point,
                            'updated_by' => Auth::user()->id,
                        ]);

                        // Validation Update Customer Point
                        if ($customer_update) {
                            DB::commit();
                            return redirect()
                                ->route('order.index')
                                ->with(['success' => 'Berhasil Menambahkan Order']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Mengurangi Point Customer'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Tambah Item Order'])
                            ->withInput();
                    }
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Tambah Order'])
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
            // Order Detail by Requested Id
            $order = Order::with(['customer', 'orderItem.menu', 'orderItem.promoPoint.menu', 'orderRulePoint.ruleCalculationPoint'])->find($id);

            // Check Request Validation
            if (!is_null($order)) {
                $data['order'] = $order;
                return view('order.detail', $data);
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
            // Order Detail by Requested Id
            $order = Order::with(['customer', 'orderItem.menu', 'orderItem.promoPoint.menu', 'orderRulePoint.ruleCalculationPoint'])->find($id);

            // Check Request Validation
            if (!is_null($order)) {
                $data['order'] = $order;
                $data['customers'] = Customer::whereNull('deleted_by')->whereNull('deleted_at')->get();

                if ($order->type == 0) {
                    $data['title'] = 'Ubah Order';
                    $data['total_percentage'] = OrderRulePoint::where('order_id', $id)->sum('percentage') / 100;
                } else {
                    $data['title'] = 'Ubah Penukaran Point';
                }

                return view('order.edit', $data);
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
                'type' => 'required',
                'customer' => 'required',
                'order_item' => 'required',
            ]);

            // Validation Request Type Order
            if ($request->type == 0) {
                // Request Validation
                $request->validate([
                    'total_price' => 'required',
                    'total_point' => 'required',
                ]);

                DB::beginTransaction();

                // Check Order Last Record
                $order_last_record = Order::find($id);

                // Create Order Record
                $order = Order::where('id', $id)->update([
                    'type' => $request->type,
                    'customer_id' => $request->customer,
                    'total_price' => $request->total_price,
                    'total_point' => $request->total_point,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);

                // Validation Update Order
                if ($order) {
                    // Destroy Last Record Order Item
                    $order_item_destroy = OrderItem::where('order_id', $id)->update([
                        'deleted_by' => Auth::user()->id,
                        'deleted_at' => date('Y-m-d H:i:s'),
                    ]);

                    // Validation Destroy Order Item
                    if ($order_item_destroy) {
                        foreach ($request->order_item as $menu_id => $order_item) {
                            $order_item_request[] = [
                                'order_id' => $id,
                                'menu_id' => $menu_id,
                                'qty' => $order_item['qty'],
                                'price' => $order_item['price'],
                                'point' => $order_item['point'],
                                'created_by' => Auth::user()->id,
                                'updated_by' => Auth::user()->id,
                            ];
                        }

                        // Insert New Record Order Item
                        $order_item = OrderItem::lockForUpdate()->insert($order_item_request);

                        // Validation Store Order Item
                        if ($order_item) {
                            // Get All Rule from Last Record
                            $result_rule = OrderRulePoint::where('order_id', $id)->get()->toArray();

                            if (!empty($result_rule)) {
                                foreach ($result_rule as $order_rule_point) {
                                    // Calculation New Point
                                    $point_per_rule = $request->total_price * ($order_rule_point['percentage'] / 100);

                                    // Insert Order Rule Point
                                    $order_rule_point = OrderRulePoint::where('id', $order_rule_point['id'])->update([
                                        'rule_calculation_point_id' => $order_rule_point['rule_calculation_point_id'],
                                        'percentage' => $order_rule_point['percentage'],
                                        'point' => $point_per_rule,
                                        'updated_by' => Auth::user()->id,
                                    ]);

                                    // Validation Update Order Rule Point
                                    if (!$order_rule_point) {
                                        // Failed and Rollback
                                        DB::rollBack();
                                        return redirect()
                                            ->back()
                                            ->with(['failed' => 'Gagal Ubah Rule Item Point'])
                                            ->withInput();
                                    }
                                }
                            }

                            // Validation Customer Request
                            if ($request->customer == $order_last_record->customer_id) {
                                // Get Last Record Customer
                                $customer_record = Customer::find($request->customer);

                                // Calculation Point
                                $substraction_last_point = $customer_record->point - $order_last_record->total_point;

                                // Update New Point
                                $add_point = $substraction_last_point + $request->total_point;

                                // Update Customer Point
                                $customer_update = Customer::where('id', $request->customer)->update([
                                    'point' => $add_point,
                                    'updated_by' => Auth::user()->id,
                                ]);

                                // Validation Update Customer Point
                                if ($customer_update) {
                                    DB::commit();
                                    return redirect()
                                        ->route('order.index')
                                        ->with(['success' => 'Berhasil Ubah Order']);
                                } else {
                                    // Failed and Rollback
                                    DB::rollBack();
                                    return redirect()
                                        ->back()
                                        ->with(['failed' => 'Gagal Ubah Point Customer'])
                                        ->withInput();
                                }
                            } else {
                                // Get Last Record Customer
                                $customer_record = Customer::find($order_last_record->customer_id);

                                // Substraction Point
                                $substraction_last_point = $customer_record->point - $order_last_record->total_point;

                                // Update Customer Substraction Point
                                $customer_substraction_update = Customer::where('id', $order_last_record->customer_id)->update([
                                    'point' => $substraction_last_point,
                                    'updated_by' => Auth::user()->id,
                                ]);

                                // Get Request Record Customer
                                $customer_update_record = Customer::find($request->customer);

                                // Calculation Point
                                $add_point = $customer_update_record->point + $request->total_point;

                                // Update Customer Point
                                $customer_update = Customer::where('id', $request->customer)->update([
                                    'point' => $add_point,
                                    'updated_by' => Auth::user()->id,
                                ]);

                                // Validation Update Customer Point
                                if ($customer_substraction_update && $customer_update) {
                                    DB::commit();
                                    return redirect()
                                        ->route('order.index')
                                        ->with(['success' => 'Berhasil Ubah Order']);
                                } else {
                                    // Failed and Rollback
                                    DB::rollBack();
                                    return redirect()
                                        ->back()
                                        ->with(['failed' => 'Gagal Ubah Point Customer'])
                                        ->withInput();
                                }
                            }
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Tambah Item Order'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Ubah Item Order'])
                            ->withInput();
                    }
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Ubah Order'])
                        ->withInput();
                }
            } else {
                // Request Validation
                $request->validate([
                    'total_point' => 'required',
                ]);

                DB::beginTransaction();

                // Check Order Last Record
                $order_last_record = Order::find($id);

                // Create Order Record
                $order = Order::where('id', $id)->update([
                    'type' => $request->type,
                    'customer_id' => $request->customer,
                    'total_point' => $request->total_point,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);

                // Validation Update Order
                if ($order) {
                    // Destroy Last Record Order Item
                    $order_item_destroy = OrderItem::where('order_id', $id)->update([
                        'deleted_by' => Auth::user()->id,
                        'deleted_at' => date('Y-m-d H:i:s'),
                    ]);

                    // Validation Destroy Order Item
                    if ($order_item_destroy) {
                        foreach ($request->order_item as $menu_id => $order_item) {
                            $order_item_request[] = [
                                'order_id' => $id,
                                'menu_id' => $menu_id,
                                'promo_point_id' => $order_item['promo_point'],
                                'qty' => $order_item['qty'],
                                'point' => $order_item['point'],
                                'created_by' => Auth::user()->id,
                                'updated_by' => Auth::user()->id,
                            ];
                        }

                        // Insert Order Item
                        $order_item = OrderItem::lockForUpdate()->insert($order_item_request);

                        // Validation Store Order Item
                        if ($order_item) {
                            // Validation Customer Request
                            if ($request->customer == $order_last_record->customer_id) {
                                // Get Last Record Customer
                                $customer_record = Customer::find($request->customer);

                                // Calculation Point
                                $add_last_point = $customer_record->point + $order_last_record->total_point;

                                // Update New Point
                                $substraction_point = $add_last_point - $request->total_point;

                                // Update Customer Point
                                $customer_update = Customer::where('id', $request->customer)->update([
                                    'point' => $substraction_point,
                                    'updated_by' => Auth::user()->id,
                                ]);

                                // Validation Update Customer Point
                                if ($customer_update) {
                                    DB::commit();
                                    return redirect()
                                        ->route('order.index')
                                        ->with(['success' => 'Berhasil Ubah Order']);
                                } else {
                                    // Failed and Rollback
                                    DB::rollBack();
                                    return redirect()
                                        ->back()
                                        ->with(['failed' => 'Gagal Ubah Point Customer'])
                                        ->withInput();
                                }
                            } else {
                                // Get Last Record Customer
                                $customer_record = Customer::find($order_last_record->customer_id);

                                // Substraction Point
                                $add_last_point = $customer_record->point + $order_last_record->total_point;

                                // Update Customer Substraction Point
                                $customer_substraction_update = Customer::where('id', $order_last_record->customer_id)->update([
                                    'point' => $add_last_point,
                                    'updated_by' => Auth::user()->id,
                                ]);

                                // Get Request Record Customer
                                $customer_update_record = Customer::find($request->customer);

                                // Calculation Point
                                $substraction_point = $customer_update_record->point - $request->total_point;

                                // Update Customer Point
                                $customer_update = Customer::where('id', $request->customer)->update([
                                    'point' => $substraction_point,
                                    'updated_by' => Auth::user()->id,
                                ]);

                                // Validation Update Customer Point
                                if ($customer_substraction_update && $customer_update) {
                                    DB::commit();
                                    return redirect()
                                        ->route('order.index')
                                        ->with(['success' => 'Berhasil Ubah Order']);
                                } else {
                                    // Failed and Rollback
                                    DB::rollBack();
                                    return redirect()
                                        ->back()
                                        ->with(['failed' => 'Gagal Ubah Point Customer'])
                                        ->withInput();
                                }
                            }
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Tambah Item Order'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Ubah Item Order'])
                            ->withInput();
                    }
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Ubah Order'])
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Check Order Request
            $order = Order::find($id);

            // Validation Order
            if (!is_null($order)) {
                DB::beginTransaction();

                // Destroy with Softdelete
                $order_destroy = Order::where('id', $id)->update([
                    'deleted_by' => Auth::user()->id,
                    'deleted_at' => date('Y-m-d H:i:s'),
                ]);

                // Validation Destroy Order
                if ($order_destroy) {
                    // Destroy with Softdelete Order Item
                    $order_item_destroy = OrderItem::where('order_id', $id)->update([
                        'deleted_by' => Auth::user()->id,
                        'deleted_at' => date('Y-m-d H:i:s'),
                    ]);

                    if ($order_item_destroy) {
                        // Check Order Type as Add Order or Subtraction Point
                        if ($order->type == 0) {
                            // Destroy with Softdelete Order Rule Calculation Point
                            $order_rule_calculation_point_destroy = OrderRulePoint::where('order_id', $id)->update([
                                'deleted_by' => Auth::user()->id,
                                'deleted_at' => date('Y-m-d H:i:s'),
                            ]);

                            if ($order_rule_calculation_point_destroy) {
                                // Get Last Record Customer
                                $customer_record = Customer::find($order->customer_id);

                                // Calculation Point
                                $substraction_last_point = $customer_record->point - $order->total_point;

                                // Update Customer Point
                                $customer_update = Customer::where('id', $order->customer_id)->update([
                                    'point' => $substraction_last_point,
                                    'updated_by' => Auth::user()->id,
                                ]);

                                // Validation Update Customer Point
                                if ($customer_update) {
                                    DB::commit();
                                    session()->flash('success', 'Berhasil Hapus Order');
                                } else {
                                    // Failed and Rollback
                                    DB::rollBack();
                                    session()->flash('failed', 'Gagal Ubah Point');
                                }
                            } else {
                                // Failed and Rollback
                                DB::rollBack();
                                session()->flash('failed', 'Gagal Hapus Order Rule Point');
                            }
                        } else {
                            // Get Last Record Customer
                            $customer_record = Customer::find($order->customer_id);

                            // Calculation Point
                            $add_last_point = $customer_record->point + $order->total_point;

                            // Update Customer Point
                            $customer_update = Customer::where('id', $order->customer_id)->update([
                                'point' => $add_last_point,
                                'updated_by' => Auth::user()->id,
                            ]);

                            // Validation Update Customer Point
                            if ($customer_update) {
                                DB::commit();
                                session()->flash('success', 'Berhasil Hapus Order');
                            } else {
                                // Failed and Rollback
                                DB::rollBack();
                                session()->flash('failed', 'Gagal Ubah Point');
                            }
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        session()->flash('failed', 'Gagal Hapus Order Item');
                    }
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    session()->flash('failed', 'Gagal Hapus Order');
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Permintaan Gagal!']);
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
