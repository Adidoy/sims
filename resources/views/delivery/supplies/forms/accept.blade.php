@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Item Delivery</h3></legend>
			<ul class="breadcrumb">
				<li>Supply Inventory</li>
				<li class="active"><a href="{{ url('delivery/supplies/create/') }}">Item Delivery</a></li>
			</ul>
	</section>
@endsection

@section('content')
	@include('modal.request.supply')
  <div class="box" style="padding:10px;">
  	<div class="box-body">
			{{ Form::open(['method'=>'post','route'=>array('delivery.supply.create'),'class'=>'form-horizontal','id'=>'stockCardForm']) }}
				@include('delivery.supplies.forms.form')
			{{ Form::close() }}
  	</div>
	</div>
@endsection