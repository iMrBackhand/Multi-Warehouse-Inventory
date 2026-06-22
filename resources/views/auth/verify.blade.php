<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8" />
        <title>Verification Page</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc."/>
        <meta name="author" content="Zoyothemes"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">

        <!-- App css -->
        <link href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

        <!-- Icons -->
        <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('backend/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">

    </head>

    <body class="bg-white">
        <!-- Begin page -->
        <div class="account-page">
            <div class="container-fluid p-0">
                <div class="row align-items-center g-0">
                    <div class="col-xl-5">
                        <div class="row">
                            <div class="col-md-7 mx-auto">
                                <div class="mb-0 border-0 p-md-5 p-lg-0 p-4">
                                    <div class="mb-4 p-0">
                                        <a href="index.html" class="auth-logo">
                                            <img src="{{ asset('backend/assets/images/logo-dark.png') }}" alt="logo-dark" class="mx-auto" height="28" />
                                        </a>
                                    </div>

                                    <div class="pt-0">
                                        @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                        @endif

                                        @if ($errors->any())
                                            <div class="alert alert-danger mt-3">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <form action="{{ route('custom.verification.verify') }}" method="POST" class="my-4">
                                            @csrf
                                            @if (session('error'))
                                                <div class="alert alert-danger">
                                                    {{ session('error') }}
                                                </div>

                                            @endif
                                            <div class="form-group mb-3">
                                                <label for="emailaddress" class="form-label">Verification Code</label>
                                                <input class="form-control" name="code" type="text" id="code"  placeholder="Enter your code">

                                                {{-- <p class="text-muted mt-1">
                                                    Code expires in 1 minute.
                                                </p> --}}
                                                @error('code')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            {{-- <div class="form-group mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input class="form-control" name="password" type="password"  id="password" placeholder="Enter your password">
                                                @error('password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div> --}}

                                            {{-- <div class="form-group d-flex mb-3">
                                                <div class="col-sm-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="checkbox-signin" checked>
                                                        <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 text-end">
                                                    <a class='text-muted fs-14' href="{{ route('password.request') }}">Forgot password?</a>
                                                </div>
                                            </div> --}}

                                            <div class="form-group mb-0 row">
                                                <div class="col-12">
                                                    <div class="d-grid">
                                                        <button class="btn btn-primary" type="submit"> Verify </button>
                                                    </div>
                                                </div>
                                            </div>


                                        </form>
                                                                                    <div class="mt-2 d-flex justify-content-between align-items-center">

                                                <form action="{{ route('admin.resend.otp') }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link p-0">
                                                        Resend Code
                                                    </button>
                                                </form>

                                                <a class="text-muted fs-14" href="{{ route('password.request') }}">
                                                    Forgot password?
                                                </a>

                                            </div>

                                        <hr>

                                        <div class="text-center text-muted mb-4">
                                            <p class="mb-0">Don't have an account ?<a class='text-primary ms-2 fw-medium' href="{{ route('register') }}">Register</a></p>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7">
                        <div class="account-page-bg p-md-5 p-4">
                            <div class="text-center">
                                <h3 class="text-dark mb-3 pera-title">Quick, Effective, and Productive With Tapeli Admin Dashboard</h3>
                                <div class="auth-image">
                                    <img src="{{ asset('backend/assets/images/authentication.svg') }}" class="mx-auto img-fluid"  alt="images">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- END wrapper -->
             <!-- Vendor -->
        <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/feather-icons/feather.min.js') }}"></script>

        <!-- Apexcharts JS -->
        <script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

        <!-- for basic area chart -->
        <script src="https://apexcharts.com/samples/assets/stock-prices.js"></script>

        <!-- Widgets Init Js -->
        <script src="{{ asset('backend/assets/js/pages/analytics-dashboard.init.js') }}"></script>

        <!-- App js-->
        <script src="{{ asset('backend/assets/js/app.js') }}"></script>
        <!-- DataTables core -->
        <script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>

        <!-- Bootstrap 5 integration -->
        <script src="{{ asset('backend/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('backend/assets/js/pages/datatable.init.js') }}"></script>
        <!-- Vendor -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="assets/libs/jquery.counterup/jquery.counterup.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>

        <!-- App js-->
        <script src="assets/js/app.js"></script>

    </body>
</html>
