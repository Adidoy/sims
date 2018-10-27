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
<!-- Default box -->
  <div class="box">
    <div class="box-body">
    
     
      @include('errors.alert')
      <legend><h3 class="text-muted">Inspection and Acceptance for {{ $delivery->local }}</h3></legend>
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
            <th class="col-sm-1">Quantity Failed</th>
            <th class="col-sm-1">Comment</th>
          </tr>
        </thead>
        <tbody>
            @foreach($delivery->supplies as $supply)
                <tr>
                    <td>{{ $supply->stocknumber }}<input type="hidden" name="stocknumber[]" value="{{ $supply->stocknumber }}"</td>
                    <td>{{ $supply->details }}</td>
                    <td>{{ $supply->pivot->unit_cost }}</td>
                    <td>{{ $supply->pivot->quantity_delivered }}</td>
                    <td><input type="number" name="quantity[{{ $supply->stocknumber }}]" class="form-control" value="{{ $supply->pivot->quantity_delivered }}"  /></td>
                    <td><input type="text" name="passed_comment[{{ $supply->stocknumber }}]" class="form-control"/></td>
                    <td><input type="number" name="quantity_failed[{{ $supply->stocknumber }}]" class="form-control" value="0"  /></td>
                    <td><input type="text" name="failed_comment[{{ $supply->stocknumber }}]" class="form-control"/></td>
                </tr>
            @endforeach
        </tbody>
      </table>
      <div class="pull-right">
        <div class="btn-group">
          <button type="button" id="approve" class="btn btn-md btn-danger btn-block">Release</button>
        </div>
        <div class="btn-group">
          <button type="button" id="cancel" class="btn btn-md btn-default">Cancel</button>
        </div>
      </div>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
@endsection