@extends('backpack::layout')

@section('header')
  <section class="content-header">
    @if(Request::url() == url('request/client/pending'))
      <h1>Pending Requests</h1>
      <ol class="breadcrumb">
        <li>Requests</li>
        <li>Pending</li>
        <li class="active">Home</li>
      </ol>
    @elseif(Request::url() == url('request/client/approved'))
      <h1>Approved Requests</h1>
      <ol class="breadcrumb">
        <li>Requests</li>
        <li>Approved</li>
        <li class="active">Home</li>
      </ol>
      @elseif(Request::url() == url('request/client/released'))
      <h1>Released Requests</h1>
      <ol class="breadcrumb">
        <li>Requests</li>
        <li>Released</li>
        <li class="active">Home</li>
      </ol>               
    @endif
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
            <th class="col-sm-1">Purpose</th>
            @if(Request::url() == url('request/client/approved'))
              <th class="col-sm-1">Remarks</th>
              <th class="col-sm-1">Date Approved</th>
            @elseif(Request::url() == url('request/client/released'))
              <th class="col-sm-1">Date Released</th>
            @endif

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
        ajax: getURL(),
        columns: [
          { data: "local" },
          { data: 'date_requested' },
          { data: "purpose" },
          @if(Request::url() == url('request/client/approved'))
          { data: "remarks" },
          { data: "approved_at" },
          @elseif(Request::url() == url('request/client/released'))
          { data: "released_at" },
          @endif
           
          { data: function(callback){
            return `<a href="{{ url('request/client/') }}/`+ callback.id +`" class="btn btn-default btn-sm"><i class="fa fa-list-ul" aria-hidden="true"></i> View</a>`;
          }}
        ],
      });

      function getURL()
      {
        if("{{Request::url()}}" == "{{url('request/client/pending')}}") {
          return "{{url('request/client/pending')}}";
        } else if("{{Request::url()}}" == "{{url('request/client/approved')}}") {
          return "{{url('request/client/approved')}}";
        } else if("{{Request::url()}}" == "{{url('request/client/released')}}") {
          return "{{url('request/client/released')}}";
        }        
      }
    });

</script> 
@endsection
