@extends('layouts.main')
@section('content')
    <div class="az-content mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">User Management</h4>
                    <a href="{{ route('user-management.create') }}" class="btn btn-sm rounded-5 btn-primary">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah User
                    </a>
                </div>
                <div class="table-responsive">
                    <input type="hidden" id="datatable-url" value="{{ $dt_route }}">
                    <table class="table table-bordered mg-b-0" id="datatable-user">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Role</th>
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
        @include('js.user_management')
        <script>
            dataTable();
        </script>
    @endpush
@endsection
