<!doctype html>
<html class="fixed">
<head>

    <!-- Basic -->
    <meta charset="UTF-8">

    <meta name="keywords" content="HTML5 Admin Template" />
    <meta name="description" content="Porto Admin - Responsive HTML5 Template">
    <meta name="author" content="okler.net">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Web Fonts  -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{asset('public/assets/vendor/bootstrap/css/bootstrap.css')}}" />
    <link rel="stylesheet" href="{{asset('public/assets/vendor/animate/animate.css')}}">

    <link rel="stylesheet" href="{{asset('public/assets/vendor/font-awesome/css/font-awesome.css')}}" />
    <link rel="stylesheet" href="{{asset('public/assets/vendor/magnific-popup/magnific-popup.css')}}" />
    <link rel="stylesheet" href="{{asset('public/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css')}}" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{asset('public/assets/css/theme.css')}}" />

    <!-- Skin CSS -->
    <link rel="stylesheet" href="{{asset('public/assets/css/skins/default.css')}}" />

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{asset('public/assets/css/custom.css')}}">

    <!-- Head Libs -->
    <script src="{{asset('assets/vendor/modernizr/modernizr.js')}}"></script>

</head>
<body>
    <!-- start: page -->
    <section class="body-sign">
        <div class="center-sign">
            <a href="/" class="logo float-left">
                <img src="{{asset('assets/img/logo.png')}}" height="54" alt="Overheard Admin" />
            </a>

            <div class="panel card-sign">
                <div class="card-title-sign mt-3 text-right">
                    <h2 class="title text-uppercase font-weight-bold m-0"><i class="fa fa-user mr-1"></i> Set Password </h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group mb-3">
                            <label>Email</label>
                            <input id="email" name="email" type="email" class="form-control form-control-lg  @error('email') is-invalid @enderror"
                                   value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus/>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label>Password</label>
                                    <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
										</span>
                                    @enderror
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label>Password Confirmation</label>
                                    <input id="password-confirm" type="password" class="form-control form-control-lg" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4 text-right">
                                <button type="submit" class="btn btn-primary mt-2">Set Password</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- end: page -->

    <!-- Vendor -->
    <script src="{{asset('assets/vendor/jquery/jquery.js')}}"></script>
    <script src="{{asset('assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js')}}"></script>
    <script src="{{asset('assets/vendor/popper/umd/popper.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('assets/vendor/common/common.js')}}"></script>
    <script src="{{asset('assets/vendor/nanoscroller/nanoscroller.js')}}"></script>
    <script src="{{asset('assets/vendor/magnific-popup/jquery.magnific-popup.js')}}"></script>
    <script src="{{asset('assets/vendor/jquery-placeholder/jquery-placeholder.js')}}"></script>

    <!-- Theme Base, Components and Settings -->
    <script src="{{asset('assets/js/theme.js')}}"></script>

    <!-- Theme Custom -->
    <script src="{{asset('assets/js/custom.js')}}"></script>

    <!-- Theme Initialization Files -->
    <script src="{{asset('assets/js/theme.init.js')}}"></script>

</body>
</html>
