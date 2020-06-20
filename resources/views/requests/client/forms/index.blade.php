@extends('backpack::layout')

@section('header')
  <section class="content-header">
    @if($type=='pending')
      <h1>Pending Requests</h1>
      <ol class="breadcrumb">
        <li>Requests</li>
        <li>Pending</li>
        <li class="active">Home</li>
      </ol>
    @elseif($type== 'approved')
      <h1>Approved Requests</h1>
      <ol class="breadcrumb">
        <li>Requests</li>
        <li>Approved</li>
        <li class="active">Home</li>
      </ol>
      @elseif($type == 'released')
      <h1>Released Requests</h1>
      <ol class="breadcrumb">
        <li>Requests</li>
        <li>Released</li>
        <li class="active">Home</li>
      </ol>
      @elseif($type == 'disapproved')
      <h1>Disapproved Requests</h1>
      <ol class="breadcrumb">
        <li>Requests</li>
        <li>Disapproved</li>
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
            @if($type == 'pending')
            <th class="col-sm-1 no-sort ">Request Date</th>
            <th class="col-sm-1 no-sort ">Request Processed By</th>
              <th class="col-sm-1">Purpose</th>
            @elseif($type == 'approved')
              <th class="col-sm-1 no-sort ">Request Processed By</th>
              <th class="col-sm-1">Date Approved</th>
              <th class="col-sm-1">Approved by</th>
              <th class="col-sm-1">Remarks</th>
            @elseif($type == 'released')
              <th class="col-sm-1 no-sort ">Request Processed By</th>
              <th class="col-sm-1">Date Released</th>
              <th class="col-sm-1">Released By</th>
              <th class="col-sm-1">Remarks</th>
            @elseif($type == 'disapproved')
              <th class="col-sm-1 no-sort ">Request Processed By</th>
              <th class="col-sm-1">Date Updated</th>
              <th class="col-sm-1">Updated By</th>
              <th class="col-sm-1">Status</th>
              <th class="col-sm-1">Remarks</th>
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
        ajax: "{{ url('request/client/' . $type) }}",
        columns: [
          { data: "local_id" },
          @if($type == 'pending')
            { data: "purpose" },
          { data: 'date_requested' },
          { data: 'request_person' },
          @elseif($type == 'approved')
          { data: 'request_person' },
            { data: "date_approved" },
            { data: "issue_person" },
            { data: "remarks" },
          @elseif($type == 'released')
          { data: 'request_person' },
            { data: "date_released" },
            { data: "release_person" },
            { data: "remarks" },
          @elseif($type == 'disapproved')
          { data: 'request_person' },
            { data: "date_cancelled" },
            { data: "cancel_person" },
            { data: "status" },
            { data: "remarks" },
          @endif
           
          { data: function(callback){
            return `<a href="{{ url('request/client/') }}/`+ callback.id +`" class="btn btn-default btn-sm"><i class="fa fa-list-ul" aria-hidden="true"></i> View</a>`;
          }}
        ],
      });
    });

</script> 
@endsection