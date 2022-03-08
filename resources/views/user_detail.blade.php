@extends('layouts.layout')
@section('title', 'Profile Detail')
@section('specific page vendor css')
	<!-- Specific Page Vendor CSS -->
	<link rel="stylesheet" href="{{asset('assets/vendor/select2/css/select2.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/pnotify/pnotify.custom.css')}}" />
@endsection


@section('content body')
	<section role="main" class="content-body">
		<!-- start: page -->

		<div class="row pt-4">
			<div class="col-lg-6">
				<section class="card full-height">
					<header class="card-header">
						<div class="card-actions">
							<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						</div>
						<h2 class="card-title">PROFILE DETAILS</h2>
					</header>

					<div id="profile-container" class="card-body" user-id="{{$user->id}}">
						<div class="row">
							<div class="col-md-4 col-sm-12">
								<div class="profile-picture" style="width: 100%;height: 100%;aspect-ratio: 1;">
									@if($user->avatar != null)
										<img style="width: 100%; height: 100%;" src="{{$user->avatar}}" alt="User Avatar" class="rounded-circle" data-lock-picture="img/!logged-user.jpg" />
									@else
										<img style="width: 100%; height: 100%;" src="{{asset('assets/img/avatars/avatar.png')}}" alt="User Avatar" class="rounded-circle" data-lock-picture="img/!logged-user.jpg" />
									@endif
								</div>
							</div>
							<div class="col-md-6 col-sm-12">
								<div class="profile-account vertical-center">
									<h3 class="name font-weight-semibold">{{$user->firstname}} {{$user->lastname}}</h3>
									<a href="#" class="account">{{$user->email}}</a>
								</div>
							</div>
							<div class="col-md-2 col-sm-12">
								@if($user->isActive == 1)
									<div class="vertical-center">Active</div>
								@else
									<div class="vertical-center">Blocked</div>
								@endif
							</div>
						</div>
						<div class="row" style="margin-top: 20px;">
							<div class="col-sm-4">
								<h5>REGISTERED DATE:</h5>
							</div>
							<div class="col-sm-8">
								<h5 style="font-weight: bold">
									{{date('Y-m-d', strtotime($user->created_at))}}
								</h5>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<h5>EMAIL:</h5>
							</div>
							<div class="col-sm-8">
								<h5 style="font-weight: bold">
									{{$user->email}}
								</h5>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<h5>VERIFIED REPORTED:</h5>
							</div>
							<div class="col-sm-8">
								<h5 style="font-weight: bold">
									@if($user->isReporter == 1)
										Verified
									@else
										Unverified
									@endif
								</h5>
							</div>
						</div>
						<div class="row" style="margin-top: 20px;">
							<div class="col-sm-6" style="text-align: center;">
								<a href="{{url('/posts/'.$user->id)}}">
									<button type="button" class="mb-1 mt-1 mr-1 btn btn-success"
											style="width: 70%;">VIEW POSTS</button>
								</a>
							</div>
							<div class="col-sm-6" style="text-align: center;">
								@if($user->isActive == 1)
									<a id="user_active_btn" href="#block_modal" class="mb-1 mt-1 mr-1 btn btn-danger"
											style="width: 70%;">BLOCK</a>

								@else
									<a id="user_active_btn" href="#unblock_modal" class="mb-1 mt-1 mr-1 btn btn-warning"
									   style="width: 70%;">UNBLOCK</a>
								@endif

									<div id="block_modal" class="modal-block modal-block-primary mfp-hide">
										<section class="card">
											<header class="card-header">
												<h2 class="card-title">Are you sure?</h2>
											</header>
											<div class="card-body">
												<div class="modal-wrapper">
													<div class="modal-icon">
														<i class="fa fa-question-circle"></i>
													</div>
													<div class="modal-text">
														<p class="mb-0">Are you sure that you want to block this user?</p>
													</div>
												</div>
											</div>
											<footer class="card-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														<a onclick="event.preventDefault();
                                                     		document.getElementById('block-form').submit();">
															<button id="user_active_btn" type="button" class="mb-1 mt-1 mr-1 btn btn-danger"
																	>BLOCK</button>
														</a>
														<button class="btn btn-default modal-dismiss">Cancel</button>
													</div>
												</div>
											</footer>
										</section>
									</div>

									<div id="unblock_modal" class="modal-block modal-block-primary mfp-hide">
										<section class="card">
											<header class="card-header">
												<h2 class="card-title">Are you sure?</h2>
											</header>
											<div class="card-body">
												<div class="modal-wrapper">
													<div class="modal-icon">
														<i class="fa fa-question-circle"></i>
													</div>
													<div class="modal-text">
														<p class="mb-0">Are you sure that you want to unblock this user?</p>
													</div>
												</div>
											</div>
											<footer class="card-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														<a onclick="event.preventDefault();
                                                     		document.getElementById('block-form').submit();">
															<button id="user_active_btn" type="button" class="mb-1 mt-1 mr-1 btn btn-warning"
																	>UNBLOCK</button>
														</a>
														<button class="btn btn-default modal-dismiss">Cancel</button>
													</div>
												</div>
											</footer>
										</section>
									</div>

								<form id="block-form" action="{{ route('user_active_manage') }}" method="POST" style="display: none;">
									@csrf
									<input name="user_id" value="{{$user->id}}">
								</form>
							</div>
						</div>
					</div>
				</section>
			</div>
			<div class="col-lg-6">
				<section class="card full-height">
					<header class="card-header">
						<div class="card-actions">
							<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						</div>

						<h2 class="card-title">WALLET BALANCE</h2>
					</header>
					<div class="card-body">
						<h1 style="text-align: center;" class="vh-center">$71053</h1>
					</div>
				</section>
			</div>
		</div>
	</section>
@endsection


@section('specific page vendor js')
	<script src="{{asset('assets/vendor/select2/js/select2.js')}}"></script>
	<script src="{{asset('assets/vendor/pnotify/pnotify.custom.js')}}"></script>

@endsection

@section('page js')
	<script src="{{asset('assets/js/modals.js')}}"></script>
	<script src="{{asset('assets/js/profilemanage.js')}}"></script>
@endsection
