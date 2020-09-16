<div class="row">
	<div class="col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading" style="border-radius: 0px;">
				<h4 class="text-muted"><strong>Requisition</stong></h4>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<div class="col-md-12">
						{{ Form::label('Date Requested') }}
						{{ Form::text('date_requested', old('date_requested'),[
							'id' => 'date_requested',
							'class' => 'form-control',
							'readonly',
							'style' => 'background-color: white;',
						]) }}
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						{{ Form::label('Office') }}
						{{ Form::text('office',Input::old('office'),[
							'id' => 'office',
							'class' => 'form-control'
						]) }}
					</div>
				</div>
				<input type="hidden" id="office-code" />
				<div id="office-details"></div>			
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading" style="border-radius: 0px;">
				<h4 class="text-muted"><strong>Issuance</stong></h4>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<div class="col-md-12">
						{{ Form::label('Date Issued') }}
						{{ Form::text('date_issued', old('date_issued'),[
							'id' => 'date_issued',
							'class' => 'form-control',
							'readonly',
							'style' => 'background-color: white;',
						]) }}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						{{ Form::label('Issued by') }}
						{{ Form::select('issued_by', (isset($issued_by) && count($issued_by) > 0) ? $issued_by : [], old('issued_by'),[
							'id' => 'issued_by',
							'class' => 'form-control'
						]) }}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading" style="border-radius: 0px;">
				<h4 class="text-muted"><strong>Release</stong></h4>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<div class="col-md-12">
						{{ Form::label('Date Released') }}
						{{ Form::text('date_released', old('date_released'),[
							'id' => 'date_released',
							'class' => 'form-control',
							'readonly',
							'style' => 'background-color: white;',
						]) }}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						{{ Form::label('Released by') }}
						{{ Form::select('released_by', (isset($released_by) && count($released_by) > 0) ? $released_by : [], old('released_by'),[
							'id' => 'released_by',
							'class' => 'form-control'
						]) }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
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
				<button type="button" data-toggle="modal" data-target="#addStockNumberModal" class="btn btn-md btn-primary">Search</button>
			</div>
		</div>
		<input type="hidden" id="supply-item" />
		<div id="stocknumber-details"></div>
		<div class="form-group">
			<div class="col-md-12">
				{{ Form::label('Quantity Requested') }}
				{{ Form::text('quantity','',[
					'id' => 'quantity_requested',
					'class' => 'form-control'
				]) }}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				{{ Form::label('Quantity Released') }}
				{{ Form::text('quantity','',[
					'id' => 'quantity_released',
					'class' => 'form-control'
				]) }}
			</div>
		</div>
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
							<th rowspan="2">Stock Number</th>
							<th rowspan="2">Information</th>
							<th colspan="2" style="text-align:center;">Quantity</th>
							<th rowspan="2"></th>
						</tr>
						<tr>
							<th>Requested</th>
							<th>Released</th>
						</tr
					</thead>
					<tbody>
						<tr>
							<td class="text-center text-muted" colspan="@if($type == 'ledger') 5 @if($title == 'Release') 6 @else 5 @endif  @else 4 @endif">*** Nothing Follows ***</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="row box-footer">
	<div class="col-sm-12">
		<div class="pull-right">
			<div class="btn-group">
				<button type="button" id="accept" class="btn btn-md btn-primary btn-block" data-loading-text="Submitting..." autocomplete="off">{{ $title }}</button>
			</div>
			<div class="btn-group">
				<button type="button" id="cancel" class="btn btn-md btn-default" onclick='window.location.href = "{{ url('inventory/supply') }}"'>Cancel</button>
			</div>
		</div>
	</div>
</div>

<script>
$('document').ready(function(){

	jQuery.fn.extend({
	  setDate: function(obj){
			var object = $(this);

			if(obj == null)
				var date = moment().format('MMM DD, YYYY');
			else
				var date = moment(obj).format('MMM DD, YYYY');

			object.val(date);
		}
	});

	$('#reset').on('click', function(){
		$('#supplyTable > tbody').html(`
				<tr>
					<td class="text-center text-muted" colspan="@if($type == 'ledger') 5 @if($title == 'Release') 6 @else 5 @endif  @else 4 @endif">*** Nothing Follows ***</td>
				</tr>
		`)
	})


	$('#stocknumber').autocomplete({
		source: "{{ url("get/inventory/supply/stocknumber") }}"
	})

	$('#stocknumber').on('click focus-in mousein keyup focus-out', function(){
		setStockNumberDetails()
	})

	$('#office').autocomplete({
		source: "{{ url('maintenance/get/office/autocomplete') }}"
	})
 
	$('#office').on('change mousein keyup focusin',function(){
		setOfficeCodeDetails()
	})

	function setStockNumberDetails(){
		$.ajax({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },
			type: 'get',
			url: '{{ url('inventory/supply') }}' +  '/' + $('#stocknumber').val(),
			dataType: 'json',
			success: function(response){
				try{
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

					// url = "{{ url('inventory/supply')  }}" +  '/' + $('#stocknumber').val() + '/compute/daystoconsume'

					// $.getJSON( url, function( data ) {
					//   $('#daystoconsume').val(data)
					// });
					    				
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

	function setOfficeCodeDetails(){
		$.ajax({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },
			type: 'get',
			url: '{{ url('maintenance/get/office') }}' + '/' + $('#office').val(),
			dataType: 'json',
			success: function(response){
				try{
					code = response.code
					name = response.name
					//$('#office-code').val(details.toString())
					$('#office-details').html(`
						<div class="alert alert-success">
							<ul class="list-unstyled">
								<li><strong>Code:</strong> ` + code + ` </li>
								<li><strong>Office:</strong> <span class="label label-warning">` + name + `</span> </li>
							</ul>
						</div>
					`)
					    				
				} catch (e) {
					$('#office-details').html(`
						<div class="alert alert-danger">
							<ul class="list-unstyled">
								<li>Invalid Office Code</li>
							</ul>
						</div>
					`)

				}
			}
		})
	}

	$( "#date_requested" ).datepicker({
		  changeMonth: true,
		  changeYear: true,
		  maxAge: 59,
		  minAge: 15,
	});

	$( "#date_issued" ).datepicker({
		  changeMonth: true,
		  changeYear: true,
		  maxAge: 59,
		  minAge: 15,
	});

	$( "#date_released" ).datepicker({
		  changeMonth: true,
		  changeYear: true,
		  maxAge: 59,
		  minAge: 15,
	});

	@if(Input::old('date_requested'))
		$('#date_requested').setDate('{{ Input::old('date_requested') }}');
	@else
		$('#date_requested').setDate('{{ Carbon\Carbon::now()->toFormattedDateString() }}');
	@endif

	@if(Input::old('date_issued'))
		$('#date_issued').setDate('{{ Input::old('date_issued') }}');
	@else
		$('#date_issued').setDate('{{ Carbon\Carbon::now()->toFormattedDateString() }}');
	@endif

	@if(Input::old('date_released'))
		$('#date_released').setDate('{{ Input::old('date_released') }}');
	@else
		$('#date_released').setDate('{{ Carbon\Carbon::now()->toFormattedDateString() }}');
	@endif

	$('#add').on('click',function(){
		row = parseInt($('#supplyTable > tbody > tr:last').text())

		stocknumber = $('#stocknumber').val()
		quantity_requested = $('#quantity_requested').val()
		quantity_released = $('#quantity_released').val()
		details = $('#supply-item').val()
		if(addForm(stocknumber, details, quantity_requested, quantity_released))
		{
			$('#stocknumber').val("")
			$('#quantity_requested').val("")
			$('#quantity_released').val("")
			$('#stocknumber-details').html("")
		}
	})

	function addForm(_stocknumber = "", _info ="" , _quantity_requested = "", _quantity_released = "")
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
				<td><input type="text" readonly class="stocknumber-list form-control text-center" value="` + _stocknumber + `" name="stocknumber[` + _stocknumber + `]" style="border:none;" /></td>
				<td><input type="hidden" class="form-control text-center" value="` + _info + `" name="info[` + _stocknumber + `]" style="border:none;" />` + _info + `</td>
				<td>
					<input type="number" class="form-control text-center" value="` + _quantity_requested + `" name="quantity_requested[` + _stocknumber + `]" style="border:none;"  />
				</td>
				<td>
					<input type="number" class="form-control text-center" value="` + _quantity_released + `" name="quantity_released[` + _stocknumber + `]" style="border:none;"  />
				</td>
				<td>
					<button type="button" class="remove btn btn-md btn-danger text-center"><span class="glyphicon glyphicon-remove"></span></button>
				</td>
			</tr>
		`)

		return true;
	}

	$('#supplyTable').on('click','.remove',function(){
		$(this).parents('tr').remove()
	})

	@if(null !== old('stocknumber'))

	function init()
	{

		@foreach(old('stocknumber') as $stocknumber)

		addForm("{{ $stocknumber }}","{{ old("info.$stocknumber") }}", "{{ old("quantity.$stocknumber") }}", "{{ old("daystoconsume.$stocknumber") }}" @if($type == 'ledger') , "{{ old("unitcost.$stocknumber") }}" @endif)
		@endforeach

	}

	init();

	@endif

    $('#stocknumber').on('change focusin focusout mousein keyup', function(){
    	setStockNumberDetails()
    })

    $('#supplyInventoryTable').on('click','.add-stock',function(){
      $('#stocknumber').val($(this).data('id'))
      $('#addStockNumberModal').modal('hide')
      setStockNumberDetails()
    })

	$('#accept').on('click',function(){
		if($('#supplyTable > tbody > tr').length == 0)
		{
			swal('Blank Field Notice!','Supply table must have atleast 1 item','error')
		} else {
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
	          } else {
	            swal("Cancelled", "Operation Cancelled", "error");
	          }
	        })
		}
	})
})
</script>