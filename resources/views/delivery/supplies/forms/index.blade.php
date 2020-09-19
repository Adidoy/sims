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
					<th class="hidden">created_at</th>
					<th>Delivery Reference</th>
					<th>Supplier</th>
					<th>Purchase Order No.</th>
					<th>Invoice No.</th>
					<th>Delivery Receipt No.</th>
					<th>Date Processed</th>
					<th>Processed by</th>
					<th></th>
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
			pageLength: 25,
        	"processing": true,
			language: {
					searchPlaceholder: "Search..."
			},
			'columnDefs' : [
        		{ 'visible': false, 'targets': [0] }
    		],
			"order": [[ 0, "desc" ]],
			"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			ajax: "{{ url('delivery/supplies/') }}",
			columns: [
				{ data: "created_at" },
				{ data: "DeliveryReference" },
				{ data: "Supplier" },
				{ data: "PONumber" },
				{ data: "InvoiceNumber" },
				{ data: "DRNumber" },
				{ data: "DateProcessed" },
				{ data: "ProcessedBy" },
				{ data: function(callback){
					if(callback.DeliveryReference != '')
					{
						return `
							<a href="{{ url('delivery/supplies') }}/`+ callback.ID +`" class="btn btn-default ladda-button" data-style="zoom-in"><i class="fa fa-list-ul" aria-hidden="true"></i> View</a>
						`;
					}
					else
					{
						return `
							<a href="{{ url('receipt') }}/`+ callback.ID +`" class="btn btn-default ladda-button" data-style="zoom-in"><i class="fa fa-list-ul" aria-hidden="true"></i> View</a>
						`;
					}
				} 
	}
			],
	    });

		$('#page-body').show();
	} );
</script>
@endsection
