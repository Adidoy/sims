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
                            <th class="col-sm-1">Stock Number</th>
                            <th class="col-sm-1">Information</th>
                            <th class="col-sm-1">Unit</th>
                            <th class="col-sm-1">Cost</th>
                            <th class="col-sm-1">Quantity Passed</th>
                            <th class="col-sm-1">Quantity Failed</th>
                            <th class="col-sm-1">Comment</th>
                        </tr>
                    </thead>
			    </table>
		    </div>
            {{ Form::open(['method'=>'post','route'=>array('inspection.approve', $inspection->id),'class'=>'form-horizontal','id'=>'inspectForm']) }}
            <div class="pull-right">
                <br />
                <input type="hidden" name="inspectionID" value="{{ $inspection->id }}" class="form-control"  />
                <div class="btn-group">
                    <button type="button" id="approve" class="btn btn-md btn-primary btn-block">Submit</button>
                </div>
                <div class="btn-group">
                    <button type="button" id="cancel" class="btn btn-md btn-default" onclick='window.location.href = "{{ url('/inspection/supply') }}"'>Cancel</button>
                </div>
            </div>
            {{ Form::close() }}
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
		    })
        });
        
    </script>
@endsection
