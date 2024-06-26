@extends('layouts.main')
@section('content')
    <div class="az-content mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Ubah Customer</h4>
                </div>
                <form class="forms-sample" method="post" action="{{ route('customer.update', ['id' => $customer->id]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Customer"
                            value="{{ $customer->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Nomor Telepon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="phone" name="phone" min="0"
                            placeholder="Nomor Telepon" value="{{ $customer->phone }}" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea class="form-control" name="address" id="address" cols="10" rows="3"
                            placeholder="Alamat">{{ $customer->address }}</textarea>
                    </div>
                    <div class="float-right mt-3">
                        <a href="{{ route('customer.index') }}" class="btn btn-sm rounded-5 btn-danger">
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
        @include('js.customer')
    @endpush
@endsection
