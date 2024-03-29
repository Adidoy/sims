@extends('backpack::layout')
 
@section('header')
	<section class="content-header">
		<legend>
            <h3 class="text-muted">Requisition and Issuance No.: {{ $request->local_id }}</h3>
        </legend>
		<ul class="breadcrumb">
			<li><a href="{{ url('request') }}">Request</a></li>
			<li class="active"> {{ $request->local }} </li>
		</ul>
	</section>
@endsection

@section('content')
<!-- Default box -->
    <div class="box">
        <div class="box-body">
		    <div class="panel panel-body table-responsive">
                <div class="text-center">
                    @if(isset($request->status))
                        @if($request->status == 'Pending')
                            <a href="{{ url("request/client/$request->id/cancel") }}" style="text-align:justify; margin:left: 15em; font-size:11pt;" class="btn btn-danger btn-sm">
                                <i class="fa fa-hand-stop-o" aria-hidden="true"></i> Cancel Request
                            </a>
                            <br /><br />
                        @elseif (($request->status == 'Approved') || ($request->status == 'Released'))
                            <a href="{{ url("../reports/requests/$request->local/print") }}" style="text-align:justify; margin:left: 15em; font-size:11pt;" target="_blank" id="print" class="print btn btn-sm btn-default ladda-button" data-style="zoom-in">
                                <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                <span id="nav-text"> Download Requisition and Issue Slip</span>
                            </a>
                            <br /><br />
                        @endif
                    @endif
                </div>
			    <table class="table table-hover table-striped table-bordered table-condensed" id="requestTable" cellspacing="0" width="100%">
				    <thead>
                        <tr rowspan="2">
                            <th class="text-left" colspan="2">Request Slip:  <span style="font-weight:normal">{{ $request->local_id }}</span></th>
                            <th class="text-left" colspan="3">Office:  <span style="font-weight:normal">{{ isset($request->office) ? $request->office->code : 'None' }}</span> </th>
                        </tr>
                        <tr rowspan="2">
                            <th class="text-left" colspan="2">Status:  <span style="font-weight:normal">{{ ($request->status == '') ? ucfirst(config('app.default_status')) : $request->status }}</span> </th>
                            <th class="text-left" colspan="3">Processed by:  <span style="font-weight:normal">{{ $request->requestor->fullname }}, {{ $request->requestor->position }}</span> </th>
                        </tr>
                        <tr rowspan="2">
                            <th class="text-left" colspan="2">Purpose:  <span style="font-weight:normal">{{ $request->purpose }}</span> </th>
                            <th class="text-left" colspan="3">Remarks:  <span style="font-weight:normal">{{ $request->remarks }}</span> </th>
                        </tr>
                        <tr>          
						    <th>Stock Number</th>
						    <th>Details</th>
						    <th>Quantity Requested</th>
						    <th>Quantity Issued</th>
						    <th>Notes</th>
					    </tr>
				    </thead>
			    </table>
		    </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
@endsection

@section('after_scripts')
    <script>
        $(document).ready(function() {
            var table = $('#requestTable').DataTable({
                language: {
                    searchPlaceholder: "Search..."
                },
                "dom":  "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                "processing": true,
                ajax: "{{ url("request/client/$request->id") }}",
                columns: [
                        { data: "stocknumber" },
                        { data: "details" },
                        { data: "pivot.quantity_requested" },
                        { data: "pivot.quantity_issued" },
                        { data: "pivot.comments" }
                ],
            });
        });
    </script>
@endsection
