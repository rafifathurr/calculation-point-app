<div class="az-header">
    <div class="container">
        <div class="az-header-left">
            <a href="{{ url('/') }}" class="az-logo"><img src="{{ asset('img/yasaka-icon-horizontal.png') }}"
                    width="20%" alt=""></a>
            <a href="" id="azMenuShow" class="az-header-menu-icon d-lg-none"><span></span></a>
        </div>
        <!-- az-header-left -->
        <div class="az-header-menu">
            <div class="az-header-menu-header">
                <a href="{{ url('/') }}" class="az-logo"><img src="{{ asset('img/yasaka-icon-horizontal.png') }}"
                        width="30%" alt=""></a>
                <a href="" class="close">&times;</a>
            </div>
            <!-- az-header-menu-header -->
            <ul class="nav">
                <li class="nav-item @if (Route::currentRouteName() == 'home') active show @endif">
                    <a href="{{ url('/') }}" class="nav-link"><i class="typcn typcn-home"></i> Home</a>
                </li>
                @if (Illuminate\Support\Facades\Auth::user()->hasRole('owner'))
                    <li class="nav-item">
                        <a href="chart-chartjs.html" class="nav-link"><i class="typcn typcn-chart-bar"></i>
                            Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link with-sub"><i class="typcn typcn-document"></i> Master</a>
                        <nav class="az-menu-sub">
                            <a href="{{ route('rule-calculation-point.index') }}" class="nav-link">Rule Point</a>
                            <a href="{{ route('menu.index') }}" class="nav-link">Menu</a>
                            <a href="page-signup.html" class="nav-link">Promo</a>
                        </nav>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user-management.index') }}" class="nav-link"><i class="typcn typcn-user"></i>
                            User Management</a>
                    </li>
                @elseif(Illuminate\Support\Facades\Auth::user()->hasRole('cashier'))
                    <li class="nav-item">
                        <a href="chart-chartjs.html" class="nav-link"><i class="typcn typcn-clipboard"></i>
                            Order</a>
                    </li>
                    <li class="nav-item">
                        <a href="chart-chartjs.html" class="nav-link"><i class="typcn typcn-ticket"></i>
                            Promo</a>
                    <li class="nav-item">
                        <a href="{{ route('customer.index') }}" class="nav-link"><i class="typcn typcn-user"></i>
                            Customer</a>
                    </li>
                @endif
            </ul>
        </div>
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

                    <a href="{{ route('signout') }}" class="dropdown-item"><i class="typcn typcn-power-outline"></i>
                        Sign Out</a>
                </div>
                <!-- dropdown-menu -->
            </div>
        </div>
        <!-- az-header-right -->
    </div>
    <!-- container -->
</div>
