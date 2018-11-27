@extends('backpack::layout')

@section('header')
  <section class="content-header">
    @if($type =='pending')
      <h1>Pending Requests</h1>
      <ol class="breadcrumb">
        <li>Requests</li>
        <li>For Approval</li>
        <li class="active">Home</li>
      </ol>
    @elseif($type == 'approved')
      <h1>Approved Requests</h1>
      <ol class="breadcrumb">
        <li>Requests</li>
        <li>For Issuance</li>
        <li class="active">Home</li>
      </ol>
      @elseif($type == 'released')
      <h1>Released Requests</h1>
      <ol class="breadcrumb">
        <li>Requests</li>
        <li>For Release</li>
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
        <thead width="100%">
          <tr>
            <th class="col-md-1 no-sort">Request No.</th>
            @if($type == 'pending')
              <th class="col-md-1 no-sort ">Request Date</th>
              <th class="col-md-1 no-sort ">Requestor</th>
              <th class="col-md-1 no-sort ">Office</th>
              <th class="col-md-1">Purpose</th>
            @elseif($type == 'approved')
              <th class="col-md-1">Date Approved</th>
              <th class="col-md-1 no-sort ">Requestor</th>
              <th class="col-md-1 no-sort ">Office</th>
              <th class="col-md-1 no-sort ">Approved and Issued By</th>
              <th class="col-md-1">Remarks</th>
            @elseif($type == 'released')
              <th class="col-md-1">Date Released</th>
              <th class="col-md-1 no-sort ">Requestor</th>
              <th class="col-md-1 no-sort ">Office</th>
              <th class="col-md-1 no-sort ">Released By</th>
              <th class="col-md-1 no-sort ">Remarks</th>
              @elseif($type == 'disapproved')
              <th class="col-md-1">Date Updated</th>
              <th class="col-md-1 no-sort ">Requestor</th>
              <th class="col-md-1 no-sort ">Office</th>
              <th class="col-md-1">Status</th>
              <th class="col-md-1">Remarks</th>
            @endif

            <th class="col-md-1 no-sort"></th>
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
        "autoWidth": true,
        language: {
              searchPlaceholder: "Search..."
        },
        columnDefs:[{ targets: 'no-sort', orderable: false }],
        "dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
                      "<'row'<'col-sm-12'tr>>" +
                      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        ajax: "{{ url('request/custodian/?type=' . $type) }}",
        columns: [
          { data: "local_id" },
          @if($type == 'pending')
            { data: 'date_requested' },
            { data: 'request_person'},
            { data: "office_name" },
            { data: "purpose" },
          @elseif($type == 'approved')
            { data: "date_approved" },
            { data: 'request_person'},
            { data: "office_name" },
            { data: "issue_person" },
            { data: "request_remarks" },
          @elseif($type == 'released')
            { data: "date_released" },
            { data: 'request_person'},
            { data: "office_name" },
            { data: 'release_person'},
            { data: "request_remarks" },
          @elseif($type == 'disapproved')
            { data: "date_updated" },
            { data: 'request_person'},
            { data: "office_name" },
            { data: "status" },
            { data: "request_remarks" },
          @endif

          { data: function(callback){
            return `<a href="{{ url('request/custodian/') }}/`+ callback.id +`" class="btn btn-default btn-sm"><i class="fa fa-list-ul" aria-hidden="true"></i> View</a>`;
          }}
        ],
      });
    });

</script> 
@endsection