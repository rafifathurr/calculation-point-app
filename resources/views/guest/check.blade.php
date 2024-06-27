@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Customer Check Point</h4>
                </div>
                <div class="card rounded-5">
                    <div class="card-header bg-white">
                        <div class="input-group p-2 my-auto">
                            <input type="number" class="form-control" id="phone" name="phone" min="0" minlength="12" maxlength="13"
                                placeholder="Masukan Nomor Telepon..." value="">
                            <button type="button" class="input-group-text bg-primary text-white" onclick="getData()"><i class="typcn typcn-zoom-outline "
                                    style="font-size:x-large"></i></button>
                        </div>
                    </div>
                    <div class="content">
                    </div>
                </div>
                <!-- az-dashboard-one-title -->
            </div>
            <!-- az-content-body -->
        </div>
    </div>
    @push('js-bottom')
        @include('js.home')
    @endpush
@endsection
