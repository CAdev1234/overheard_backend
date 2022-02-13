@extends('layouts.layout')

@section('title', 'PostManagement')

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

				<h2 class="card-title">POSTS MANAGEMENT</h2>
			</header>
			<div class="card-body">
				<table class="table table-bordered table-striped" id="datatable-postlist" data-url="ajax/ajax-datatables-sample.json">
					<thead>
					<tr style="display: none">
						<th width="25%">MEDIA</th>
						<th>CONTENT</th>
						<th width="15%">ACTION</th>
					</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<a href="#delete_modal" id="delete_post" style="display: none"></a>
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
								<button id="post_delete_btn" type="button" class="mb-1 mt-1 mr-1 btn btn-danger"
									>Delete</button>
								<button class="btn btn-default modal-dismiss">Cancel</button>
							</div>
						</div>
					</footer>
				</section>
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
	<script src="{{asset('assets/vendor/owl.carousel/owl.carousel.js')}}"></script>
@endsection

@section('page js')
	<script>
		let user_id = '{{$user_id}}';
		let post_id;
	</script>
	<script src="{{asset('assets/js/posts.tables.editable.js')}}"></script>
	<script src="{{asset('assets/js/modals.js')}}"></script>
	<script>
        function delete_post(obj){
            post_id = obj.attr('post-id');
            $('#delete_post').click();
        }
	</script>
@endsection