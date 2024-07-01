@extends('layouts.main')
@section('content')
    <div class="az-content mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Point Grade</h4>
                    <a href="{{ route('point-grade.create') }}" class="btn btn-sm rounded-5 btn-primary">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah Point Grade
                    </a>
                </div>
                <div class="table-responsive">
                    <input type="hidden" id="datatable-url" value="{{ $dt_route }}">
                    <table class="table table-bordered mg-b-0" id="datatable-point-grade">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jangkauan Point</th>
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
        @include('js.point_grade')
        <script>
            dataTable();
        </script>
    @endpush
@endsection
