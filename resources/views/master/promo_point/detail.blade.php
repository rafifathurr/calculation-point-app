@extends('layouts.main')
@section('content')
    <div class="az-content mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Detail Promo Point</h4>
                </div>
                <div class="py-1">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $promo_point->name }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Menu</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $promo_point->menu->name }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9 col-form-label">
                            @if ($promo_point->status == 0)
                                <span class="badge badge-danger p-1 px-3 rounded-pill">Tidak Aktif</span>
                            @else
                                <span class="badge badge-success p-1 px-3 rounded-pill">Aktif</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Point</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $promo_point->point }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Qty</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $promo_point->qty }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Durasi Promo</label>
                        <div class="col-sm-9 col-form-label">
                            {{ date('d F Y', strtotime($promo_point->start_on)) . ' - ' . date('d F Y', strtotime($promo_point->expired_on)) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Foto</label>
                        <div class="col-sm-9 col-form-label">
                            <img width="50%" src="{{ asset($promo_point->attachment) }}" alt=""
                                class="rounded-5 border border-1-default">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $promo_point->description ?? '-' }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Diperbarui Oleh</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $promo_point->updatedBy->name }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Diperbarui Pada</label>
                        <div class="col-sm-9 col-form-label">
                            {{ date('d F Y H:i:s', strtotime($promo_point->updated_at)) }}
                        </div>
                    </div>
                    <div class="float-right mt-3">
                        <a href="{{ route('promo-point.index') }}" class="btn btn-sm btn-danger rounded-5">
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
