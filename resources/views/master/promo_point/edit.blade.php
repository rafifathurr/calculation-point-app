@extends('layouts.main')
@section('content')
    <div class="az-content mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Ubah Promo Point</h4>
                </div>
                <form class="forms-sample" method="post"
                    action="{{ route('promo-point.update', ['id' => $promo_point->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Promo"
                            value="{{ $promo_point->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="menu">Menu <span class="text-danger">*</span></label>
                        <select class="form-control" id="menu" name="menu" required>
                            <option disabled hidden selected>Pilih Menu</option>
                            @foreach ($menus as $menu)
                                <option value="{{ $menu->id }}" @if ($promo_point->menu_id == $menu->id) selected @endif>
                                    {{ $menu->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="status" name="status" required>
                            <option disabled hidden selected>Pilih Status</option>
                            <option value="0" @if ($promo_point->status == 0) selected @endif>Tidak Aktif</option>
                            <option value="1" @if ($promo_point->status == 1) selected @endif>Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="point">Point <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="point" name="point" min="0"
                            placeholder="Nilai Point" value="{{ $promo_point->point }}" required>
                    </div>
                    <div class="form-group">
                        <label for="qty">Qty <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="qty" name="qty" min="0"
                            placeholder="Jumlah" value="{{ $promo_point->qty }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_on">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_on" name="start_on"
                                    min="{{ date('Y-m-d') }}" value="{{ $promo_point->start_on }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expired_on">Tanggal Berakhir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="expired_on" name="expired_on"
                                    min="{{ date('Y-m-d') }}" value="{{ $promo_point->expired_on }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="attachment">Foto <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="attachment" name="attachment"
                            accept="image/jpeg,image/jpg,image/png" required>
                        <label class="mt-2"><a target="_blank" href="{{ asset($promo_point->attachment) }}"><i
                                    class="typcn typcn-download"></i>
                                Lampiran Foto</a></label>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" name="description" id="description" cols="10" rows="3"
                            placeholder="Deskripsi">{{ $promo_point->description }}</textarea>
                    </div>
                    <div class="float-right mt-3">
                        <a href="{{ route('promo-point.index') }}" class="btn btn-sm rounded-5 btn-danger">
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
        @include('js.promo_point')
    @endpush
@endsection
