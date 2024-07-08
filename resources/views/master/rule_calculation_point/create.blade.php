@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Tambah Rule Kalkulasi Point</h4>
                </div>
                <form class="forms-sample" method="post" action="{{ route('rule-calculation-point.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Rule"
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="percentage">Percentase Point <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="percentage" name="percentage"
                                placeholder="Persentase Point" value="{{ old('percentage') }}" required>
                            <span class="input-group-text">
                                %
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="status" name="status" required>
                            <option disabled hidden selected>Pilih Status</option>
                            <option value="0" @if (!is_null(old('status')) && old('status') == 0) selected @endif>Tidak Aktif</option>
                            <option value="1" @if (!is_null(old('status')) && old('status') == 1) selected @endif>Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="availability">Availability <span class="text-danger">*</span></label>
                        <select class="form-control" id="availability" name="availability"
                            onchange="changeAvailability(this)" required>
                            <option disabled hidden selected>Pilih Availability</option>
                            <option value="0" @if (!is_null(old('availability')) && old('availability') == 0) selected @endif>Setiap Saat</option>
                            <option value="1" @if (!is_null(old('availability')) && old('availability') == 1) selected @endif>Dengan Waktu</option>
                        </select>
                    </div>
                    <div @if (!is_null(old('availability')) && old('availability') == 1) class="d-block" @else class="d-none" @endif
                        id="availability-form">
                        <div class="form-group">
                            <label for="year">Tahun <span class="text-danger">*</span></label>
                            <select class="form-control" id="year" name="year" onchange="yearConfiguration()">
                                <option disabled hidden selected>Pilih Tahun</option>
                                <option value="">Tanpa Tahun</option>
                                @for ($year = date('Y'); $year <= date('Y') + 2; $year++)
                                    <option value="{{ $year }}" @if (!is_null(old('year')) && old('year') == $year) selected @endif>
                                        {{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="month">Bulan <span class="text-danger">*</span></label>
                            <select class="form-control" id="month" name="month" onchange="monthConfiguration()">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="month">Tanggal <span class="text-danger">*</span></label>
                            <select class="form-control" id="day" name="day">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" name="description" id="description" cols="10" rows="3"
                            placeholder="Deskripsi">{{ old('description') }}</textarea>
                    </div>
                    <div class="float-right mt-3">
                        <a href="{{ route('rule-calculation-point.index') }}" class="btn btn-sm btn-danger rounded-5">
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
        @include('js.rule_calculation_point')
    @endpush
@endsection
