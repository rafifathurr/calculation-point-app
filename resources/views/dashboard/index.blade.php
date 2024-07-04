@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title ">
                    <h4 class="az-dashboard-title" id="title">Dashboard</h4>
                </div>
                <!-- az-dashboard-one-title -->
                <div class="row row-sm my-2">
                    <div class="col-md-4 mb-2">
                        <div class="card card-dashboard-two rounded-5">
                            <div class="card-header">
                                <h2>{{ $total_all_order }}
                                    @if ($total_all_order - $total_all_order_last > $total_all_order_last)
                                        <i class="icon ion-md-trending-up tx-success"></i>
                                        <small>{{ $total_all_order - $total_all_order_last }}+</small>
                                    @elseif ($total_all_order - $total_all_order_last < $total_all_order_last)
                                        <i class="icon ion-md-trending-down tx-danger"></i>
                                        <small>{{ $total_all_order_last - $total_all_order < 0 ? $total_all_order_last - $total_all_order : ($total_all_order_last - $total_all_order) * -1 }}</small>
                                    @endif
                                </h2>
                                <p class="py-0"><b>Total Data Order</b></p>
                            </div><!-- card-header -->
                            <div class="card-body">
                                <div class="chart-wrapper">
                                    <div id="flotChart1" class="flot-chart"></div>
                                </div><!-- chart-wrapper -->
                            </div><!-- card-body -->
                        </div><!-- card -->
                    </div><!-- col -->
                    <div class="col-md-4 mb-2">
                        <div class="card card-dashboard-two rounded-5">
                            <div class="card-header">
                                <h2>{{ $total_order }}
                                    @if ($total_order - $total_order_last > $total_order_last)
                                        <i class="icon ion-md-trending-up tx-success"></i>
                                        <small>{{ $total_order - $total_order_last }}+</small>
                                    @elseif ($total_order - $total_order_last < $total_order_last)
                                        <i class="icon ion-md-trending-down tx-danger"></i>
                                        <small>{{ $total_order_last - $total_order < 0 ? $total_order_last - $total_order : ($total_order_last - $total_order) * -1 }}</small>
                                    @endif
                                </h2>
                                <p class="py-0"><b>Total Tambah Order</b></p>
                            </div><!-- card-header -->
                            <div class="card-body">
                                <div class="chart-wrapper">
                                    <div id="flotChart2" class="flot-chart"></div>
                                </div><!-- chart-wrapper -->
                            </div><!-- card-body -->
                        </div><!-- card -->
                    </div><!-- col -->
                    <div class="col-md-4 mb-2">
                        <div class="card card-dashboard-two rounded-5">
                            <div class="card-header">
                                <h2>{{ $total_substraction_order }}
                                    @if ($total_substraction_order - $total_substraction_order_last > $total_substraction_order_last)
                                        <i class="icon ion-md-trending-up tx-success"></i>
                                        <small>{{ $total_substraction_order - $total_substraction_order_last }}+</small>
                                    @elseif ($total_substraction_order - $total_substraction_order_last < $total_substraction_order_last)
                                        <i class="icon ion-md-trending-down tx-danger"></i>
                                        <small>{{ $total_substraction_order_last - $total_substraction_order < 0 ? $total_substraction_order_last - $total_substraction_order : ($total_substraction_order_last - $total_substraction_order) * -1 }}</small>
                                    @endif
                                </h2>
                                <p class="py-0"><b>Total Penukaran Point</b></p>
                            </div><!-- card-header -->
                            <div class="card-body">
                                <div class="chart-wrapper">
                                    <div id="flotChart3" class="flot-chart"></div>
                                </div><!-- chart-wrapper -->
                            </div><!-- card-body -->
                        </div><!-- card -->
                    </div><!-- col -->
                </div>
                <div class="row row-sm my-3">
                    <div class="col-lg-6 mg-t-20 mg-lg-t-0">
                        <div class="card card-dashboard-four rounded-5">
                            <div class="card-header">
                                <h6 class="card-title">Statistik Order Per Hari</h6>
                            </div><!-- card-header -->
                            <div class="card-body">
                                <div class="col-md-12 align-items-center">
                                    <div class="chart"><canvas id="chartBar"></canvas></div>
                                </div><!-- col -->
                            </div><!-- card-body -->
                        </div><!-- card-dashboard-four -->
                    </div><!-- col -->
                    <div class="col-lg-6 mg-t-20 mg-lg-t-0">
                        <div class="card card-dashboard-four rounded-5">
                            <div class="card-header">
                                <h6 class="card-title">Statistik Order</h6>
                            </div><!-- card-header -->
                            <div class="card-body row">
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="chart"><canvas id="chartDonut"></canvas></div>
                                </div><!-- col -->
                                <div class="col-md-6 col-lg-5 mg-lg-l-auto mg-t-20 mg-md-t-0">
                                    <div class="az-traffic-detail-item">
                                        <div>
                                            <span>Tambah Order</span>
                                            <span><span id="total_order_span">- </span><span
                                                    id="percentage_total_order_span">(-%)</span></span>
                                        </div>
                                    </div>
                                    <div class="az-traffic-detail-item">
                                        <div>
                                            <span>Penukaran Point</span>
                                            <span><span id="total_substraction_order_span">- </span><span
                                                    id="percentage_total_substraction_order_span">(-%)</span></span>
                                        </div>
                                    </div>
                                </div><!-- col -->
                            </div><!-- card-body -->
                        </div><!-- card-dashboard-four -->
                    </div><!-- col -->
                </div>
            </div>
            <!-- az-content-body -->
        </div>
    </div>
    @push('js-bottom')
        @include('js.dashboard')
    @endpush
@endsection
