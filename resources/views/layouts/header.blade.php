<div class="az-header">
    <div class="container">
      <div class="az-header-left">
        <a href="{{ url('/') }}" class="az-logo"><img src="{{ asset('img/yasaka-icon-horizontal.png') }}" width="20%" alt=""></a>
        <a href="" id="azMenuShow" class="az-header-menu-icon d-lg-none"
          ><span></span
        ></a>
      </div>
      <!-- az-header-left -->
      <div class="az-header-menu">
        <div class="az-header-menu-header">
          <a href="{{ url('/') }}" class="az-logo"><img src="{{ asset('img/yasaka-icon-horizontal.png') }}" width="30%" alt=""></a>
          <a href="" class="close">&times;</a>
        </div>
        <!-- az-header-menu-header -->
        <ul class="nav">
          <li class="nav-item @if (Route::currentRouteName() == 'home') active show @endif">
            <a href="{{ url('/') }}" class="nav-link"
              ><i class="typcn typcn-home"></i> Home</a
            >
          </li>
          <li class="nav-item">
            <a href="" class="nav-link with-sub"
              ><i class="typcn typcn-document"></i> Pages</a
            >
            <nav class="az-menu-sub">
              <a href="page-signin.html" class="nav-link">Sign In</a>
              <a href="page-signup.html" class="nav-link">Sign Up</a>
            </nav>
          </li>
          <li class="nav-item">
            <a href="chart-chartjs.html" class="nav-link"
              ><i class="typcn typcn-chart-bar-outline"></i> Charts</a
            >
          </li>
          <li class="nav-item">
            <a href="form-elements.html" class="nav-link"
              ><i class="typcn typcn-chart-bar-outline"></i> Forms</a
            >
          </li>
          <li class="nav-item">
            <a href="" class="nav-link with-sub"
              ><i class="typcn typcn-book"></i> Components</a
            >
            <div class="az-menu-sub">
              <div class="container">
                <div>
                  <nav class="nav">
                    <a href="elem-buttons.html" class="nav-link">Buttons</a>
                    <a href="elem-dropdown.html" class="nav-link">Dropdown</a>
                    <a href="elem-icons.html" class="nav-link">Icons</a>
                    <a href="table-basic.html" class="nav-link">Table</a>
                  </nav>
                </div>
              </div>
              <!-- container -->
            </div>
          </li>
        </ul>
      </div>
      <!-- az-header-menu -->
      <div class="az-header-right">
        <!-- az-header-notification -->
        <div class="dropdown az-profile-menu">
          <a href="" class="az-img-user"
            ><img src="{{ asset('img/admin.png') }}" alt=""
          /></a>
          <div class="dropdown-menu">
            <div class="az-dropdown-header d-sm-none">
              <a href="" class="az-header-arrow"
                ><i class="icon ion-md-arrow-back"></i
              ></a>
            </div>
            <div class="az-header-profile">
              <div class="az-img-user">
                <img src="{{ asset('img/admin.png') }}" alt="" />
              </div>
              <!-- az-img-user -->
              <h6>Aziana Pechon</h6>
              <span>Premium Member</span>
            </div>
            <!-- az-header-profile -->

            <a href="{{ route('signout') }}" class="dropdown-item"
              ><i class="typcn typcn-power-outline"></i> Sign Out</a
            >
          </div>
          <!-- dropdown-menu -->
        </div>
      </div>
      <!-- az-header-right -->
    </div>
    <!-- container -->
  </div>