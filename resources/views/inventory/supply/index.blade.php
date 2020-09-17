@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Supplies Inventory</h3></legend>
		<ol class="breadcrumb">
			<li>Inventory</li>
			<li class="active">Home</li>
		</ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
		<div class="panel panel-body table-responsive">
			<table class="table table-hover table-bordered" id="supplyInventoryTable" width=100%>
				<thead>
					<tr>
						<th colspan="3" class="text-center">Information</th>
						<th colspan="2" class="text-center">Remaining Balance</th>
						<th rowspan="2" class="text-center col-sm-1 no-sort">Action</th>
					</tr>
					<tr>
						<th class="col-sm-1 text-center" width="15%">Stock No.</th>
						<th class="col-sm-1 text-center" width="35%">Supply Item</th>
						<th class="col-sm-1 text-center" width="15%">Unit</th>
						<th class="col-sm-1 text-center" width="15%">Reorder Point</th>
						<th class="col-sm-1 text-center" width="20%">Stock Card</th>
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

	    var table = $('#supplyInventoryTable').DataTable({
			language: {
					searchPlaceholder: "Search..."
			},
	    	columnDefs:[
	       	 { targets: 'no-sort', orderable: false },
	      	],
			"processing": true,
			responsive: false,
			ajax: "{{ url('inventory/supply') }}",
			columns: [
					{ data: "stocknumber" },
					{ data: "details" },
					{ data: "unit_name" },
					{ data: "reorderpoint" },
					{ data: "stock_balance" },
		            { data: function(callback){
		            	return `
								<div class="row" style="margin-bottom:5px;">
									<div class="col-sm-12">
										<a href="{{ url("inventory/supply") }}` + '/' + callback.id  + '/stockcard' +`" class="btn btn-sm btn-primary" style="width:110px;">
		            						<i class="glyphicon glyphicon-list"></i>&nbsp; View Details
		            					</a>
									</div>
								</div>
		            	`;
		            } }
			],
	    });
	} );
</script>
@endsection
