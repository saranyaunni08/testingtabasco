<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <link rel="stylesheet" type="text/css"
      href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="{{ asset('css/material-dashboard.css?v=3.1.0') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @yield('pagestyles')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.0/css/boxicons.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="{{ asset('path/to/now-ui-icons.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  



  
    <title>{{ $title }}</title>
    
  </head>
<body class="g-sidenav-show bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header text-center">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#" target="_blank">
        <span class="ms-1 font-weight-bold text-white" style="text-transform:capitalize">{{ $building->building_name ?? 'Tabasco' }}</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">

        <li class="nav-item" id="dashboardMenu">
          <a class="nav-link text-white"
             href="{{ route('admin.dashboard') }}">
              <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="material-icons opacity-10">dashboard</i>
              </div>
              <span class="nav-link-text ms-1">Dashboard</span>
          </a>
      </li>
     
        <li class="nav-item" id="dashboardMenu">
          <a class="nav-link text-white" href="{{ route('admin.buildingdashboard') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">home</i>
            </div>
            <span class="nav-link-text ms-1">Building Dashboard</span>
          </a>
        </li>

        
        @if(isset($buildings))
        @foreach($buildings as $b)
          @if(request()->routeIs('admin.rooms.index') || request()->routeIs('admin.flats.index') || request()->routeIs('admin.shops.index') || request()->routeIs('admin.table-spaces.index') || request()->routeIs('admin.kiosks.index') || request()->routeIs('admin.chair-spaces.index'))
            <li class="nav-item">
              <a class="nav-link text-white {{ $page == 'building-'.$b->id ? 'active bg-gradient-info' : '' }}"
                 href="{{ route('admin.rooms.index', ['building_id' => $b->id]) }}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="material-icons opacity-10">location_city</i>
                </div>
                <span class="nav-link-text ms-1" style="text-transform: capitalize">{{ $b->building_name }}</span>
              </a>
            </li>
          @endif
        @endforeach
      @endif
          
    
        @if(isset($rooms))
        <li class="nav-item">
          <a class="nav-link text-white {{ $page == 'flats' ? 'active bg-gradient-info' : '' }}"
          href="{{ route('admin.flats.index', ['building_id' => $building->id]) }}">
           <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">apartment</i>
          </div>
           <span class="nav-link-text ms-1">Flats</span>
       </a>
       

      </li>
      <li class="nav-item">
          <a class="nav-link text-white {{ $page == 'Shops' ? 'active bg-gradient-info' : '' }}"
             href="{{ route('admin.shops.index', ['building_id' => $building->id ?? 0]) }}">
              <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="material-icons opacity-10">store</i>
              </div>
              <span class="nav-link-text ms-1">Shops</span>
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link text-white {{ $page == 'table-spaces' ? 'active bg-gradient-info' : '' }}"
             href="{{ route('admin.table-spaces.index', ['building_id' => $building->id ?? 0]) }}">
              <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="material-icons opacity-10">table_chart</i>
              </div>
              <span class="nav-link-text ms-1">Table Spaces</span>
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link text-white {{ $page == 'Kiosks' ? 'active bg-gradient-info' : '' }}"
             href="{{ route('admin.kiosks.index', ['building_id' => $building->id ?? 0]) }}">
              <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">storefront</i> <!-- Kiosk-like icon -->
              </div>
              <span class="nav-link-text ms-1">Kiosks</span>
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link text-white {{ $page == 'chair-spaces' ? 'active bg-gradient-info' : '' }}"
             href="{{ route('admin.chair-spaces.index', ['building_id' => $building->id ?? 0]) }}">
              <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="material-icons opacity-10">event_seat</i>
              </div>
              <span class="nav-link-text ms-1">Chair Spaces</span>
          </a>
      </li>
      <li class="nav-item" id="totalCustomersMenu">
          <a class="nav-link text-white {{ $page == 'total-customers' ? 'active bg-gradient-info' : '' }}"
             href="{{ route('admin.customers.total_customers', ['building' => $building->id ?? 0]) }}">
              <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="material-icons opacity-10">people</i>
              </div>
              <span class="nav-link-text ms-1">Total Customers</span>
          </a>
      </li>

      <li class="nav-item" id="flatDifferencesMenu">
        <a class="nav-link text-white {{ $page == 'building-'.$building->id ? 'active bg-gradient-info' : '' }}"
           href="{{ route('admin.flats.difference', ['building_id' =>  $building->id]) }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">apartment</i>
            </div>
            <span class="nav-link-text ms-1">Flat Differences</span>
        </a>
      </li>
      

      <li class="nav-item" id="shopsDifferencesMenu">
        <a class="nav-link text-white {{ $page == 'shops-difference' ? 'active bg-gradient-info' : '' }}"
           href="{{ route('admin.shops.difference', ['building_id' => $building->id ?? 0]) }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">store</i>
            </div>
            <span class="nav-link-text ms-1">Shops Differences</span>
        </a>
    </li>
    <li class="nav-item {{ $page == 'kiosks' ? 'active bg-gradient-info' : '' }}">
      <a class="nav-link" href="{{ route('admin.kiosks.index', ['building_id' =>  $building->id ?? 0]) }}">
        <i class="material-icons opacity-10">storefront</i> <!-- Kiosk-like icon -->
          <span class="nav-link-text">Kiosks Differences</span>
      </a>
  </li>
  <li class="nav-item {{ $page == 'chair-spaces' ? 'active bg-gradient-info' : '' }}">
      <a class="nav-link" href="{{ route('admin.chair-spaces.index', ['building_id' => $building->id ?? 0]) }}">
        <i class="material-icons opacity-10">event_seat</i>
        <span class="nav-link-text">Chair Spaces Differences</span>
      </a>
  </li>
  <li class="nav-item {{ $page == 'table-spaces' ? 'active bg-gradient-info' : '' }}">
      <a class="nav-link" href="{{ route('admin.table-spaces.index', ['building_id' => $building->id ?? 0]) }}">
        <i class="material-icons opacity-10">table_chart</i> <!-- Updated icon -->
          <span class="nav-link-text">Table Spaces Differences</span>
      </a>
  </li>

  <li class="nav-item {{ $page == 'cancelled-sales' ? 'active bg-gradient-info' : '' }}">
    <a class="nav-link" href="{{ route('admin.sales.cancelled') }}">
        <i class="material-icons opacity-10">cancel</i>
        <span class="nav-link-text">Cancelled Sales</span>
    </a>
</li>

        @endif 

        
    
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0">
      <div class="mx-3">
        <a class="btn bg-gradient-danger w-100" href="#" type="button">Logout</a>
      </div>
    </div>
  </aside>

  <div class="main-content position-relative max-height-vh-100 h-100">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb" class="pt-3">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Admin</a></li>
          </ol>
          <h6 class="font-weight-bolder mb-0">{{ $title }}</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <ul class="navbar-nav justify-content-end">
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    @yield('content')
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="{{ asset('js/core/popper.min.js') }}"></script>
  <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="{{ asset('js/material-dashboard.min.js?v=3.1.0') }}"></script>
</body>
</html>
