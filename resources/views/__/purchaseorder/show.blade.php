@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend>
			<h3 class="text-muted">
			    References
			</h3>
		</legend>
		<ul class="breadcrumb">
			<li><a href="{{ url('purchaseorder') }}">References</a></li>
			<li class="active"> {{ $purchaseorder->number }} </li>
		</ul>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
		<div class="panel panel-body table-responsive">

			@if(Auth::user()->access == 2)
			<a href="{{ url("purchaseorder/$purchaseorder->id/print") }}" target="_blank" id="print" class="print btn btn-sm btn-default ladda-button" data-style="zoom-in">
				<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
				<span id="nav-text"> Download</span>
			</a>
			@endif
			@if(strpos(`$purchaseorder->number`,'alance') != true || strpos(`$purchaseorder->number`,'hysical') != true)
             	<a type='button' href="{{ url("purchaseorder/$purchaseorder->id/print") }}" target="_blank" class='btn btn-primary btn-sm'>
                	<span class="glyphicon glyphicon-list"></span> Download Inventory Report
             	</a>
		    @endif
            <button type="button" id="updateFundCluster" class="copy btn btn-primary btn-sm">Update Fund Cluster</button>
			<hr />
			<table class="table table-hover table-striped table-bordered table-condensed" id="purchaseOrderTable" cellspacing="0" width="100%"	>
				<thead>

		            <tr rowspan="2">
		                <th class="text-left" colspan="4">Code:  <span style="font-weight:normal">{{ isset($purchaseorder->number) ? $purchaseorder->number : "" }}</span> </th>
		                <th class="text-left" colspan="4">Fund Cluster:
	                		<span style="font-weight:normal">{{ count($purchaseorder->fundclusters) > 0 ? implode( $purchaseorder->fundclusters->pluck('code')->toArray(), ",") : "None" }}</span>
	                	</th>
		            </tr>
		            <tr rowspan="2">
		                <th class="text-left" colspan="4">Details:  <span style="font-weight:normal">{{ isset($purchaseorder->details) ? $purchaseorder->details : "" }}</span> </th>
		                <th class="text-left" colspan="4">Date:  <span style="font-weight:normal">{{ isset($purchaseorder->date_received) ? Carbon\Carbon::parse($purchaseorder->date_received)->toFormattedDateString() : "" }}</span> </th>
		            </tr>
		            <tr>
						<th>ID</th>
						<th>Stock Number</th>
						<th>Details</th>
						<th>Ordered Quantity</th>
						<th>Received Quantity</th>
						<th>Remaining Quantity</th>
						<th>Unit Price</th>
						<th>Amount</th>
						@if(Auth::user()->access == 2)
						
						<th class="no-sort col-sm-1"></th>

						@endif
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

    var table = $('#purchaseOrderTable').DataTable({
    	// serverSide: true,
		"processing": true,
		// select: {
		// 	style: 'single'
		// },
		language: {
				searchPlaceholder: "Search..."
		},
		columnDefs:[
			 { targets: 'no-sort', orderable: false },
		],
		ajax: {
			type: "get",
			url: "{{ url("purchaseorder/$purchaseorder->id") }}",
			contentType: "application/json; charset=utf-8",
		},
		columns: [
			{ data: "id" },
			{ data: "stocknumber" },
			{ data: "details" },
			{ data: "pivot.ordered_quantity" },
			{ data: "pivot.received_quantity" },
			{ data: "pivot.remaining_quantity" },
			{ data: "pivot.unitcost" },
			{ data: function(callback){
				return parseFloat(callback.pivot.ordered_quantity * callback.pivot.unitcost).toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
			} , orderable: false},

			@if(Auth::user()->access == 2)

			{ data: function(callback){
				return `
			         <button data-id="{{ $purchaseorder->id }}" data-stocknumber="`+callback.stocknumber+`" class="edit btn btn-sm btn-primary ladda-button" data-style="zoom-in">
			          <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
			          <span id="nav-text"> Update Price</span>
			        </button>
			        `
			}}

			@endif
		],
	 });

    @if(Auth::user()->access == 2)

    $('#updateFundCluster').on('click',function(){
    	id = "{{ isset($purchaseorder->id) ? $purchaseorder->id : null }}"
    	swal({
			  title: "Purchase Order",
			  text: "Input Fund Clusters:",
			  type: "input",
			  showCancelButton: true,
			  closeOnConfirm: false,
			  animation: "slide-from-top",
			  inputPlaceholder: "Comma separate each fund cluster",
				inputValue: "{{ count($purchaseorder->fundclusters()) > 0 ? implode( $purchaseorder->fundclusters()->pluck('code')->toArray(), ",") : '' }}"
			},
			function(inputValue){
			  if (inputValue === false) return false;

			  if (inputValue === "") {
			    swal.showInputError("You need to write something!");
			    return false
			  }

			  $.ajax({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
			  	type: 'put',
			  	url: '{{ url("purchaseorder") }}' + '/' + id,
			  	dataType: 'json',
			  	data: {
			  		'fundcluster': inputValue
			  	},
			  	success: function(response){
			  		if(response == 'success')
						{
				  		swal('Success','Operation Successful','success')
				  		location.reload()
						}
			  		else
			  		swal('Error',response,'error')
			  	},
			  	error: function(){
			  		swal('Error','Problem Occurred while processing your data','error')
			  	}
			  })
			});
    	})

	    $('#purchaseOrderTable').on('click','.edit',function(){
	    	id = $(this).data('id')
	    	stocknumber = $(this).data('stocknumber')
	    	swal({
			  title: "Purchase Order",
			  text: "Input Unit Cost (Php):",
			  type: "input",
			  showCancelButton: true,
			  closeOnConfirm: false,
			  animation: "slide-from-top",
			  inputPlaceholder: "Php XX.XX"
			},
			function(inputValue){
			  if (inputValue === false) return false;

			  if (inputValue === "") {
			    swal.showInputError("You need to write something!");
			    return false
			  }

			  $.ajax({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
			  	type: 'put',
			  	url: '{{ url("purchaseorder") }}' + '/' +  id,
			  	dataType: 'json',
			  	data: {
			  		'unitprice': inputValue,
			  		'stocknumber': stocknumber
			  	},
			  	success: function(response){
			  		if(response == 'success')
			  			swal('Success','Operation Successful','success')
			  		else
			  				swal('Error','Problem Occurred while processing your data','error')
			  		table.ajax.reload();
			  	},
			  	error: function(){
			  		swal('Error','Problem Occurred while processing your data','error')
			  	}
			  })
			});

		} );

		@endif
	} );
</script>
@endsection
