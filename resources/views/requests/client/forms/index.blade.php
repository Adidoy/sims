@extends('backpack::layout')

@section('header')
  <section class="content-header">
    <h1>Request</h1>
	  <ol class="breadcrumb">
	    <li>Request</li>
	    <li class="active">Home</li>
	  </ol>
  </section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box" style="padding:10px">
    <div class="box-body">
      <table class="table table-hover table-striped" id="requestTable" width=100%>
        <thead>
          <tr>
            <th class="col-sm-1 no-sort">Request No.</th>
            <th class="col-sm-1 no-sort ">Request Date</th>
            <th class="col-sm-1">Remarks</th>
            <th class="col-sm-1">Purpose</th>
            <th class="col-sm-1">Status</th>
            <th class="col-sm-1 no-sort"></th>
          </tr>
        </thead>
      </table>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
@endsection

@section('after_scripts')
  <script>
    jQuery(document).ready(function($) 
    {
      table = $('#requestTable').DataTable(
      {
        pageLength: 25,
        serverSide: true,
        stateSave: true,
        "processing": true,
        language: {
              searchPlaceholder: "Search..."
        },
        columnDefs:[{ 
              targets: 'no-sort', orderable: false 
        }],
        "order": [
              [0, 'asc']
        ],
        "dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
                      "<'row'<'col-sm-12'tr>>" +
                      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        ajax: "{{ url('request') }}",
        columns: [
          { data: "code" },
          { data: 'date_requested' },
          { data: "remarks" },
          { data: "purpose" },
          { data: "status" }, 
          { data: function(callback){
            return `<a href="{{ url('request') }}/`+ callback.id +`" class="btn btn-default btn-sm"><i class="fa fa-list-ul" aria-hidden="true"></i> View</a>`;
          }}
        ],
      });
      $('div.toolbar').html(`<a id="create" href="{{ url('request/create') }}" class="btn btn-primary ladda-button" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-plus"></i> Create a Request</span></a>`);
    });

</script> 
@endsection
