@extends('layouts.main')
@section('content')
    <div class="az-content mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Tambah Menu</h4>
                </div>
                <form class="forms-sample" method="post" action="{{ route('menu.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Menu"
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input type="number" class="form-control" id="price" name="price" min="0"
                                placeholder="Harga Menu" value="{{ old('price') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="attachment">Foto <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="attachment" name="attachment"
                            value="{{ old('attachment') }}" accept="image/jpeg,image/jpg,image/png" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" name="description" id="description" cols="10" rows="3"
                            placeholder="Deskripsi">{{ old('description') }}</textarea>
                    </div>
                    <div class="float-right mt-3">
                        <a href="{{ route('menu.index') }}" class="btn btn-sm rounded-5 btn-danger">
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
