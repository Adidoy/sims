@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Late Entry - Requisition and Issuance</h3></legend>
		<ul class="breadcrumb">
			<li>Requisition and Issuance</li>
			<li class="active">Late Entry</li>
		</ul>
	</section>
@endsection

@section('content')
@include('modal.request.supply')
<!-- Default box -->
  <div class="box" style="padding:10px;">
    <div class="box-body">
		{{ Form::open(['method'=>'post','route'=>array('supply.stockcard.release'),'class'=>'form-horizontal','id'=>'stockCardForm']) }}
        @include('errors.alert')
		@include('inventory.supply.form')
		{{ Form::close() }}
    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection