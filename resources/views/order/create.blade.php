@extends('layouts.main')
@section('content')
    <div class="az-content mb-5">
        <div class="px-5">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">{{ $title }}</h4>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-7">
                        <div class="card rounded-5">
                            <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    <div class="p-0">
                                        <div class="input-group w-100 mx-auto d-flex">
                                            <input type="text" id="search" oninput="catalogue()"
                                                class="form-control p-3" placeholder="Cari Menu..."
                                                aria-describedby="search-icon-1">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div>
                                    <div class="d-none" id="process">
                                        <div class="row g-4 mt-5">
                                            <div class="col-md-12 text-center">
                                                <p class="font-weight-bold">Mohon Tunggu...</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-none" id="customer_none">
                                        <div class="row g-4 mt-5">
                                            <div class="col-md-12 text-center">
                                                <p class="font-weight-bold">Harap Pilih Customer Terlebih Dahulu</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="catalogue">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="card rounded-5">
                            <form class="forms-sample pb-3" method="post" action="{{ route('order.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="type" id="type" value="{{ $type }}">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="customer_phone">Nomor Telepon Customer <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="customer_phone" onchange="customerCheck()"
                                            required>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    @if (!is_null(old('customer')) && old('customer') == $customer->id) selected @endif>
                                                    {{ $customer->phone }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="customer">Nama Customer <span class="text-danger">*</span></label>
                                        <select class="form-control" id="customer" name="customer" required readonly>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    @if (!is_null(old('customer')) && old('customer') == $customer->id) selected @endif>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($type == 0)
                                        <div class="form-group">
                                            <label for="total_percentage">Persentase Point</label>
                                            <input type="number" class="form-control" id="total_percentage"
                                                value="{{ $total_percentage }}" readonly>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="total_percentage">Customer Point</label>
                                            <input type="number" class="form-control" id="customer_point"
                                                value="{{ old('customer_point') }}" readonly>
                                        </div>
                                    @endif
                                    <div class="table-responsive mt-5">
                                        @include('order.includes.table.create.' . $type)
                                    </div>
                                    <div class="float-right my-3">
                                        <a href="{{ route('order.index') }}" class="btn btn-sm rounded-5 btn-danger">
                                            <i class="fas fa-arrow-left"></i>
                                            Kembali
                                        </a>
                                        <button type="submit" class="btn btn-sm btn-primary rounded-5 mx-2">
                                            Simpan
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- az-content-body -->
        </div>
    </div>
    @push('js-bottom')
        @include('js.order')
        <script>
            $('#customer_phone').val('').change();
            $('#customer').val('').change();
            catalogue();
        </script>
    @endpush
@endsection
