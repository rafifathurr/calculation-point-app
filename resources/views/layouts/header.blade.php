<div class="az-header">
    <div class="container">
        <div class="az-header-left">
            <a href="{{ url('/') }}" class="az-logo"><img src="{{ asset('img/yasaka-icon-horizontal-new.png') }}"
                    width="25%" alt=""></a>
            <a href="" id="azMenuShow" class="az-header-menu-icon d-lg-none"><span></span></a>
        </div>
        <!-- az-header-left -->
        <div class="az-header-menu">
            <div class="az-header-menu-header">
                <a href="{{ url('/') }}" class="az-logo"><img src="{{ asset('img/yasaka-icon-horizontal-new.png') }}"
                        width="50%" alt=""></a>
                <a href="" class="close">&times;</a>
            </div>
            <!-- az-header-menu-header -->
            <ul class="nav">
                <li class="nav-item @if (Route::currentRouteName() == 'home' || Route::currentRouteName() == 'guest.home') active show @endif">
                    <a href="{{ url('/') }}" class="nav-link"><i class="typcn typcn-home"></i> Home</a>
                </li>
                @if (Illuminate\Support\Facades\Auth::check())
                    @if (Illuminate\Support\Facades\Auth::user()->hasRole('owner'))
                        <li class="nav-item @if (Route::currentRouteName() == 'dashboard.index') active show @endif">
                            <a href="{{ route('dashboard.index') }}" class="nav-link"><i
                                    class="typcn typcn-chart-bar"></i>
                                Dashboard</a>
                        </li>
                        <li class="nav-item @if (Route::currentRouteName() == 'order.index' || Route::currentRouteName() == 'customer.index') active show @endif">
                            <a href="" class="nav-link with-sub"><i class="typcn typcn-document"></i> Data
                                Order</a>
                            <nav class="az-menu-sub">
                                <a href="{{ route('order.index') }}" class="nav-link">Order</a>
                                <a href="{{ route('customer.index') }}" class="nav-link">Customer</a>
                            </nav>
                        </li>
                        <li class="nav-item @if (Route::currentRouteName() == 'rule-calculation-point.index' ||
                                Route::currentRouteName() == 'menu.index' ||
                                Route::currentRouteName() == 'point-grade.index' ||
                                Route::currentRouteName() == 'promo-point.index') active show @endif">
                            <a href="" class="nav-link with-sub"><i class="typcn typcn-document"></i> Master</a>
                            <nav class="az-menu-sub">
                                <a href="{{ route('point-grade.index') }}" class="nav-link">Point Grade</a>
                                <a href="{{ route('rule-calculation-point.index') }}" class="nav-link">Rule Point</a>
                                <a href="{{ route('menu.index') }}" class="nav-link">Menu</a>
                                <a href="{{ route('promo-point.index') }}" class="nav-link">Promo</a>
                            </nav>
                        </li>
                        <li class="nav-item @if (Route::currentRouteName() == 'user-management.index') active show @endif">
                            <a href="{{ route('user-management.index') }}" class="nav-link"><i
                                    class="typcn typcn-user"></i>
                                User Management</a>
                        </li>
                    @elseif(Illuminate\Support\Facades\Auth::user()->hasRole('cashier'))
                        <li class="nav-item @if (Route::currentRouteName() == 'order.index') active show @endif">
                            <a href="{{ route('order.index') }}" class="nav-link"><i class="typcn typcn-clipboard"></i>
                                Order</a>
                        </li>
                        <li class="nav-item @if (Route::currentRouteName() == 'promo-point.index') active show @endif">
                            <a href="{{ route('promo-point.index') }}" class="nav-link"><i
                                    class="typcn typcn-ticket"></i>
                                Promo</a>
                        </li>
                        <li class="nav-item @if (Route::currentRouteName() == 'customer.index') active show @endif">
                            <a href="{{ route('customer.index') }}" class="nav-link"><i class="typcn typcn-user"></i>
                                Customer</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item @if (Route::currentRouteName() == 'guest.check') active show @endif">
                        <a href="{{ route('guest.check') }}" class="nav-link"><i class="typcn typcn-clipboard"></i>
                            Check Point</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('signin') }}" class="nav-link"><i class="typcn typcn-user"></i>
                            Sign In</a>
                    </li>
                @endif
            </ul>
        </div>
        @if (Illuminate\Support\Facades\Auth::check())
            <!-- az-header-menu -->
            <div class="az-header-right">
                <!-- az-header-notification -->
                <div class="dropdown az-profile-menu">
                    <a href="" class="az-img-user"><img src="{{ asset('img/admin.png') }}" alt="" /></a>
                    <div class="dropdown-menu">
                        <div class="az-dropdown-header d-sm-none">
                            <a href="" class="az-header-arrow"><i class="icon ion-md-arrow-back"></i></a>
                        </div>
                        <div class="az-header-profile">
                            <div class="az-img-user">
                                <img src="{{ asset('img/admin.png') }}" alt="" />
                            </div>
                            <!-- az-img-user -->
                            <h6>{{ Auth::user()->name }}</h6>
                            @php
                                $exploded_raw_role = explode('-', Auth::user()->getRoleNames()[0]);
                                $user_role = ucwords(implode(' ', $exploded_raw_role));
                            @endphp
                            <span>{{ $user_role }}</span>
                        </div>
                        <!-- az-header-profile -->

                        <a href="{{ route('signout') }}" class="dropdown-item"><i
                                class="typcn typcn-power-outline"></i>
                            Sign Out</a>
                    </div>
                    <!-- dropdown-menu -->
                </div>
            </div>
        @endif
        <!-- az-header-right -->
    </div>
    <!-- container -->
</div>
