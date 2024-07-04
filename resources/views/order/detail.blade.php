@extends('layouts.main')
@section('content')
    <div class="az-content mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Detail Order</h4>
                </div>
                <div class="py-1">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tipe Order</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $order->type == 0 ? 'Tambah Order' : 'Penukaran Point' }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Customer</label>
                        <div class="col-sm-9 col-form-label">
                            <a target="_blank"
                                href="{{ route('customer.show', ['id' => $order->customer_id]) }}">{{ $order->customer->name }} <i class="typcn typcn-arrow-forward"></i></a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9 col-form-label">
                            {{ date('d F Y H:i:s', strtotime($order->created_at)) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Diperbarui Oleh</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $order->updatedBy->name }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Diperbarui Pada</label>
                        <div class="col-sm-9 col-form-label">
                            {{ date('d F Y H:i:s', strtotime($order->updated_at)) }}
                        </div>
                    </div>
                    <div class="table-responsive mt-5 mb-3">
                        @include('order.includes.table.detail.' . $order->type)
                    </div>
                    <div class="float-right mt-3">
                        <a href="{{ route('order.index') }}" class="btn btn-sm btn-danger rounded-5">
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
