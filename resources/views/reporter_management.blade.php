@extends('layouts.layout')

@section('title', 'ReporterManagement')

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
				<div class="card-actions">
					<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
				</div>

				<h2 class="card-title">REPORTER MANAGEMENT</h2>
			</header>
			<div class="card-body">
				<table class="table table-bordered table-striped" id="datatable-reporterlist" data-url="ajax/ajax-datatables-sample.json">
					<thead>
					<tr>
						<th width="10%">REPORTER</th>
						<th width="20%">FIRST NAME</th>
						<th width="20%">LAST NAME</th>
						<th width="15%">USER NAME</th>
						<th width="15%">EMAIL</th>
						<th width="20%">STATUS</th>
					</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
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
		function approveUser(obj) {
			$.ajax({
				url: app_url + '/approveUser',
				type: 'post',
				data: {
                    _token: $('meta[name=csrf-token]').attr("content"),
				    id: obj.attr('reporter-id')
				},
				success: function (data) {
                    $("#datatable-reporterlist").DataTable().ajax.reload()
                }
			});
        }
        function declineUser(obj) {
            $.ajax({
                url: app_url + '/declineUser',
                type: 'post',
                data: {
                    _token: $('meta[name=csrf-token]').attr("content"),
                    id: obj.attr('reporter-id')
                },
                success: function (data) {
                    $("#datatable-reporterlist").DataTable().ajax.reload()
                }
            });
        }
	</script>
@endsection