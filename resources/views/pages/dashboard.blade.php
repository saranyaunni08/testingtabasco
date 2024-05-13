@extends('layouts.default', ['title' => 'Dashboard', 'page' => 'dashboard'])
@section('content')
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
              <h6 class="text-white text-capitalize ps-3">Total Buildings</h6>
            </div>
          </div>
          <div class="card-body px-0 pb-2">
            <div class="row p-4">

            <!-- first row -->

              <div class="container-fluid mt-3">
                <div class="row">
                  <div class="col-lg-3 col-sm-6">
                    <div class="card gradient-1">
                      <a href="{{ route('admin.building') }}">
                      <div class="card-body">
                        <h3 class="card-title text-black">Building A</h3>
                        <div class="d-inline-block">
                          <p class="text-gray mb-0">Sold 25%
                            <div class="progress mb-2">
                              <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </p>
                          <p class="text-gray mb-0">Remaining 75%
                          <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          </p>
                        </div>
                      </div>
                      </a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                    <div class="card gradient-2">
                      <a href="">
                      <div class="card-body">
                        <h3 class="card-title text-black">Building B</h3>
                        <div class="d-inline-block">
                          <p class="text-gray mb-0">Sold 35%
                            <div class="progress mb-2">
                              <div class="progress-bar bg-success" role="progressbar" style="width: 35%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </p>
                          <p class="text-gray mb-0">Remaining 65%
                          <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          </p>
                        </div>
                      </div>
                      </a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                    <div class="card gradient-1">
                      <a href="">
                      <div class="card-body">
                        <h3 class="card-title text-black">Building C</h3>
                        <div class="d-inline-block">
                          <p class="text-gray mb-0">Sold 45%
                            <div class="progress mb-2">
                              <div class="progress-bar bg-success" role="progressbar" style="width: 45%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </p>
                          <p class="text-gray mb-0">Remaining 55%
                          <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 55%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          </p>
                        </div>
                      </div>
                      </a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                    <div class="card gradient-1">
                      <div class="card-body">
                        <h3 class="card-title text-black">Building D</h3>
                        <div class="d-inline-block">
                          <p class="text-gray mb-0">Sold 55%
                            <div class="progress mb-2">
                              <div class="progress-bar bg-success" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </p>
                          <p class="text-gray mb-0">Remaining 45%
                          <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 45%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

         <!-- second row -->

              <div class="container-fluid mt-3 mb-3">
                <div class="row">
                  <div class="col-lg-3 col-sm-6">
                    <div class="card gradient-1">
                      <div class="card-body">
                        <h3 class="card-title text-black">Building E</h3>
                        <div class="d-inline-block">
                          <p class="text-gray mb-0">Sold 65%
                            <div class="progress mb-2">
                              <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </p>
                          <p class="text-gray mb-0">Remaining 45%
                          <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 45%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                    <div class="card gradient-2">
                      <div class="card-body">
                        <h3 class="card-title text-black">Building F</h3>
                        <div class="d-inline-block">
                          <p class="text-gray mb-0">Sold 75%
                            <div class="progress mb-2">
                              <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </p>
                          <p class="text-gray mb-0">Remaining 25%
                          <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 25%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                    <div class="card gradient-1">
                      <div class="card-body">
                        <h3 class="card-title text-black">Building G</h3>
                        <div class="d-inline-block">
                          <p class="text-gray mb-0">Sold 85%
                            <div class="progress mb-2">
                              <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </p>
                          <p class="text-gray mb-0">Remaining 15%
                          <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 15%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                    <div class="card gradient-1">
                      <div class="card-body">
                        <h3 class="card-title text-black">Building H</h3>
                        <div class="d-inline-block">
                          <p class="text-gray mb-0">Sold 95%
                            <div class="progress mb-2">
                              <div class="progress-bar bg-success" role="progressbar" style="width: 95%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </p>
                          <p class="text-gray mb-0">Remaining 5%
                          <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 5%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
@endsection

@section('sidebar')
  @parent {{-- Include parent content from the default layout --}}
  {{-- Add room module link in the sidebar --}}
  <li class="nav-item">
    <a class="nav-link" href="{{ route('rooms.create') }}">
      <i class="fas fa-plus"></i>
      <span class="nav-link-text ms-1">Add Room</span>
    </a>
  </li>
@endsection

@section('sidebar')
  @parent {{-- Include parent content from the default layout --}}
  {{-- Add room module link in the sidebar --}}
  <li class="nav-item">
    <a class="nav-link" href="{{ route('rooms.index') }}"> <!-- Assuming 'rooms.index' is your route for the rooms index -->
      <i class="fas fa-list"></i> <!-- You can use an appropriate icon here -->
      <span class="nav-link-text ms-1">Rooms List</span>
    </a>
  </li>
@endsection
