@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5 pt-lg-5 pt-md-3">
        <div class="mx-lg-5 px-5">
            <div class="card border border-0">
                <div class="card-body p-0">
                    <div class="az-dashboard-one-title">
                        <h4 class="az-dashboard-title" id="title">Customer</h4>
                        @if (Illuminate\Support\Facades\Auth::user()->hasRole('cashier'))
                            <a href="{{ route('customer.create') }}" class="btn btn-sm rounded-5 btn-primary">
                                <i class="fas fa-plus mr-1"></i>
                                Tambah Customer
                            </a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <input type="hidden" id="datatable-url" value="{{ $dt_route }}">
                        <table class="table table-bordered mg-b-0" id="datatable-customer">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>No Telp</th>
                                    <th>Alamat</th>
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
    </div>
    @push('js-bottom')
        @include('js.customer')
        <script>
            dataTable();
        </script>
    @endpush
@endsection
