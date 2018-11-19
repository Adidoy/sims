@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
	    Approval and Issuance :: RIS No.: {{ $request->local }}
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url('request') }}">RIS {{ $request->local }}</a></li>
	    <li class="active">Approval</li>
	  </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
          <table class="table table-hover table-striped table-bordered table-condensed" id="requestTable" cellspacing="0" width="100%">
				    <thead>
                        <tr rowspan="2">
                            <th class="text-left" colspan="15">Request Slip:  <span style="font-weight:normal">{{ $request->local }}</span></th>
                            <th class="text-left" colspan="15">Office:  <span style="font-weight:normal">{{ isset($request->office) ? $request->office->name : 'None' }}</span> </th>
                        </tr>
                        <tr rowspan="2">
                            <th class="text-left" colspan="15">Status:  <span style="font-weight:normal">{{ ($request->status == '') ? ucfirst(config('app.default_status')) : $request->status }}</span> </th>
                            <th class="text-left" colspan="15">Request Processed by:  <span style="font-weight:normal">{{ $request->requestor->fullname }}, {{ $request->requestor->position }}</span> </th>
                        </tr>
                        <tr rowspan="2">
                            <th class="text-left" colspan="15">Purpose:  <span style="font-weight:normal">{{ $request->purpose }}</span> </th>
                            <th class="text-left" colspan="15">Remarks:  <span style="font-weight:normal">{{ $request->remarks }}</span> </th>
                        </tr>
                        
				    </thead>
			    </table>
      <form method="post" action="{{ route('request.accept', $request->id) }}" class="form-horizontal" id="requestForm">
        
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        @include('requests.custodian.forms.action')

      </form>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
@endsection