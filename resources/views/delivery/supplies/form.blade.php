<!-- suppliers -->
<div class="row">
	<!-- supplier form -->
	<div class="col-md-12">
		<div class="col-md-12">
			<div class="form-group">
				{{ Form::label('Supplier') }}
				{{ Form::select('supplier', (isset($supplier) && count($supplier) > 0) ? $supplier : [], old('supplier'),[
					'id' => 'supplier',
					'class' => 'form-control'
				]) }}
			</div>
		</div>
	</div> <!-- end of supplier form -->
</div> 
<!-- suppliers -->

<hr style="color: black; background-color :black;" />

<!-- references and receipts -->
<div class="row">
	<!-- purchase order form -->
	<div class="col-sm-4" id="reference-form">
		<div class="panel panel-default">
			<div class="panel-heading" style="border-radius: 0px;">
				<h4 class="text-muted"><strong>Purchase Order / Agency Purchase Request</strong> </h4>
			</div>
			<div class="panel-body">

				<!-- purchase order number -->
				<div class="col-md-12">
					<div class="form-group">
						{{ Form::label('purchaseorder','Purchase Order Number',[
								'id' => 'purchaseorder-label'
						]) }}
						{{ Form::text('po_no',Input::old('po_no'),[
							'id' => 'po_no',
							'class' => 'form-control',
							'placeholder' => 'Number'
						]) }}
					</div>
					<div id="purchaseorder-details"></div>
					<div class="clearfix"></div>
				</div> 
				<!-- end of purchase order number -->
				<!-- purchase order date -->
				<div class="col-md-12">
					<div class="form-group">
						{{ Form::label('Date') }}
						{{ Form::text('po_date', old('po_date'),[
							'id' => 'po_date',
							'class' => 'form-control',
							'readonly',
							'style' => 'background-color: white;',
							'placeholder' => 'Date'
						]) }}
					</div>
				</div> 
				<!-- end of purchase order date -->
			</div>
		</div>
	</div> 
	<!-- end of purchase order form -->

	<!-- invoice details form -->
	<div class="col-sm-4" id="invoice-form">
		<div class="panel panel-default">
			<div class="panel-heading" style="border-radius: 0px;">
				<h4 class="text-muted"><strong>Invoice Details</strong> </h4>
			</div>
			<div class="panel-body">
				<!-- invoice number -->
				<div class="col-md-12">
					<div class="form-group">
						{{ Form::label('Invoice Number') }}
						{{ Form::text('invoice_no', old('invoice_no'),[
							'id' => 'invoice_no',
							'class' => 'form-control',
							'placeholder' => 'Invoice Number'
						]) }}
					</div>
					<div id="purchaseorder-details"></div>
					<div class="clearfix"></div>
				</div> 
				<!-- end of invoice number -->
				<!-- purchase order date -->
				<div class="col-md-12">
					<div class="form-group">
						{{ Form::label('Invoice Date') }}
						{{ Form::text('invoice_date', old('invoice_date'),[
							'id' => 'invoice_date',
							'class' => 'form-control',
							'readonly',
							'style' => 'background-color: white;',
							'placeholder' => 'Invoice Date'
						]) }}
					</div>
				</div> 
				<!-- end of purchase order date -->
			</div>
		</div>
	</div> 
	<!-- end of invoice details form -->

	<!-- delivery receipt form -->
	<div class="col-sm-4" id="receipt-form">
		<div class="panel panel-default">
			<div class="panel-heading" style="border-radius: 0px;">
				<h4 class="text-muted"><strong> Delivery Receipt </strong> </h4>
			</div>
			<div class="panel-body">
				
				<!-- top -->
				<div class="form-group">
					<div class="col-sm-12">
						{{ Form::label('Delivery Receipt Number ') }}
						{{ Form::text('dr_no', old('dr_no'),[
							'id' => 'dr_no',
							'class' => 'form-control',
							'placeholder' => 'Delivery Receipt Number'
						]) }}
						<div id="receipt-details"></div>
					</div>
				</div> <!-- top -->

				<!-- bottom -->
				<div class="form-group">
					<!-- receipt date -->
					<div class="col-sm-12">
						{{ Form::label('Delivery Date') }}
						{{ Form::text('dr_date', old('dr_date'),[
							'id' => 'dr_date',
							'class' => 'form-control',
							'readonly',
							'style' => 'background-color: white;',
							'placeholder' => 'Delivery  Date'
						]) }}
					</div> <!-- end of receipt date -->
				</div> <!-- bottom -->
			</div>
		</div>
	</div> 
	<!-- end of receipt form -->

</div> 
<!-- references and receipts -->

<hr style="color: black; background-color :black;" />

<!-- supplies list -->
<div class="row">
	<div class="col-sm-4">
	
		<div class="form-group">
			<div class="col-md-12">
				{{ Form::label('stocknumber','Stock Number') }}
			</div>
			<div class="col-md-9">
				<input type="text" value="" id="stocknumber" class="form-control" />
			</div>
			<div class="col-md-1">
				<button type="button" data-toggle="modal" data-target="#addStockNumberModal" class="btn btn-sm btn-primary">Search</button>
			</div>
		</div>

		<!-- supply details -->
		<input type="hidden" id="supply-item" />
		<div id="stocknumber-details"></div>
		<!-- supply details -->

		<!-- quantity -->
		<div class="col-md-12">
			<div class="form-group">
				{{ Form::label('Quantity') }}
				{{ Form::text('quantity','',[
					'id' => 'quantity',
					'class' => 'form-control'
				]) }}
			</div>
		</div> 
		<!-- quantity -->

		<!-- unit cost -->
		<div class="col-md-12">
			<div class="form-group">
				{{ Form::label('Unit Cost') }}
				{{ Form::text('unitcost','',[
					'id' => 'unitcost',
					'class' => 'form-control'
				]) }}
			</div>
		</div> 
		<!-- unit cost -->

		<div class="btn-group" style="margin-bottom: 20px;">
			<button type="button" id="add" class="btn btn-md btn-success"><span class="glyphicon glyphicon-plus"></span> Add</button>
		</div>
	</div>

	<div class="col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="text-muted">Supplies List</h3>
			</div>

			<div class="panel-body">
				<div class="clearfix"></div>

				<div class="col-sm-12">
					<button type="button" id="reset" class="btn btn-md pull-right" style="color: #9D2933; background-color: #ECF0F1; border-color: #e5e5e5; margin-bottom: 10px;">Clear All</button>
				</div>

				<table class="table table-hover table-condensed table-bordered" id="supplyTable">
					<thead>
						<tr>
							<th>Stock Number</th>
							<th>Supply Details</th>
							<th>Quantity Delivered</th>
							<th>Unit Cost</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-center text-muted" colspan="5">*** Nothing Follows ***</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div> 
<!-- supplies list -->

<!-- buttons -->
<div class="row box-footer">
	<div class="col-sm-12">
		<div class="pull-right">
			<div class="btn-group">
				<button type="button" id="accept" class="btn btn-md btn-primary btn-block" data-loading-text="Submitting..." autocomplete="off">Accept Delivery</button>
			</div>
			<div class="btn-group">
				<button type="button" id="cancel" class="btn btn-md btn-default" onclick='window.location.href = "{{ url('/delivery/supply') }}"'>Cancel</button>
			</div>
		</div>
	</div>
</div> 
<!-- buttons -->

<script>
	$('document').ready(function(){

		// //date format
		jQuery.fn.extend ({
			setDate: function(obj){
				var object = $(this);
		 		if(obj == null)
					var date = moment().format('MMM DD, YYYY');
		 		else
		 			var date = moment(obj).format('MMM DD, YYYY');
			object.val(date);
		 	}
		});
		
		//clear all button click
		$('#reset').on('click', function(){
			$('#supplyTable > tbody').html(`
				<tr>
					<td class="text-center text-muted" colspan="5">*** Nothing Follows ***</td>
				</tr>
			`)
		})

		//stock number autocomplete
		$('#stocknumber').autocomplete({
			source: "{{ url("get/inventory/supply/stocknumber") }}"
		})

		//stock number onfocus = false
		$('#stocknumber').on('click focus-in mousein keyup focus-out', function(){
			setStockNumberDetails()
		})
	
		//accept click
		$('#accept').on('click',function() {
			if($('#supplyTable > tbody > tr').length == 0) {
				swal('Blank Field Notice!','Supply table must have atleast 1 item','error')
			} 
			else {
				swal({
					title: "Are you sure?",
					text: "This will no longer be editable once submitted. Do you want to continue?",
					type: "warning",
					showCancelButton: true,
					confirmButtonText: "Yes, submit it!",
					cancelButtonText: "No, cancel it!",
					closeOnConfirm: false,
					closeOnCancel: false
				},
				function(isConfirm){
					if (isConfirm) {
						$('#stockCardForm').submit();
					} 
					else {
						swal("Cancelled", "Operation Cancelled", "error");
					}
				})
			}
		})

		//show stock details
		function setStockNumberDetails() {
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: 'get',
				url: '{{ url('inventory/supply') }}' +  '/' + $('#stocknumber').val(),
				dataType: 'json',
				success: function(response){
					try {
						details = response.data.details
						unit = response.data.unit_name
						@if($type == 'ledger')
						balance = response.data.ledger_balance
						@else
						balance = response.data.stock_balance
						@endif
						$('#supply-item').val(details.toString())
						$('#stocknumber-details').html(`
							<div class="alert alert-success">
								<ul class="list-unstyled">
									<li><strong>Item:</strong> ` + details + ` </li>
									<li><strong>Unit:</strong> <span class="label label-warning">` + unit + `</span> </li>
									<li><strong>Remaining Balance:</strong> `
									+ balance +
									`</li>
								</ul>
							</div>
						`)

						url = "{{ url('inventory/supply')  }}" +  '/' + $('#stocknumber').val() + '/compute/daystoconsume'

						$.getJSON( url, function( data ) {
						$('#daystoconsume').val(data)
						});
											
					} catch (e) {
						$('#stocknumber-details').html(`
							<div class="alert alert-danger">
								<ul class="list-unstyled">
									<li>Invalid Property Number</li>
								</ul>
							</div>
						`)

					}
				}
			})
		}


	$('#add').on('click',function(){
		row = parseInt($('#supplyTable > tbody > tr:last').text())
		stocknumber = $('#stocknumber').val()
		quantity = $('#quantity').val()
		details = $('#supply-item').val()
		unitcost = $('#unitcost').val()
		if(addForm(stocknumber,details,quantity,unitcost))
		{
			$('#stocknumber').val("")
			$('#quantity').val("")
			$('#unitcost').val("")
			$('#stocknumber-details').html("")
		}
	})

function addForm(_stocknumber = "",_info ="" ,_quantity = "",  _unitcost = "" )
	{
		error = false
		$('.stocknumber-list').each(function() {
		    if (_stocknumber == $(this).val())
		    {
		    	error = true;	
		    	return;
		    }
		});

		if(error)
		{
			swal("Error", "Stocknumber already exists", "error");
			return false;
		}

		$('#supplyTable > tbody').prepend(`
			<tr>
				<td><input type="text" class="stocknumber-list form-control text-center" value="` + _stocknumber + `" name="stocknumber[` + _stocknumber + `]" style="border:none;" /></td>
				<td><input type="hidden" class="form-control text-center" value="` + _info + `" name="info[` + _stocknumber + `]" style="border:none;" />` + _info + `</td>
				<td>
					<input type="number" class="form-control text-center" value="` + _quantity + `" name="quantity[` + _stocknumber + `]" style="border:none;"  />
				</td>

				<td>
					<input type="number" step=0.01 class="form-control text-center" value="` + _unitcost + `" name="unitcost[` + _stocknumber + `]" style="border:none;"  />
				</td>
				
				<td>
					<button type="button" class="remove btn btn-md btn-danger text-center"><span class="glyphicon glyphicon-remove"></span></button>
				</td>
			</tr>
		`)

		return true;
	}
		
		//remove supply from list
		$('#supplyTable').on('click','.remove',function() {
			$(this).parents('tr').remove()
		})

		$( "#po_date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			maxAge: 59,
			minAge: 15,
		});

		$( "#invoice_date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			maxAge: 59,
			minAge: 15,
		});

		$( "#dr_date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			maxAge: 59,
			minAge: 15,
		});

		@if(Input::old('po_date'))
			$('#po_date').setDate('{{ Input::old('po_date') }}');
		@else
			$('#po_date').setDate('{{ Carbon\Carbon::now()->toFormattedDateString() }}');
		@endif

		@if(Input::old('invoice_date'))
			$('#invoice_date').setDate('{{ Input::old('invoice_date') }}');
		@else
			$('#invoice_date').setDate('{{ Carbon\Carbon::now()->toFormattedDateString() }}');
		@endif

		@if(Input::old('dr_date'))
			$('#dr_date').setDate('{{ Input::old('dr_date') }}');
		@else
			$('#dr_date').setDate('{{ Carbon\Carbon::now()->toFormattedDateString() }}');
		@endif

		$('#po_date, #invoice_date, #dr_date').on('change', function(){
			$(this).setDate( $(this).val() )
		})

		@if(old('stocknumber') != null)

		function init()	{

			@foreach(old('stocknumber') as $stocknumber)
				addForm("{{ $stocknumber }}","{{ old("info.$stocknumber") }}", "{{ old("quantity.$stocknumber") }}","{{ old("unitcost.$stocknumber") }}")
			@endforeach

		}

		init();

		@endif

		setReferenceLabel( $("#supplier option:selected").text() )

		$('#supplier').on('change',function() {
			setReferenceLabel($("#supplier option:selected").text())
		})

		function setReferenceLabel(supplier) {
			if( supplier == "{{ config('app.main_agency') }}") {
				$('#purchaseorder-label').text('Agency Purchase Request Number')
			}
			else {
				$('#purchaseorder-label').text('Purchase Order Number')
			}
		}

		$('#stocknumber').on('change focusin focusout mousein keyup', function() {
			setStockNumberDetails()
		})

		$('#supplyInventoryTable').on('click','.add-stock',function() {
		$('#stocknumber').val($(this).data('id'))
		$('#addStockNumberModal').modal('hide')
		setStockNumberDetails()
		})

		$('#fundcluster').on('change', function(){
			e.preventDefault()
		})

		$('.fundcluster-item').on('click', function(){
			text =  $(this).val()
			f_text = $('#fundcluster').val()

			if(f_text.length <= 1 )
				text = text
			else
				text = f_text.toString() + "," + text.toString()
			$('#fundcluster').val(text) 
		})
	})
</script>