@extends('backpack::layout')

@section('after_styles')
    <style>
      th , tbody {
        text-align: center;
      }
    </style>
@endsection

@section('header')
	<section class="content-header">
	  <h1>
	    Inspection and Acceptance
	  </h1>
	  <ol class="breadcrumb">
	    <li>Inspection and Acceptance</li>
	    <li class="active">Supplies</li>
	  </ol>
	</section>
@endsection

@section('content')
    <div class="box">
        <div class="box-body">
            @include('errors.alert')
            <legend><h3 class="text-muted">Inspection and Acceptance Report for {{ $delivery->local }}</h3></legend>
            <br />
            <table class="col-sm-12 table table-hover table-condensed table-bordered" id="supplyTable">
                <thead>
                    <tr>
                        <th class="col-sm-1">Stock Number</th>
                        <th class="col-sm-1">Information</th>
                        <th class="col-sm-1">Unit Cost</th>
                        <th class="col-sm-1">Delivered Quantity</th>
                        <th class="col-sm-1">Quantity Passed</th>
                        <th class="col-sm-1">Comment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($delivery->supplies as $supply)
                        <tr>
                            <td>{{ $supply->stocknumber }}<input type="hidden" name="stocknumber[]" value="{{ $supply->stocknumber }}"/></td>
                            <td>{{ $supply->details }}</td>
                            <td>{{ $supply->pivot->unit_cost }}</td>
                            <td>{{ $supply->pivot->quantity_delivered }}</td>
                            <td><input type="number" name="quantity[{{ $supply->stocknumber }}]" max="{{ $supply->pivot->quantity_delivered }}" min="0" class="form-control" value="{{ $supply->pivot->quantity_delivered }}"  /></td>
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
                        'placeholder'=>'Remarks' ])
                    }}
                </div>
            </div>
            <div class="pull-right">
                <div class="btn-group">
                    <button type="button" id="save" class="btn btn-md btn-danger btn-block">Save</button>
                </div>
                <div class="btn-group">
                    <button type="button" id="cancel" class="btn btn-md btn-default">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script>
    jQuery(document).ready(function($) {

        $('#save').on('click',function(){
        console.log($('#supplyTable > tbody > tr').length)
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
                    $('#requestForm').submit();
                } else {
                    swal("Cancelled", "Operation Cancelled", "error");
                }
                })
        }
        })
    });
    </script>
@endsection