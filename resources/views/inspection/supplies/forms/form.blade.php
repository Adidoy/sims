    <div class="box">
        <div class="box-body">
            @include('errors.alert')
            <legend><h3 class="text-muted">Inspection and Acceptance Report for <input type="hidden" name="deliveryLocal" value="{{ $delivery->local }}" class="form-control"  />{{ $delivery->local }}</h3></legend>
            <br />
            <table class="col-sm-12 table table-hover table-condensed table-bordered" id="supplyTable">
                <thead>
                    <tr>
                        <th class="col-sm-1">Stock Number</th>
                        <th class="col-sm-1">Information</th>
                        <th class="col-sm-1">Unit of Measure</th>
                        <th class="col-sm-1">Unit Cost</th>
                        <th class="col-sm-1">Delivered Quantity</th>
                        <th class="col-sm-1">Quantity Passed</th>
                        <th class="col-sm-1">Comment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($delivery->supplies as $supply)
                        <tr>
                            <td><input type="hidden" name="stocknumber[{{ $supply->stocknumber }}]" value="{{ $supply->stocknumber }}" class="form-control"  />{{ $supply->stocknumber }}</td>
                            <td>{{ $supply->details }}</td>
                            <td>{{ $supply->unit->name }}</td>
                            <td><input type="hidden" name="unit_cost[{{ $supply->stocknumber }}]" value="{{ $supply->pivot->unit_cost }}" class="form-control"  />{{ $supply->pivot->unit_cost }}</td>
                            <td><input type="hidden" class="form-control" name="quantity[{{ $supply->stocknumber }}]"  value="{{ $supply->pivot->quantity_delivered }}"  /> {{ $supply->pivot->quantity_delivered }} </td>
                            <td><input type="number" name="quantity_passed[{{ $supply->stocknumber }}]" max="{{ $supply->pivot->quantity_delivered }}" min="0" class="form-control" value="{{ $supply->pivot->quantity_delivered }}"  /></td>
                            <td><input type="text" name="passed_comment[{{ $supply->stocknumber }}]" class="form-control"/></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="form-group">
                <div class="col-md-12 ">
                    {{ Form::label('remarks','Remarks') }}<br>
                    {{ Form::text('remarks', '',[
                        'class'=>'form-control',
                        'id' => 'purpose',
                        'placeholder'=>'Provide overall remarks for this inspection...' ])
                    }}
                </div>
            </div>
            <div class="pull-right">
                <br />
                <div class="btn-group">
                    <button type="button" id="save" class="btn btn-md btn-danger btn-block">Submit</button>
                </div>
                <div class="btn-group">
                    <button type="button" id="cancel" class="btn btn-md btn-default" onclick='window.location.href = "{{ url('/inspection/supply') }}"'>Cancel</button>
                </div>
            </div>
        </div>
    </div>

@section('after_scripts')
    <script>
        $('document').ready(function(){
            deliveryLocal = $('#deliveryLocal').val()
            $('#save').on('click',function() {
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
                        $('#inspectForm').submit();
                    } 
                    else {
                        swal("Cancelled", "Operation Cancelled", "error");
                    }
                })
		    })
        })      
    </script>
@endsection