@extends('layouts.layout')
@section('title', 'Report Detail')
@section('specific page vendor css')
	<!-- Specific Page Vendor CSS -->
	<link rel="stylesheet" href="{{asset('assets/vendor/select2/css/select2.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/pnotify/pnotify.custom.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/owl.carousel/assets/owl.carousel.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/owl.carousel/assets/owl.theme.default.css')}}" />
@endsection


@section('content body')
	<section role="main" class="content-body">
		<!-- start: page -->

		<div class="row pt-4">
			<div class="col-lg-12">
				<section class="card full-height">
					<header class="card-header">
						<h2 class="card-title">REPORT DETAILS</h2>
					</header>

					<div id="profile-container" class="card-body">
						<div class="row">
							<div class="col-xl-2">
								<figure class="image rounded">
									@if($reporter->avatar == null)
										<img style="width: 50px; height: 50px;" src="{{asset('assets/img/avatars/avatar.png')}}"
											 alt="Joseph Doe Junior" class="rounded-circle"/>
									@else
										<img style="width: 50px; height: 50px;" src="{{$reporter->avatar}}"
											 alt="Joseph Doe Junior" class="rounded-circle"/>
									@endif
								</figure>
							</div>
							<div class="col-xl-10" style="padding-top: 10px;">{{$reporter->firstname.' '.$reporter->lastname}}</div>


							<div class="card-body">
								<div class="scrollable" data-plugin-scrollable style="height: 100px;">
									<div class="scrollable-content">
										{{$report->content}}
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xl-2">
								<figure class="image rounded">
									@if($reported->avatar == null)
										<img style="width: 50px; height: 50px;" src="{{asset('assets/img/avatars/avatar.png')}}"
											 alt="Joseph Doe Junior" class="rounded-circle"/>
									@else
										<img style="width: 50px; height: 50px;" src="{{$reported->avatar}}"
											 alt="Joseph Doe Junior" class="rounded-circle"/>
									@endif
								</figure>
							</div>
							<div class="col-xl-10" style="padding-top: 10px;">{{$reported->firstname.' '.$reported->lastname}}</div>

							<div class="col-xl-3">
								<div class="owl-carousel owl-theme" data-plugin-carousel data-plugin-options='{"dots": false, "nav": true, "items": 1 }'>
									@php
										foreach ($post->attaches as $attach){
                                            $ext = pathinfo($attach->url, PATHINFO_EXTENSION);
                                            if(mb_strtolower($ext) == 'mp4' || mb_strtolower($ext) == 'avi'){
                                                echo '<video controls width="200" class="item img-thumbnail" style="height: 150px;"><source src="';
                                                echo $attach->url;
                                                echo '"></video>';
                                            }
                                            else{
                                                echo '<div class="item"><img class="img-thumbnail" style="height: 150px;" src="';
                                                echo $attach->url;
                                                echo '" alt=""></div>';
                                            }
                                        }
									@endphp
								</div>
							</div>
							<div class="col-xl-7">
								<section class="card">
									<header class="card-header" style="padding-bottom: 10px;">
										<h2 class="card-title">{{$post->title}}</h2>
										</header>
									<div class="card-body">
										<div class="scrollable" data-plugin-scrollable style="height: 100px;">
											<div class="scrollable-content">
												{{$post->content}}
											</div>
										</div>
									</div>
								</section>
							</div>
							<div class="col-xl-2">
								<a href="#delete_modal" id="delete_post" type="button" class="mb-1 mt-1 mr-1 btn btn-danger vh-center">DELETE</a>
							</div>
						</div>
					</div>
				</section>
				<div id="delete_modal" class="modal-block modal-block-primary mfp-hide">
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
									<p class="mb-0">Are you sure that you want to delete this post?</p>
								</div>
							</div>
						</div>
						<footer class="card-footer">
							<div class="row">
								<div class="col-md-12 text-right">
									<button id="post_delete_btn" post-id="{{$post->id}}" type="button" class="mb-1 mt-1 mr-1 btn btn-danger"
									>Delete</button>
									<button class="btn btn-default modal-dismiss">Cancel</button>
								</div>
							</div>
						</footer>
					</section>
				</div>
			</div>
		</div>
	</section>
@endsection


@section('specific page vendor js')
	<script src="{{asset('assets/vendor/select2/js/select2.js')}}"></script>
	<script src="{{asset('assets/vendor/pnotify/pnotify.custom.js')}}"></script>
	<script src="{{asset('assets/vendor/owl.carousel/owl.carousel.js')}}"></script>

@endsection

@section('page js')
	<script>
		let post_id;
	</script>
	<script src="{{asset('assets/js/modals.js')}}"></script>
	<script>
		$(document).ready(function () {
			$('#post_delete_btn').click(function () {
				post_id = $(this).attr('post-id');
				//$('#delete_post').click();
				$.magnificPopup.close();
		        $.ajax({
		            url: app_url + '/delete_post',
		            type: 'post',
		            data: {
		                _token: $('meta[name=csrf-token]').attr("content"),
		                post_id: post_id
		            },
		            success: function (data) {
		                if(data['status']){
		                    window.location.href = app_url + 'reportmanagement';
		                }
		            }
		        });
            });
        });
	</script>
@endsection
