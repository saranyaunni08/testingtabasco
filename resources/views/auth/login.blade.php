@extends('layouts.auth')

@section('content')
<style>
    .page-header {
        background-image: url('{{ asset('img/tabasco-login11.jpg') }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed; /* Optional for parallax effect */
    }

    /* Set custom color for the card header and button */
    .bg-custom {
        background-color: #1c9dac !important;
    }

    .btn-custom {
        background-color: #1c9dac !important;
        color: white !important; /* Set button text color to white */
    }

</style>
<main class="main-content mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('{{ asset('img/jpeg-optimizer_tabasco-login11.jpg') }}');">
        <span class="mask bg-gradient-dark opacity-1"></span>
        <div class="container my-auto">
            <div class="row">
                <div class="col-lg-4 col-md-8 col-12 mx-auto">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-custom shadow-info border-radius-lg py-3 pe-1">
                                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">ADMIN LOGIN</h4>
                                <div class="row mt-3">
                                    <div class="col-12 text-center ms-auto">
                                        <h5 class="text-white">Tabasco</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form role="form" class="text-start" action="{{ route('dologin') }}" method="POST">
                                @csrf
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="form-check form-switch d-flex align-items-center mb-3">
                                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" checked>
                                    <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-custom w-100 my-4 mb-2">Login</button>
                                </div>
                                <p class="mt-4 text-sm text-center">
                                    Forgot your password?
                                    <a href="{{ route('password.request') }}" class="text-info text-gradient font-weight-bold">Reset Password</a>
                                </p>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
