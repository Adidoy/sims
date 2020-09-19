@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
	    Create Inventory Adjustment - Issuance
	  </h1>
	  <ol class="breadcrumb">
	    <li>Adjustment</li>
	    <li class="active">Issuance</li>
	  </ol>
	</section>
@endsection

@section('content')
@include('modal.request.supply')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
    {{ Form::open(['method'=>'post','route'=>array('adjustment.issue.create'),'class'=>'form-horizontal','id'=>'adjustmentForm']) }}
        @include('errors.alert')
        @include('adjustment.formIssue')
      {{ Form::close() }}
    </div><!-- /.box-body -->
  </div><!-- /.box -->
@endsection