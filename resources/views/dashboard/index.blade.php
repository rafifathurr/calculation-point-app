@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title ">
                    <h4 class="az-dashboard-title pb-3" id="title">Dashboard</h4>
                </div>
                <!-- az-dashboard-one-title -->
                <div class="card rounded-5">
                    <div class="card-body">
                        <div class="row row-sm my-2">
                            <div class="col-md-12">
                                <h6 class="az-dashboard-title py-1" id="title">Statistik Order</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-white rounded-5">
                                    <div class="card-body">
                                        <h2>{{ $total_all_order }}
                                            @if ($total_all_order - $total_all_order_last > $total_all_order_last)
                                                <i class="icon ion-md-trending-up tx-success"></i>
                                                <small>{{ $total_all_order - $total_all_order_last }}+</small>
                                            @endif
                                        </h2>
                                        <p class="py-2"><b>Total Data Order</b></p>
                                    </div><!-- card-body -->
                                </div><!-- card -->
                            </div><!-- col -->
                            <div class="col-md-4">
                                <div class="card bg-white rounded-5">
                                    <div class="card-body">
                                        <h2>{{ $total_order }}
                                            @if ($total_order - $total_order_last > $total_order_last)
                                                <i class="icon ion-md-trending-up tx-success"></i>
                                                <small>{{ $total_order - $total_order_last }}+</small>
                                            @endif
                                        </h2>
                                        <p class="py-2"><b>Total Order</b></p>
                                    </div><!-- card-body -->
                                </div><!-- card -->
                            </div><!-- col -->
                            <div class="col-md-4">
                                <div class="card bg-white rounded-5">
                                    <div class="card-body">
                                        <h2>{{ $total_substraction_order }}
                                            @if ($total_substraction_order - $total_substraction_order_last > $total_substraction_order_last)
                                                <i class="icon ion-md-trending-up tx-success"></i>
                                                <small>{{ $total_substraction_order - $total_substraction_order_last }}+</small>
                                            @endif
                                        </h2>
                                        <p class="py-2"><b>Total Penukaran Point</b></p>
                                    </div><!-- card-body -->
                                </div><!-- card -->
                            </div><!-- col -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- az-content-body -->
        </div>
    </div>
    @push('js-bottom')
        @include('js.dashboard')
    @endpush
@endsection
