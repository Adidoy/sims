@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Item Delivery</h3></legend>
		<ul class="breadcrumb">
			<li>Supply Inventory</li>
			<li class="active"><a href="{{ url('delivery/supply/create/') }}">Item Delivery</a></li>
		</ul>
	</section>
@endsection

@section('content')
@include('modal.request.supply')
<!-- Default box -->
  <div class="box" style="padding:10px;">
    <div class="box-body">
		{{ Form::open(['method'=>'post','route'=>array('delivery.supply.create'),'class'=>'form-horizontal','id'=>'stockCardForm']) }}
		@include('errors.alert')
		@include('delivery.supplies.form')
		{{ Form::close() }}
    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection