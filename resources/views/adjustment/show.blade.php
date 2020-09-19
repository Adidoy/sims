@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">{{ $adjustment->local }}</h3></legend>
		<ul class="breadcrumb">
			<li>Adjustment</li>
			<li class="active"> {{ $adjustment->local }} </li>
		</ul>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
		<div class="row" style="text-align:center;">
			<div class="col-sm-12">
				<a href="{{ url('../reports/adjustments') }}/{{ $adjustment->local }}/print" target="_blank" id="print" class="print btn btn-md btn-primary" data-style="zoom-in">
					<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
					<span id="nav-text"> Download Adjustment Report</span>
				</a>
			</div>
		</div>
		<div class="panel panel-body table-responsive">
			<table class="table table-hover table-striped table-bordered table-condensed" id="adjustmentTable" cellspacing="0" width="100%"	>
				<thead>
		            <tr rowspan="2">
		                <th class="text-left" colspan="3">Adjustment Reference:  <span style="font-weight:normal">{{ $adjustment->local }}</span> </th>
		                <th class="text-left" colspan="3">Created By:  <span style="font-weight:normal">{{ $adjustment->processed_person }}</span> </th>
		            </tr>
					<tr rowspan="2">
		                <th class="text-left" colspan="6">References:  <span style="font-weight:normal">{{ $adjustment->reference }}</span> </th>
		            </tr>
					<tr rowspan="2">
		                <th class="text-left" colspan="6">Reasons Leading to Adjustment:  <span style="font-weight:normal">{{ $adjustment->reasonLeadingTo }}</span> </th>
		            </tr>
		            <tr rowspan="2">
		                <th class="text-left" colspan="6">Details:  <span style="font-weight:normal">{{ $adjustment->details_append }}</span> </th>
		            </tr>
		            <tr>
						<th>Stock Number</th>
						<th>Details</th>
						<th>Quantity</th>
						<th>Unit Cost</th>
						<th>Total Cost</th>
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

    var table = $('#adjustmentTable').DataTable({
			language: {
					searchPlaceholder: "Search..."
			},
			"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
			ajax: "{{ url("inventory/adjustments/$adjustment->id") }}",
			columns: [
					{ data: "stocknumber" },
					{ data: "details" },
					{ data: "pivot.quantity" },
					{ data: "pivot.unit_cost" },
					{ data: "pivot.total_cost" }
			],
    });
	} );
</script>
@endsection
