@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
	    New Request
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url('request/create') }}">Request</a></li>
	    <li class="active">Create</li>
	  </ol>
	</section>
@endsection

@section('content')
@include('modal.request.supply')

<!-- Default box -->
  <div class="box">
    <div class="box-body" style="padding: 15px;">
      <form method="post" action="{{ route('request.store') }}" class="form-horizontal" id="requestForm">
        
        {{ csrf_field() }}

        @include('requests.client.forms.form')

      </form>

    </div><!-- /.box-body -->
  </div><!-- /.box -->
@endsection