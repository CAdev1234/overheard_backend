@extends('layouts.layout')
@section('title', 'Profile Detail')
@section('specific page vendor css')
	<!-- Specific Page Vendor CSS -->
	<link rel="stylesheet" href="{{asset('assets/vendor/select2/css/select2.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/datatables/media/css/dataTables.bootstrap4.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/elusive-icons/css/elusive-icons.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/pnotify/pnotify.custom.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/owl.carousel/assets/owl.carousel.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/owl.carousel/assets/owl.theme.default.css')}}" />
@endsection


@section('content body')
	<section role="main" class="content-body">
		<!-- start: page -->
		<section class="card">
			<header class="card-header">
				<div class="card-actions">
					<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
				</div>

				<h2 class="card-title">COMMUNITY SETTING</h2>
			</header>
			<div class="card-body">
				<form class="form-horizontal form-bordered" method="get">
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="inputPlaceholder">COMMUNITY NAME</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" placeholder="Community Name" id="input-communityname" value="{{$community->name}}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="inputPlaceholder">Latitude</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" placeholder="Latitude" id="input-lat" value="{{$community->lat}}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="inputPlaceholder">Longitude</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" placeholder="Longitude" id="input-lng" value="{{$community->lng}}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="inputPlaceholder">RADIUS</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" placeholder="0.0" id="input-radius" value="{{$community->radius}}">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="inputPlaceholder">ADS PRICE</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" placeholder="0.0" id="input-adsprice" value="{{$community->ads_price}}">
						</div>
					</div>
					<button id="community-info-update" type="button" class="mb-1 mt-1 mr-1 btn btn-primary update-generalinfo-button">SAVE CHANGES <i class="fa fa-refresh"></i></button>
				</form>
			</div>
		</section>
		<!-- end: page -->
	</section>
@endsection


@section('specific page vendor js')
	<script src="{{asset('assets/vendor/select2/js/select2.js')}}"></script>
	<script src="{{asset('assets/vendor/datatables/media/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('assets/vendor/datatables/media/js/dataTables.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/vendor/autosize/autosize.js')}}"></script>
	<script src="{{asset('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.js')}}"></script>
	<script src="{{asset('assets/vendor/pnotify/pnotify.custom.js')}}"></script>
	<script src="{{asset('assets/vendor/owl.carousel/owl.carousel.js')}}"></script>
@endsection

@section('page js')
	<script>
        let community_id = '{{$community->id}}';
        let post_id;
	</script>
	<script src="{{asset('assets/js/community.detail.tables.editable.js')}}"></script>
	<script src="{{asset('assets/js/modals.js')}}"></script>
	<script src="{{asset('assets/js/settings.ajax.js')}}"></script>
	<script>
        function delete_post(obj){
            post_id = obj.attr('post-id');
            $('#delete_post').click();
        }
	</script>
@endsection
