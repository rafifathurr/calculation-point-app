@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Promo Point</h4>
                    @if (Illuminate\Support\Facades\Auth::user()->hasRole('owner'))
                        <a href="{{ route('promo-point.create') }}" class="btn btn-sm rounded-5 btn-primary">
                            <i class="fas fa-plus mr-1"></i>
                            Tambah Promo Point
                        </a>
                    @endif
                </div>
                <div class="table-responsive">
                    <input type="hidden" id="datatable-url" value="{{ $dt_route }}">
                    <table class="table table-bordered mg-b-0" id="datatable-promo-point">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Durasi</th>
                                <th>Point</th>
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
        @include('js.promo_point')
        <script>
            dataTable();
        </script>
    @endpush
@endsection
