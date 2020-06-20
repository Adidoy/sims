@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
	    Reports
	  </h1>
	  <ol class="breadcrumb">
	    <li>Reports</li>
	    <li class="active">Summary</li>
	  </ol>
	</section>
@endsection

@section('content')
  <div class="box" style="padding:10px;">
		<div class="form-group">
			<div class="col-md-4">
				<br />
			</div>
    	<div class="col-md-4">
				<form method="post" action="{{ route('summary.submit') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					{{ Form::label('lblPeriod','Select Period:') }}
					<select id="period" name = "period" class="form-control">
						@foreach($years as $year)
							<option value="{{ $year }}">{{ $year }}</option>
						@endforeach
					</select>
					<button type="submit" id="generate" class="btn btn-md btn-default">
						<span class="glyphicon glyphicon-print" href="{{ url('reports/summary/print') }}" target="_blank" aria-hidden="true"></span>
						<span id="nav-text"> Print Report</span>
					</button>
				</form>
    	</div>
			<div class="col-md-4">
				<br />
			</div>
		</div>
		<br /><br /><br /><br />
	  <div class="box-body">
    	<legend><h3 class="text-muted">Summary Reports</h3></legend>
			<table class="table table-hover table-striped table-bordered table-condensed table-responsive" id="rsmiTable" cellspacing="0" width="100%">
				<thead>
					<th class="col-sm-1">Stock No.</th>
					<th class="col-sm-2">Supply Name</th>
					<th class="col-sm-1">Quantity Received</th>
					<th class="col-sm-1">Quantity Issued</th>
					<th class="col-sm-1">Remaining Balance</th>
				</thead>
			</table>
    </div>
  </div>
@endsection

@section('after_scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			var table = $('#rsmiTable').DataTable({
	    	pageLength: 100,
			"processing": true,
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
			language: {
					searchPlaceholder: "Search..."
			},
			ajax: '{{ url("reports/summary/") }}',
			columns: [
					{ data: "stocknumber" },
					{ data: "details" },
					{ data: "received" },
					{ data: "issued" },
					{ data: "balance" },
			],
			});
		});
		$('#period').on('change', function() {
			$('#rsmiTable').dataTable().fnDestroy();
			var table = $('#rsmiTable').DataTable({
	    	pageLength: 100,
			"processing": true,
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
			language: {
					searchPlaceholder: "Search..."
			},
			ajax: '{{ url("reports/summary/getRecords") }}' + '/' + $('#period').val(),
			columns: [
					{ data: "stocknumber" },
					{ data: "details" },
					{ data: "received" },
					{ data: "issued" },
					{ data: "balance" },
			],
			});			
		});
	</script>
@endsection