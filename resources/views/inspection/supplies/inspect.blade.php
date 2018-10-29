@extends('backpack::layout')

@section('header')
    <section class="content-header">
	    <h1>Inspection and Acceptance</h1>
	    <ol class="breadcrumb">
	        <li>Inspection and Acceptance</li>
            <li>Supplies</li>
	        <li class="active">{{$delivery->local}}</li>
	    </ol>
	</section>
@endsection

@section('content')
    <div class="box" style="padding:10px;">
        <div class="box-body">
            {{ Form::open(['method'=>'post','route'=>array('inspection.accept'),'class'=>'form-horizontal','id'=>'inspectForm']) }}
		        @include('errors.alert')
		        @include('inspection.supplies.form')
		    {{ Form::close() }}
        </div>
    </div>
@endsection