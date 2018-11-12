@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend>
            <h3 class="text-muted">Delivery Acceptance No.: {{ $delivery->local }}</h3>
        </legend>
		<ul class="breadcrumb">
			<li>Delivery Acceptance</a></li>
			<li class="active"> {{ $delivery->local }} </li>
		</ul>
	</section>
@endsection 

@section('content')
    <div class="box">
        <div class="box-body">
		    <div class="panel panel-body table-responsive">
			<table class="table table-hover table-striped table-bordered table-condensed" id="headerTable" cellspacing="0" width="100%">
				    <thead>
						<tr>
							<th>Processed By:  <span style="font-weight:normal">{{ isset($delivery->user_name) ? $delivery->user_name : 'None' }}</span> </th>						
							<th>Date Processed:  <span style="font-weight:normal">{{ isset($delivery->date_processed) ? $delivery->date_processed : 'None' }}</span> </th>
                        </tr>
                        <tr >
							<th>Purchase Order No.:  <span style="font-weight:normal">{{ isset($delivery->purchaseorder_no) ? $delivery->purchaseorder_no : 'None' }}</span> </th>
                            <th>Purchase Order Date:  <span style="font-weight:normal">{{ isset($delivery->date_purchaseorder) ? $delivery->date_purchaseorder : 'None' }}</span> </th>

                        </tr>
                        <tr >
							<th>Invoice No.:  <span style="font-weight:normal">{{ isset($delivery->invoice_no) ? $delivery->invoice_no : 'None' }}</span> </th>
							<th>Invoice Date:  <span style="font-weight:normal">{{ isset($delivery->date_invoice) ? $delivery->date_invoice : 'None' }}</span> </th>
                        </tr>
                        <tr >
							<th>Delivery Receipt No.:  <span style="font-weight:normal">{{ isset($delivery->delrcpt_no) ? $delivery->delrcpt_no : 'None' }}</span> </th>
							<th>Delivery Date:  <span style="font-weight:normal">{{ isset($delivery->date_delivered) ? $delivery->date_delivered : 'None' }}</span> </th>
                        </tr>						
				    </thead>
			    </table>
				<hr style="color: black; background-color :black;" />
			    <table class="table table-hover table-striped table-bordered table-condensed" id="requestTable" cellspacing="0" width="100%">
				    <thead>
                        <tr>          
						    <th class="text-center">Stock Number</th>
						    <th class="text-center">Item Name</th>
							<th class="text-center">Unit of Measure</th>
						    <th class="text-center">Quantity Delivered</th>
						    <th class="text-center">Unit Cost</th>
					    </tr>
				    </thead>
			    </table>
		    </div>
        </div>
    </div>
@endsection

@section('after_scripts')
<script>
	$(document).ready(function() {
	 	var table = $('#requestTable').DataTable({
			language: {
				searchPlaceholder: "Search..."
			},
			"processing": true,
			ajax: "{{ url("delivery/supply/$delivery->id") }}",
			columnDefs: [{
					targets: [3,4],
					className: "text-right"
				}
			],
			columns: [
					{ data: "stocknumber" },
					{ data: "details" },
					{ data: "unit.name" },
					{ data: "pivot.quantity_delivered" },
					{ data: "pivot.unit_cost" }
			],
		});
	});
</script>
@endsection