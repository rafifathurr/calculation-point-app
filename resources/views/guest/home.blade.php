@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <div>
                        <h2 class="az-dashboard-title">Selamat Datang di Yasaka !</h2>
                    </div>
                </div>
                <!-- az-dashboard-one-title -->
                <div class="card rounded-5">
                    <div class="card-body">
                        <div class="p-2 py-3 mb-3">
                            <h2 class="az-dashboard-title">Promo Saat Ini</h2>
                        </div>
                        <div id="carouselExampleIndicators" class="carousel slide mx-5" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @foreach ($promo_point as $index => $promo)
                                    <li data-target="#carouselExampleIndicators" data-slide-to="{{ $index }}"
                                        @if ($index == 0) class="active" @endif></li>
                                @endforeach
                            </ol>
                            <div class="carousel-inner pb-3">
                                @foreach ($promo_point as $index => $promo)
                                    <div
                                        class="carousel-item rounded-5 border border-1 @if ($index == 0) active @endif">
                                        <div class="col-md-12">
                                            <div class="row justify-content-between">
                                                <img src="{{ asset($promo->attachment) }}" class="col-md-6 px-0"
                                                    width="50%" height="20%">
                                                <div class="col-md-6 px-lg-5 py-md-5 mt-sm-3 my-auto">
                                                    <h4 class="font-weight-bold">
                                                        {{ $promo->name }}
                                                    </h4>
                                                    <p class="mb-1">
                                                        {{ $promo->description }}
                                                    </p>
                                                    <p class="mb-3">
                                                        <small>
                                                            <i>
                                                                Periode
                                                                {{ date('d F Y', strtotime($promo->start_on)) . ' - ' . date('d F Y', strtotime($promo->expired_on)) }}
                                                            </i>
                                                        </small>
                                                    </p>
                                                    <p class="text-right h5"><b>Point :</b>
                                                        <span
                                                            class="bg-success px-2 py-1 rounded-5 text-white font-weight-bold">{{ $promo->point }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- az-content-body -->
        </div>
    </div>
    @push('js-bottom')
        @include('js.home')
    @endpush
@endsection
