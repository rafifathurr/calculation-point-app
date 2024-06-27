@extends('layouts.main')
@section('content')
    <div class="az-content mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Ubah Point Grade</h4>
                </div>
                <form class="forms-sample" method="post"
                    action="{{ route('point-grade.update', ['id' => $point_grade->id]) }}">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Grade"
                            value="{{ $point_grade->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="range_min">Minimal Point <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="range_min" name="range_min" min="0"
                            placeholder="Minimal Point" value="{{ $point_grade->range_min }}" required>
                    </div>
                    <div class="form-group">
                        <label for="range_max">Maksimal Point <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="range_max" name="range_max" min="0"
                            placeholder="Maksimal Point" value="{{ $point_grade->range_max }}" required>
                    </div>
                    <div class="float-right mt-3">
                        <a href="{{ route('point-grade.index') }}" class="btn btn-sm rounded-5 btn-danger">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary rounded-5 mx-2">
                            Simpan
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </form>
                <!-- az-dashboard-one-title -->
            </div>
            <!-- az-content-body -->
        </div>
    </div>
    @push('js-bottom')
        @include('js.point_grade')
    @endpush
@endsection
