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
		<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/bootstrap.css')}}" />
		<link rel="stylesheet" href="{{asset('assets/vendor/animate/animate.css')}}">

		<link rel="stylesheet" href="{{asset('assets/vendor/font-awesome/css/font-awesome.css')}}" />
		<link rel="stylesheet" href="{{asset('assets/vendor/magnific-popup/magnific-popup.css')}}" />
		<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css')}}" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="{{asset('assets/css/theme.css')}}" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="{{asset('assets/css/skins/default.css')}}" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

		<!-- Head Libs -->
		<script src="{{asset('assets/vendor/modernizr/modernizr.js')}}"></script>

	</head>
	<body>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<div class="panel card-sign">
					{{--<div class="card-title-sign mt-3 text-right">--}}
						{{--<h2 class="title text-uppercase font-weight-bold m-0"><i class="fa fa-user mr-1"></i> Sign In</h2>--}}
					{{--</div>--}}
					<img class="logo-image" src="{{asset('assets/img/logo.png')}}" height="100" alt="Overheard Admin" />
					<!--<div class="alternative-font logo-title">OVERHEARD, LLC</div>-->
					<div class="logo-title" style="color:#0088cc">OVERHEARD, LLC</div>
					<div class="card-body">
						<form action="{{ route('login') }}" method="post">
							@csrf
							<div class="form-group mb-3">
								<label>Email</label>
								<div class="input-group input-group-icon">
									<input name="email" type="text" class="form-control form-control-lg @error('email') is-invalid @enderror"
										   value="{{ old('email') }}" required autocomplete="email" autofocus/>
									@error('email')
									<div class="invalid-feedback" role="alert">
                                        {{--<strong>{{ $message }}</strong>--}}
                                    </div>
									@enderror
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-user"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="form-group mb-3">
								<div class="clearfix">
									<label class="float-left">Password</label>
									{{--<a href="pages-recover-password" class="float-right">Lost Password?</a>--}}
									@if (Route::has('password.request'))
										<a class="float-right" href="{{ route('password.request') }}">
											{{ __('Forgot Your Password?') }}
										</a>
									@endif
								</div>
								<div class="input-group input-group-icon">
									<input name="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
										   required autocomplete="current-password"/>

									@error('password')
									<span class="invalid-feedback" role="alert">
                                        {{--<strong>{{ $message }}</strong>--}}
                                    </span>
									@enderror
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-8">
									<div class="checkbox-custom checkbox-default">
										<input id="RememberMe" name="rememberme" type="checkbox"/>
										<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
										<label class="form-check-label" for="remember">
											{{ __('Remember Me') }}
										</label>
									</div>
								</div>
								<div class="col-sm-4 text-right">
									<button type="submit" class="btn btn-primary mt-2">Sign In</button>
								</div>
							</div>
							<!--<span class="mt-3 mb-3 line-thru text-center text-uppercase">
								<span>or</span>
							</span>

							<p class="text-center">Don't have an account yet? <a href="{{ route('register') }}">Sign Up!</a></p>-->

							<div class="row">
								<div class="col-sm-4" style="text-align: center;">
									<a href="{{route('privacypolicy')}}">Privacy Policy</a>
								</div>
								<div class="col-sm-4" style="text-align: center;">
									<a href="{{route('terms')}}">Terms of service</a>
								</div>
								<div class="col-sm-4" style="text-align: center;">
									<a href="{{route('aboutus')}}">About us</a>
								</div>
							</div>

						</form>
					</div>
				</div>

				<p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2020. All Rights Reserved.</p>
				<h4 class="text-center" style="color: #0088cc">CONTACT US</h4>
				<h5 class="text-center" style="color: #0088cc">TEL: +1 (231) 709-7088</h5>
				<h5 class="text-center" style="color: #0088cc">E-MAIL: info@overheard.net</h5>
				<h5 class="text-center" style="color: #0088cc">ADDRESS: 613 W EIGHTH ST, Traverse City MI 49684</h5>
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
		<script>
            const myArray = [1, 2, 5, 6, 19];
            for (var i = 0; i < myArray.length; i++) {
                setTimeout(function() {
                    console.log('Index: ' + i + ', element: ' + myArray[i]);
                }, 3000);
            }
		</script>
	</body>
</html>