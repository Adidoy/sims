@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
	    View Inventory Adjustments
	  </h1>
	  <ol class="breadcrumb">
	    <li>Adjustment</li>
	    <li class="active">Home</li>
	  </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box" style="padding:10px">
    <div class="box-body">
      <table class="table table-hover table-striped" id="adjustmentTable" width=100%>
        <thead>
          <tr>
            <th class="col-sm-1">Date</th>
            <th class="col-sm-1">Adjustment Reference</th>
            <th class="col-sm-1">References</th>
            <th class="col-sm-1">Reasons Leading<br />to Adjustment</th>
            <th class="col-sm-1">Other Details</th>
            <th class="col-sm-1">Action</th>
            <th class="col-sm-1">Processed by</th>
            <th class="col-sm-1 no-sort"></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>

    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection

@section('after_scripts')
<script>
  jQuery(document).ready(function($) {

    var table = $('#adjustmentTable').DataTable({
        language: {
                searchPlaceholder: "Search..."
        },
        columnDefs:[
            { targets: 'no-sort', orderable: false },
        ],
        "dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        "processing": true,
        ajax: "{{ url('inventory/adjustments') }}",
        columns: [
                { data: 'date_processed' },
                { data: 'local' },
                { data: 'reference' },
                { data: 'reasonLeadingTo' },
                { data: 'details_append' },
                { data: 'action' },
                { data: "processed_person" },
                { data: function(callback){
                  ret_val =  `
                    <a href="{{ url('inventory/adjustments') }}/`+ callback.id +`" class="btn btn-default btn-sm"><i class="fa fa-list-ul" aria-hidden="true"></i> View</a>
                  `
                    return ret_val;
                } }
        ],
    });
  });
</script>
@endsection
