@extends('layouts.main')
@section('content')
    <div class="az-content">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Rule Kalkulasi Point</h4>
                    <a href="{{ route('rule-calculation-point.create') }}" class="btn btn-sm rounded-5 btn-primary">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah Rule
                    </a>
                </div>
                <div class="table-responsive">
                    <input type="hidden" id="datatable-url" value="{{ $dt_route }}">
                    <table class="table table-bordered mg-b-0" id="datatable-rule">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama </th>
                                <th>Berlaku Setiap </th>
                                <th>Status</th>
                                <th>Persentase Kalkulasi</th>
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
        @include('js.rule_calculation_point')
        <script>
            dataTable();
        </script>
    @endpush
@endsection
