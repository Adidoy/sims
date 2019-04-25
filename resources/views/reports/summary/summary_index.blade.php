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
    <div class="box-body">
    	<legend><h3 class="text-muted">Summary Reports</h3></legend>
			<table class="table table-hover table-striped table-bordered table-condensed table-responsive" id="rsmiTable" cellspacing="0" width="100%">
				<thead>
					<th class="col-sm-1">Stock No.</th>
					<th class="col-sm-1">Supply Name</th>
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
				"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar' id='haha'>><'col-sm-3'f>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",			
			});
			$('div.toolbar').html(`
				<form method="post" action="{{ route('summary.submit') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
						@if(isset($years) && count($years) > 0 )
							<select id="ddl_years" class="form-control">
								@foreach($years as $year)
									<option value="{{ $year }}">{{ $year }}</option>
								@endforeach
							</select>
						@endif
					<button type="submit" id="generate" class="btn btn-md btn-default">
						<span class="glyphicon href="{{ url("reports/summary/print") }}" glyphicon-print" aria-hidden="true"></span>
						<span id="nav-text"> Generate Report</span>
					</button>
				</form>
			`)
		});
		$('#rsmiTable select#ddl_years').on('click', function() {
			alert('alert!');
				// var url = "{{url('admin/maintenance/taxa/getRanks')}}" + "/" + $('#years').val();
				// $.ajax({
				// 		url: url,
				// 		type: "GET",
				// 		dataType: "json",
				// 		success:function(data) {
				// 				$('#ddl_rank').html('<option selected="selected" value="">Select Value</option>');
				// 				$.each(data, function(key, value) {
				// 						$('#ddl_rank').append('<option value="'+key+'">'+value+'</option>');
				// 				});
				// 		}
				// });
		});
	</script>
@endsection