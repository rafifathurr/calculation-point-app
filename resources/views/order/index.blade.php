@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Order</h4>
                    @if (Illuminate\Support\Facades\Auth::user()->hasRole('cashier'))
                        <button type="button" onclick="createOrder()" class="btn btn-sm rounded-5 btn-primary">
                            <i class="fas fa-plus mr-1"></i>
                            Tambah Order
                        </button>
                    @endif
                </div>
                <div class="table-responsive">
                    <input type="hidden" id="datatable-url" value="{{ $dt_route }}">
                    <table class="table table-bordered mg-b-0" id="datatable-order">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Tipe Order</th>
                                <th>Total Harga</th>
                                <th>Total Point</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div><!-- table-responsive -->
                <!-- az-dashboard-one-title -->
            </div>
            <!-- az-content-body -->
        </div>
    </div>
    @push('js-bottom')
        @include('js.order')
        <script>
            dataTable();
        </script>
    @endpush
@endsection
