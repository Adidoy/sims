@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">R.S.M.I. as of {{ $rsmi->parsed_report_date }}</h3></legend>
		<ul class="breadcrumb">
			<li><a href="{{ url('rsmi') }}">R.S.M.I.</a></li>
			<li class="active"> {{ $rsmi->parsed_report_date }} </li>
		</ul>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
		<div class="panel panel-body table-responsive">

			<table class="table table-hover table-striped table-bordered table-condensed" id="rsmiTable" cellspacing="0" width="100%"	>
				<thead>
					<tr rowspan="2">
					  <th class="text-left" colspan="3">Month:  <span style="font-weight:normal">{{ $rsmi->parsed_month }}</span> </th>
					  <th class="text-left" colspan="3">Status:  <span style="font-weight:normal">{{ ($rsmi->status_name == '') ? ucfirst(config('app.default_status')) : $rsmi->status_name }}</span> </th>
					</tr>
					<tr rowspan="2">
					  <th class="text-left" colspan="3">Created By:  <span style="font-weight:normal">{{ $rsmi->created_by }}</span> </th>
					  <th class="text-left" colspan="3">Remarks:  <span style="font-weight:normal">{{ $rsmi->remarks }}</span> </th>
					</tr>
					<tr>
			            <th>Reference</th>
						<th>Stock Number</th>
						<th style="white-space: normal;">Details</th>
						<th>Issued Quantity</th>
						<th>Issued Unit Cost</th>
					</tr>
				</thead>
			</table>
		</div>

    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection

@section('after_scripts')

<script>
	$(document).ready(function() {

    var table = $('#rsmiTable').DataTable({
		pageLength: 100,
		"processing": true,
		language: {
				searchPlaceholder: "Search..."
		},
		"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
						"<'row'<'col-sm-12'tr>>" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
		ajax: "{{ url("rsmi/$rsmi->id") }}",
		columns: [
			{data: 'reference' },
			{ data: "stocknumber" },
			{ data: "supply_name" },
			{ data: "issued_quantity" },
			{ data: "pivot.unitcost" },
		],
    });

    $('div.toolbar').html(`     
		<div class="form-group">
			<a href="{{ url("rsmi/$rsmi->id/print") }}" target="_blank" id="print" class="print btn btn-sm btn-default ladda-button" data-style="zoom-in">
				<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
				<span id="nav-text"> Download</span>
			</a>
		</div>

		@if($rsmi->status_name == 'Pending' && Auth::user()->access == 1)
		<div class="form-group">
			<form method="post" action="{{ url("rsmi/$rsmi->id/submit") }}">
				{{ csrf_field() }}
				<button type="submit" href="" id="submit" class="print btn btn-sm btn-success ladda-button" data-style="zoom-in">
					<span id="nav-text"> Submit</span>
				</button>
			</form>
		</div>
		@elseif(Auth::user()->access == 2 )

			@if($rsmi->status_name == 'Submitted')
			<div class="form-group">
				<form method="get" action="{{ url("rsmi/$rsmi->id/receive") }}">
					<button type="submit" id="submit" class="print btn btn-sm btn-success ladda-button" data-style="zoom-in">
						<span id="nav-text"> Receive</span>
					</button>
				</form>
			</div>
			@endif

			@if($rsmi->status_name == 'Received')
			<div class="form-group">
				<form method="post" action="{{ url("rsmi/$rsmi->id/apply") }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<button type="submit" id="submit" class="print btn btn-sm btn-success ladda-button" data-style="zoom-in">
						<span id="nav-text"> Apply To Ledger Card</span>
					</button>
				</form>
			</div>
			@endif

			@if( $rsmi->status_name == 'Received' || $rsmi->status_name == 'Applied To Ledger Card' )
			<div class="form-group">
				<form method="get" action="{{ url("rsmi/$rsmi->id/summary") }}">
					<button type="submit" id="submit" class="print btn btn-sm btn-primary ladda-button" data-style="zoom-in">
						<span id="nav-text"> Recapitulation</span>
					</button>
				</form>
			</div>
			@endif

		@endif
    `)

	} );
</script>
@endsection
