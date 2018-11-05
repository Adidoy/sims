@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Inspection and Acceptance </h3></legend>
		<ol class="breadcrumb">
			<li>Inspection</li>
			<li>Supplies</li>
			<li class="active">Home</li>
		</ol>
	</section>
@endsection

@section('content')
  <div class="box">
    <div class="box-body">
		<div class="panel panel-body table-responsive">
		<table class="table table-hover table-striped table-bordered table-condensed" id="inspectionsTable">
			<thead>
				<th class="col-sm-1">IAR No.</th>
				<th class="col-sm-1">Inspected by</th>
				<th class="col-sm-1">Date Processed</th>
				<th class="col-sm-1">Approved by</th>
				<th class="col-sm-1">Date Approved</th>
				<th class="col-sm-1">Acknowledged by</th>
				<th class="col-sm-1">Date Acknowledged</th>
				<th class="no-sort col-sm-1"></th>
			</thead>
		</table>
		</div>

    </div>
  </div>
@endsection

@section('after_scripts')
	<script>
		$(document).ready(function() 
		{
			var table = $('#inspectionsTable').DataTable({
				language: {
						searchPlaceholder: "Search..."
				},
				columnDefs:[
					{ targets: 'no-sort', orderable: false },
				],
				"processing": true,
				"autoWidth": false,
				ajax: "{{ url('inspection/view/supply/') }}",
				columns: [
					{ data: "local" },
					{ data: "inspection_personnel" },
					{ data: "date_inspected" },
					{ data: "approval" },
					{ data: "approval_date" },
					{ data: "acknowledgement" },
					{ data: "acknowledgement_date" },
					{ data: function(callback){
						return `
							<a href="{{ url('inspection/view/supply/') }}/`+ callback.id +`" class="btn btn-default btn-sm"><i class="fa fa-list-ul" aria-hidden="true"></i>  View</a>
						`;
					} }
				],
			});

			$('#page-body').show();
		});
	</script>
@endsection
