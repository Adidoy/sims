@extends('backpack::layout')

@section('header')
	<section class="content-header">
    <legend><h3 class="text-muted">Reports on Supplies and Materials Issued</h3></legend>
	</section>
@endsection

@section('content')
  <div class="box" style="padding:10px;">
		<div class="form-group">
			<div class="col-md-3">
				<br />
			</div>
            <form method="post">
            <!-- <form method="post" action="{{ route('rsmi.submit') }}"> -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="col-md-3">
                    {{ Form::label('lblPeriod','Select Period:') }}
                    <select id="period" name = "period" class="form-control form-control-md" style="width:100%; font-size:100%">
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <br />
                    <button type="button" id="generate" class="btn btn-md btn-primary" style="width:100%">
                        <span class="glyphicon glyphicon-print" href="{{ url('reports/rsmi/print') }}" target="_blank" aria-hidden="true" ></span>
                        <span id="nav-text"> Print Report</span>
                    </button>
                </div>
            </form>
            <div class="col-md-3">
				<br />
			</div>
		</div>
		<br /><br /><br /><br />
	  <div class="box-body">
			<table class="table table-hover table-striped table-bordered table-condensed table-responsive" id="rsmiTable" cellspacing="0" width="100%">
				<thead>
					<th class="col-sm-1">RIS No.</th>
					<th class="col-sm-1">Responsibility Center Code</th>
					<th class="col-sm-1">Stock No.</th>
                    <th class="col-sm-1">Item</th>
                    <th class="col-sm-1">Unit</th>
                    <th class="col-sm-1">Quantity</th>
				</thead>
			</table>
    </div>
  </div>
@endsection

@section('after_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#rsmiTable').DataTable({
            pageLength: 100,
            "processing": true,
            columnDefs:[
                { targets: 'no-sort', orderable: false },
            ],
            language: {
                searchPlaceholder: "Search..."
            },
            ajax: '{{ url("reports/rsmi/") }}',
            columns: [
                { data: "local" },
                { data: "office" },
                { data: "stocknumber" },
                { data: "details" },
                { data: "name" },
                { data: "quantity_issued" },
            ],
        });
    });
    $('#period').on('change', function() {
        $('#rsmiTable').dataTable().fnDestroy();
        var table = $('#rsmiTable').DataTable({
        pageLength: 100,
        "processing": true,
        columnDefs:[
            { targets: 'no-sort', orderable: false },
        ],
        language: {
                searchPlaceholder: "Search..."
        },
        ajax: '{{ url("reports/rsmi/getRecords") }}' + '/' + $('#period').val(),
        columns: [
                { data: "local" },
                { data: "office" },
                { data: "stocknumber" },
                { data: "details" },
                { data: "name" },
                { data: "quantity_issued" },
            ],
        });			
    });
    $('#generate').on('click', function() {
        var url = '{{ url("../reports/rsmi") }}' + '/' + $('#period').val() + "/print";
        window.open(url, '_blank');
        $.ajax({
           type: "GET",
           
        });
    })
</script>
@endsection