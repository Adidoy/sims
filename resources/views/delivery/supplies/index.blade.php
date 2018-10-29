@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Items Delivery</h3></legend>
		<ol class="breadcrumb">
			<li>Delivery</li>
			<li>Supplies</li>
			<li class="active">Home</li>
		</ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
		<div class="panel panel-body table-responsive">
		<table class="table table-hover table-striped table-bordered table-condensed" id="deliveriesTable">
			<thead>
				<th class="col-sm-1">Delivery No.</th>
				<th class="col-sm-1">Supplier</th>
				<th class="col-sm-1">Purchase Order No.</th>
				<th class="col-sm-1">Invoice No.</th>
				<th class="col-sm-1">Delivery Receipt No.</th>
				<th class="col-sm-1">Date Processed</th>
				<th class="col-sm-1">Processed by</th>
				<th class="no-sort col-sm-1"></th>
			</thead>
		</table>
		</div>

    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection

@section('after_scripts')

<script>
	$(document).ready(function() {

	    var table = $('#deliveriesTable').DataTable({
			language: {
					searchPlaceholder: "Search..."
			},
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
			"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
			ajax: "{{ url('delivery/supply/') }}",
			columns: [
				{ data: "local" },
				{ data: "supplier_name" },
				{ data: "purchaseorder_no" },
				{ data: "invoice_no" },
				{ data: "delrcpt_no" },
				{ data: "date_processed" },
				{ data: "received_by" },
				{ data: function(callback){
	            	return `
						<a href="{{ url('delivery/supply') }}/`+ callback.id +`" class="btn btn-default btn-sm"><i class="fa fa-list-ul" aria-hidden="true"></i> View</a>
	            	`;
	            } }
			],
	    });

		$("div.toolbar").html(`
				<a href="{{ url('delivery/supply/create') }}" class="btn btn-sm btn-primary">
					<span class="glyphicon glyphicon-tag" aria-hidden="true"></span>
					<span id="nav-text">Create New Delivery Record</span>
				</a>
		`);

		$('#page-body').show();
	} );
</script>
@endsection
