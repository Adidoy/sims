@extends('backpack::layout')
 
@section('header')
	<section class="content-header">
		<legend>
            <h3 class="text-muted">{{ $request->local }}</h3>
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
                        @if($request->status == 'Approved')
                            <a href="{{ url("request/custodian/$request->id/print") }}" style="text-align:justify; font-size:11pt;" target="_blank" id="print" class="print btn btn-sm btn-default ladda-button" data-style="zoom-in">
                                <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                <span id="nav-text"> Download Requisition and Issuance Slip</span>
                            </a>
                            <a href="{{ url("request/custodian/$request->id/release") }}" style="text-align:justify; font-size:11pt;" class="btn btn-danger btn-sm">
                                <span class="ladda-label"><i class="glyphicon glyphicon-share-alt"></i> Release Supplies</span>
                            </a>
                            <br /><br />
                        @elseif (($request->status == 'Released'))
                            <a href="{{ url("request/custodian/$request->id/print") }}" style="text-align:justify; font-size:11pt;" target="_blank" id="print" class="print btn btn-sm btn-default ladda-button" data-style="zoom-in">
                                <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                <span id="nav-text"> Download Requisition and Issuance Slip</span>
                            </a>
                            <br /><br />
                        @endif
                    @endif
                </div>
                <table class="table table-hover table-striped table-bordered table-condensed" id="requestTable" cellspacing="0" width="100%">
				    <thead>
                        <tr rowspan="2">
                            <th class="text-left" colspan="8">Request Slip:  <span style="font-weight:normal">{{ $request->local }}</span></th>
                            <th class="text-left" colspan="8">Office:  <span style="font-weight:normal">{{ isset($request->office) ? $request->office->name : 'None' }}</span> </th>
                        </tr>
                        <tr rowspan="2">
                            <th class="text-left" colspan="8">Status:  <span style="font-weight:normal">{{ ($request->status == '') ? ucfirst(config('app.default_status')) : $request->status }}</span> </th>
                            <th class="text-left" colspan="8">Request Processed by:  <span style="font-weight:normal">{{ $request->requestor->fullname }}, {{ $request->requestor->position }}</span> </th>
                        </tr>
                        <tr rowspan="2">
                            <th class="text-left" colspan="8">Purpose:  <span style="font-weight:normal">{{ $request->purpose }}</span> </th>
                            <th class="text-left" colspan="8">Remarks:  <span style="font-weight:normal">{{ $request->remarks }}</span> </th>
                        </tr>
                        
				    </thead>
			    </table>
                <table class="table table-hover table-striped table-bordered table-condensed" id="requestDetailsTable" cellspacing="0" width="100%">
				    <thead>
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
            var table = $('#requestDetailsTable').DataTable({
                language: {
                    searchPlaceholder: "Search..."
                },
                "dom":  "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                "processing": true,
                ajax: "{{ url("request/custodian/$request->id") }}",
                columns: [
                        { data: "stocknumber" },
                        { data: "details" },
                        { data: "pivot.quantity_requested" },
                        { data: "pivot.quantity_issued" },
                        { data: "pivot.comments" },
                ],
            });

            @if(Auth::user()->access == 1 || Auth::user()->access == 6)
                @if($request->status != null && $request->status != 'released')
                    $('#expire').on('click',function(){
                    id = $(this).data('id');
                    swal({
                        title: 'Expire Request {{ $request->code }}?',
                        text: 'This will cancel the request. Do you want to continue?',
                        type: 'warning',
                        showLoaderOnConfirm: true,
                        showCancelButton: true,
                        closeOnConfirm: false,
                        disableButtonsOnConfirm: true,
                        confirmLoadingButtonColor: '#DD6B55'
                    }, function(){
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'post',
                            url: '{{ url("request/$request->id/expire/") }}',
                            data: {
                                'id': id
                            },
                            dataType: 'json',
                            success: function(response) {
                                if(response == 'success') {
                                    swal('Operation Successful','Operation Complete please reload the page!','success'),
                                    location.reload();
                                }
                                else {
                                    swal('Operation Unsuccessful','Error occurred while processing your request','error')
                                }
                            },
                            error: function(){
                                swal('Operation Unsuccessful','Error occurred while processing your request','error')
                            }
                        })
                    });
                    });
                @endif
            @endif
        });
    </script>
@endsection
