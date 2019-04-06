@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">{{ $correction->control_number }}</h3></legend>
		<ul class="breadcrumb">
			<li><a href="{{ url('correction') }}">Correction</a></li>
			<li class="active"> {{ $correction->control_number }} </li>
		</ul>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
		<div class="panel panel-body table-responsive">
			<table class="table table-hover table-striped table-bordered table-condensed" id="correctionTable" cellspacing="0" width="100%"	>
				<thead>
		            <tr rowspan="2">
		                <th class="text-left" colspan="3">Control Number:  <span style="font-weight:normal">{{ $correction->control_number }}</span> </th>
		                <th class="text-left" colspan="3">Created By:  <span style="font-weight:normal">{{ $correction->created_by }}</span> </th>
		            </tr>
		            <tr rowspan="2">
		                <th class="text-left" colspan="6">Reasons:  <span style="font-weight:normal">{{ $correction->reasons }}</span> </th>
		            </tr>
		            <tr rowspan="2">
		                <th class="text-left" colspan="6">Remarks:  <span style="font-weight:normal">{{ $correction->remarks }}</span> </th>
		            </tr>
		            <tr rowspan="2">
		                <th class="text-left" colspan="6">Status:  <span style="font-weight:normal">{{ $correction->status }}</span> </th>
		            </tr>
		            <tr>
						<th>Stock Number</th>
						<th>Details</th>
						<th>From</th>
						<th>To</th>
						<th>Remarks</th>
						<th>Status</th>
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

    var table = $('#correctionTable').DataTable({
			language: {
					searchPlaceholder: "Search..."
			},
			"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
			ajax: "{{ url("correction/$correction->id") }}",
			columns: [
					{ data: "stocknumber" },
					{ data: "details" },
					{ data: "pivot.quantity_from" },
					{ data: "pivot.quantity_to" },
					{ data: "pivot.remarks" },
					{ data: "pivot.status" },
			],
    });

    $('div.toolbar').html(`
         <a href="{{ url("correction/$correction->id/print") }}" target="_blank" id="print" class="print btn btn-sm btn-default ladda-button" data-style="zoom-in">
          <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
          <span id="nav-text"> Print</span>
        </a>
    `)

	} );
</script>
@endsection
