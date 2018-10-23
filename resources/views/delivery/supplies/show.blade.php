@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend>
            <h3 class="text-muted">{{ $delivery->local }}</h3>
        </legend>
		<ul class="breadcrumb">
			<li>Delivery Acceptance</a></li>
			<li class="active"> {{ $delivery->local }} </li>
		</ul>
	</section>
@endsection 

@section('content')
<!-- Default box -->
    <div class="box">
        <div class="box-body">
		    <div class="panel panel-body table-responsive">
			    <table class="table table-hover table-striped table-bordered table-condensed" id="requestTable" cellspacing="0" width="100%">
				    <thead>
					<tr>
                            <th >Delivery Acceptance No.:  <span style="font-weight:normal">{{ $delivery->local }}</span> </th>
                            <th >Purchase Order No.:  <span style="font-weight:normal">{{ isset($delivery->purchaseorder_no) ? $delivery->purchaseorder_no : 'None' }}</span> </th>
							<th >Invoice No.:  <span style="font-weight:normal">{{ isset($delivery->invoice_no) ? $delivery->invoice_no : 'None' }}</span> </th>
							<th >Delivery Receipt No.:  <span style="font-weight:normal">{{ isset($delivery->delrcpt_no) ? $delivery->delrcpt_no : 'None' }}</span> </th>
                        </tr>
                        <tr >
							<th  >Date Processed:  <span style="font-weight:normal">{{ isset($delivery->created_at) ? $delivery->created_at : 'None' }}</span> </th>
                            <th  >Purchase Order Date:  <span style="font-weight:normal">{{ isset($delivery->parsed_purchaseorder_date) ? $delivery->parsed_purchaseorder_date : 'None' }}</span> </th>
							<th  >Invoice Date:  <span style="font-weight:normal">{{ isset($delivery->invoice_date) ? $delivery->invoice_date : 'None' }}</span> </th>
							<th  >Delivery Date:  <span style="font-weight:normal">{{ isset($delivery->delivery_date) ? $delivery->delivery_date : 'None' }}</span> </th>
                        </tr>
						<tr >
							<th  >Processed By:  <span style="font-weight:normal">{{ isset($delivery->received_by) ? $delivery->received_by : 'None' }}</span> </th>
                            <th  ></th>
							<th  ></th>
							<th  > </th>
                        </tr>
                        <tr>          
						    <th>Stock Number</th>
						    <th>Supply Name</th>
						    <th>Quantity Delivered</th>
						    <th>Unit Cost</th>
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
	 	var table = $('#requestTable').DataTable({
	 	language: {
	 		searchPlaceholder: "Search..."
	 	},
	 	"processing": true,
		ajax: "{{ url("delivery/supply/$delivery->id") }}",
	 	columns: [
	 			{ data: "stocknumber" },
				{ data: "details" },
				{ data: "pivot.quantity_delivered" },
				{ data: "pivot.unit_cost" }
	 		],
		});
	});
</script>
@endsection