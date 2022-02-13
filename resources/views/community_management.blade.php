@extends('layouts.layout')

@section('title', 'CommunityManagement')

@section('specific page vendor css')
	<!-- Specific Page Vendor CSS -->
	<link rel="stylesheet" href="{{asset('assets/vendor/select2/css/select2.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/datatables/media/css/dataTables.bootstrap4.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/bootstrap.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/animate/animate.css')}}">
	<link rel="stylesheet" href="{{asset('assets/vendor/font-awesome/css/font-awesome.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/elusive-icons/css/elusive-icons.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/pnotify/pnotify.custom.css')}}" />
@endsection

@section('content body')
	<section role="main" class="content-body">
		<!-- start: page -->
		<section class="card">
			<header class="card-header">
				<div class="card-actions vertical-center">
					<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
					<a href="{{route('communitycreate')}}"><button type="button" class="mb-1 mt-1 mr-1 btn btn-primary"><i class="fa fa-plus"></i> NEW</button></a>
				</div>

				<h2 class="card-title">COMMUNITY MANAGEMENT</h2>
			</header>
			<div class="card-body">
				<div class="tabs">
					<ul class="nav nav-tabs">
						<li class="nav-item active">
							<a class="nav-link" href="#popular" data-toggle="tab"><i class="fa fa-star"></i> List of communities</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#recent" data-toggle="tab">Communities submits</a>
						</li>
					</ul>
					<div class="tab-content">
						<div id="popular" class="tab-pane active">
							<table class="table table-bordered table-striped" id="datatable-communitylist" data-url="ajax/ajax-datatables-sample.json">
								<thead>
								<tr>
									<th width="20%">Community Name</th>
									<th width="15%">Latitude</th>
									<th width="15%">Longitude</th>
									<th width="10%">Participants Number</th>
									<th width="20%">Created Date</th>
									<th width="20%">Action</th>
								</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<div id="recent" class="tab-pane">
							<table class="table table-bordered table-striped" id="datatable-communitysubmitlist" data-url="ajax/ajax-datatables-sample.json">
								<thead>
								<tr>
									<th width="20%">Community Name</th>
									<th width="20%">Latitude</th>
									<th width="20%">Longitude</th>
									<th width="20%">Submitted Date</th>
									<th width="20%">Action</th>
								</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>

			</div>
		</section>
		<!-- end: page -->
	</section>
@endsection



@section('specific page vendor js')
	<!-- Specific Page Vendor -->
	<script src="{{asset('assets/vendor/select2/js/select2.js')}}"></script>
	<script src="{{asset('assets/vendor/datatables/media/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('assets/vendor/datatables/media/js/dataTables.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/vendor/autosize/autosize.js')}}"></script>
	<script src="{{asset('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.js')}}"></script>
	<script src="{{asset('assets/vendor/pnotify/pnotify.custom.js')}}"></script>
@endsection

@section('page js')
	<script src="{{asset('assets/js/tables.editable.js')}}"></script>
	<script src="{{asset('assets/js/settings.ajax.js')}}"></script>
	<script>
		function approveCommunity(obj) {
			$.ajax({
				url: app_url + '/approveCommunity',
				type: 'post',
				data: {
                    _token: $('meta[name=csrf-token]').attr("content"),
				    id: obj.attr('community-id')
				},
				success: function (data) {
                    $("#datatable-communitysubmitlist").DataTable().ajax.reload()
                    $("#datatable-communitylist").DataTable().ajax.reload();
                }
			});
        }
        function declineCommunity(obj) {
            $.ajax({
                url: app_url + '/declineCommunity',
                type: 'post',
                data: {
                    _token: $('meta[name=csrf-token]').attr("content"),
                    id: obj.attr('community-id')
                },
                success: function (data) {
                    $("#datatable-communitysubmitlist").DataTable().ajax.reload()
                    $("#datatable-communitylist").DataTable().ajax.reload();
                }
            });
        }
	</script>
@endsection