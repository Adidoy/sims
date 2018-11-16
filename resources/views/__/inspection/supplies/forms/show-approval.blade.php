@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend>
            <h3 class="text-muted">Inspection and Acceptance Report No.: {{ $inspection->local }}</h3>
        </legend>
		<ul class="breadcrumb">
			<li>Inspection and Acceptance Report</a></li>
			<li class="active"> {{ $inspection->local }} </li>
		</ul>
	</section>
@endsection 

@section('content')
    <div class="box">
        <div class="box-body">
		    <div class="panel panel-body table-responsive">
                <div >
                    @if( isset($inspection->property_custodian_acknowledgement_date) && isset($inspection->inspection_approval_date) )
                        <a href="{{ url('inspection/supply/'.$inspection->id.'/print') }}" style="text-align:justify; margin:left: 15em; font-size:11pt;" target="_blank" id="print" class="print btn btn-sm btn-default ladda-button" data-style="zoom-in">
                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                            <span id="nav-text"> Print Inspection Report</span>
                        </a>
                        <br/><br/>
                    @endif
                </div>
			    <table class="table table-hover table-striped table-bordered table-condensed" id="headerTable" cellspacing="0" width="100%">
                    <tr>
                        <th>Delivery Acceptance No.:  <span style="font-weight:normal">{{ isset($inspection->dai) ? $inspection->dai : 'None' }}</span> </th>
                        <th>Approved by:  <span style="font-weight:normal">{{ isset($inspection->approval) ? $inspection->approval : 'For Approval' }}</span> </th>
                    </tr>
                    <tr>
                        <th>Inspected by:  <span style="font-weight:normal">{{ isset($inspection->inspection_personnel) ? $inspection->inspection_personnel : 'None' }}</span> </th>
                        <th>Date Approved:  <span style="font-weight:normal">{{ isset($inspection->approval_date) ? $inspection->approval_date : 'N/A' }}</span> </th>
                    </tr>
                    <tr>
                        <th>Date Inspected:  <span style="font-weight:normal">{{ isset($inspection->date_inspected) ? $inspection->date_inspected : 'None' }}</span> </th>
                        <th>Acknowledged by:  <span style="font-weight:normal">{{ isset($inspection->acknowledgement) ? $inspection->acknowledgement : 'For Acknowledgement' }}</span> </th>
                    </tr>
                    <tr>
                        <th>Inspector Remarks:  <span style="font-weight:normal">{{ isset($inspection->remarks) ? $inspection->remarks : 'None' }}</span> </th>
                        <th>Date Acknowledged:  <span style="font-weight:normal">{{ isset($inspection->acknowledgement_date) ? $inspection->acknowledgement_date : 'N/A' }}</span> </th>
                    </tr>
			    </table>
				<hr style="color: black; background-color :black;" />
			    <table class="table table-hover table-striped table-bordered table-condensed" id="inspectionTable" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th  class="text-center">Stock Number</th>
                            <th  class="text-center">Information</th>
                            <th  class="text-center">Unit</th>
                            <th  class="text-center">Cost</th>
                            <th  class="text-center">Quantity Passed</th>
                            <th  class="text-center">Quantity Failed</th>
                            <th  class="text-center">Comment</th>
                        </tr>
                    </thead>
			    </table>
		    </div>
            @if(Auth::user()->access == 9)
                @if( !isset($inspection->inspection_approval_date) )
                    {{ Form::open(['method'=>'post','route' => array('inspection.approve', $inspection->id, "approve"),'class'=>'form-horizontal','id'=>'inspectForm']) }}  
                        <div class="pull-right">
                            <div class="btn-group">
                                <button type="button" id="approve" class="btn btn-md btn-primary btn-block">Approve</button>
                            </div>
                            <div class="btn-group">
                                <button type="button" id="cancel" class="btn btn-md btn-default" onclick='window.location.href = "{{ url('inspection/view/supply') }}"'>Cancel</button>
                            </div>
                        </div>
                    {{ Form::close() }}
                @else
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" id="cancel" class="btn btn-md btn-default" onclick='window.location.href = "{{ url('inspection/view/supply') }}"'>Back</button>
                        </div>
                    </div>
                @endif
            @endif
            @if(Auth::user()->access == 4)
                @if( !isset($inspection->property_custodian_acknowledgement_date) && isset($inspection->inspection_approval_date))
                    {{ Form::open(['method'=>'post','route' => array('inspection.approve', $inspection->id, "acknowledge"),'class'=>'form-horizontal','id'=>'inspectForm']) }}  
                        <div class="pull-right">
                            <div class="btn-group">
                                <button type="button" id="approve" class="btn btn-md btn-primary btn-block">Acknowledge</button>
                            </div>
                            <div class="btn-group">
                                <button type="button" id="cancel" class="btn btn-md btn-default" onclick='window.location.href = "{{ url('inspection/view/supply') }}"'>Cancel</button>
                            </div>
                        </div>
                    {{ Form::close() }}
                @else
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" id="cancel" class="btn btn-md btn-default" onclick='window.location.href = "{{ url('inspection/view/supply') }}"'>Back</button>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@section('after_scripts')
    <script>
        $(document).ready(function() 
        {
            var table = $('#inspectionTable').DataTable({
                language: {
                    searchPlaceholder: "Search..."
                },
                "processing": true,
                ajax: "{{ url("inspection/view/supply/$inspection->id") }}",
                columnDefs: [{
					targets: [3,4,5],
					className: "text-right"
				    }
			    ],
                columns: [
                    { data: "stocknumber" },
                    { data: "details" },
                    { data: "unit.name" },
                    { data: "pivot.unit_cost" },
                    { data: "pivot.quantity_passed" },
                    { data: "pivot.quantity_failed" },
                    { data: "pivot.comment" },
                ],
            });
            $('#approve').on('click',function() {
                swal({
                    title: "Are you sure?",
                    text: "This will no longer be editable once submitted. Do you want to continue?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, submit it!",
                    cancelButtonText: "No, cancel it!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm){
                    if (isConfirm) {
                        $('#inspectForm').submit();
                    } 
                    else {
                        swal("Cancelled", "Operation Cancelled", "error");
                    }
                })
		    });
        });
    </script>
@endsection
