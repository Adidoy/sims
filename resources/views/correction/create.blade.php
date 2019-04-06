@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
	    Correction
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url('adjustment') }}">Correction</a></li>
	    <li class="active">Create</li>
	  </ol>
	</section>
@endsection

@section('content')
@include('modal.request.supply')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
    {{ Form::open(['method'=>'post','url' => 'correction','class'=>'form-horizontal','id'=>'correctionForm']) }}
        @include('errors.alert')
        @include('correction.form')
      {{ Form::close() }}
    </div><!-- /.box-body -->
  </div><!-- /.box -->
@endsection