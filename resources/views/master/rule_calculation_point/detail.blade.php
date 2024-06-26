@extends('layouts.main')
@section('content')
    <div class="az-content mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Detail Rule Kalkulasi Point</h4>
                </div>
                <div class="py-1">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $rule_calculation_point->name }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Persentase</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $rule_calculation_point->percentage }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9 col-form-label">
                            @if ($rule_calculation_point->status == 0)
                                <span class="badge badge-danger p-1 px-3 rounded-pill">Tidak Aktif</span>
                            @else
                                <span class="badge badge-success p-1 px-3 rounded-pill">Aktif</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Berlaku Setiap</label>
                        <div class="col-sm-9 col-form-label">
                            @if (!is_null($rule_calculation_point->month) && !is_null($rule_calculation_point->day))
                                @if (!is_null($rule_calculation_point->year))
                                    {{ $rule_calculation_point->day . ' ' . date('F', mktime(0, 0, 0, $rule_calculation_point->month, 10)) . ' ' . $rule_calculation_point->year }}
                                @else
                                    {{ $rule_calculation_point->day . ' ' . date('F', mktime(0, 0, 0, $rule_calculation_point->month, 10)) . ' ' . $rule_calculation_point->year }}
                                @endif
                            @else
                                {{ 'Setiap Saat' }}
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $rule_calculation_point->description ?? '-' }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Diperbarui Oleh</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $rule_calculation_point->updatedBy->name }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Diperbarui Pada</label>
                        <div class="col-sm-9 col-form-label">
                            {{ date('d F Y H:i:s', strtotime($rule_calculation_point->updated_at)) }}
                        </div>
                    </div>
                    <div class="float-right mt-3">
                        <a href="{{ route('rule-calculation-point.index') }}" class="btn btn-sm btn-danger rounded-5">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <!-- az-dashboard-one-title -->
            </div>
            <!-- az-content-body -->
        </div>
    </div>
@endsection
