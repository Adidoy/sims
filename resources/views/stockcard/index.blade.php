@extends('backpack::layout') 

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Stock Card</h3></legend>
		<ul class="breadcrumb">
			<li><a href="{{ url('inventory/supply') }}">Inventory</a></li>
			<li class="active">{{ $supply->stocknumber }}</li>
			<li class="active">Stock Card</li>
		</ul>
	</section>
@endsection

@section('content')

<div class="box" style="padding:10px">
    <div class="box-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="col-sm-3">
				</div>
				<div class="col-sm-3">
					<div class="float-right">
						<a href="{{ url('../reports/stockcards') }}/{{ $supply->stocknumber }}/print" target="_blank" id="print" class="print btn btn-md btn-primary" data-style="zoom-in" style="width:100%">
							<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
							<span id="nav-text"> Download Stock Card</span>
						</a>
					</div>
				</div>
				<div class="col-sm-3">
					<a href="{{ url('../reports/stockcards/ledger') }}/{{ $supply->stocknumber }}/print" target="_blank" id="print" class="print btn btn-md btn-success" data-style="zoom-in" style="width:100%">
						<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
						<span id="nav-text"> Download Ledger Card</span>
					</a>
				</div>
				<div class="col-sm-3">
				</div>
			</div>
		</div>
		<hr />
		<div class="row">
			<div class="col-sm-12">
				<table class="table table-hover table-striped table-bordered table-condensed" id="inventoryTable" cellspacing="0" width="100%">
					<thead>
						<tr rowspan="2">
							<th class="text-left" colspan="8">Fund Cluster:
								<span style="font-weight:normal"> </span>
							</th>
						</tr>
						<tr rowspan="2">
							<th class="text-left" colspan="4">Item:  <span style="font-weight:normal">{{ $supply->details }}</span> </th>
							<th class="text-left" colspan="4">Stock No.:  <span style="font-weight:normal">{{ $supply->stocknumber }}</span> </th>
						</tr>
						<tr rowspan="2">
							<th class="text-left" colspan="4">Unit Of Measurement:  <span style="font-weight:normal">{{ $supply->unit->name }}</span>  </th>
							<th class="text-left" colspan="4">Reorder Point: <span style="font-weight:normal">{{ $supply->reorderpoint }}</span> </th>
						</tr>
						<tr>
							<th data-visible="false">ID</th>
							<th>Date</th>
							<th width="15%">Reference</th>
							<th>Receipt Qty</th>
							<th>Issue Qty</th>
							<th>Office</th>
							<th>Balance Qty</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
    </div>
</div>
@endsection

@section('after_scripts')

<script>
	$(document).ready(function() {

	    var table = $('#inventoryTable').DataTable({
			language: {
					searchPlaceholder: "Search..."
			},
			"columnDefs":[
				{ "type": "date", "targets": 0 },
			],
			"order": [
				[0, 'desc']
			],
			"processing": true,
			ajax: '{{ url("inventory/supply/$supply->id/stockcard/") }}',
			columns: [
					{ data: "updated_at"},
					{ data: "parsed_date"},
					{ data: "reference_information" },
					{ data: "received_quantity"},
					{ data: "issued_quantity" },
					{ data: "organization" },
					{ data: "balance_quantity" }
			],
	    });
	} );
</script>
@endsection
