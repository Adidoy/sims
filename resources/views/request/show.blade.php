@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<legend>
            <h3 class="text-muted">{{ $request->code }}</h3>
        </legend>
		<ul class="breadcrumb">
			<li><a href="{{ url('request') }}">Request</a></li>
			<li class="active"> {{ $request->code }} </li>
		</ul>
	</section>
@endsection

@section('content')
<!-- Default box -->
    <div class="box">
        <div class="box-body">
		    <div class="panel panel-body table-responsive">
			    @if(isset($request->requestor_id) && Auth::user()->id == $request->requestor_id )
                    @if($request->status == 'Resubmit' || $request->status == null || ( strpos($request->status, 'pdated') != false )  || $request->status == '' || ( strpos($request->status, 'ending') != false ))
                    <!--
                        <a href="{{ url("request/$request->id/edit") }}" class="btn btn-default btn-sm">
	    		            <i class="fa fa-pencil" aria-hidden="true"></i> Edit
	    	            </a> 
                    -->
                        <a href="{{ url("request/$request->id/cancel") }}" class="btn btn-danger btn-sm">
        	                <i class="fa fa-hand-stop-o" aria-hidden="true"></i> Cancel
                        </a>
                    @endif
                @endif
			    <table class="table table-hover table-striped table-bordered table-condensed" id="requestTable" cellspacing="0" width="100%"	>
				    <thead>
                        <tr rowspan="2">
                            <th class="text-left" colspan="3">Request Slip:  <span style="font-weight:normal">{{ $request->code }}</span> </th>
                            <th class="text-left" colspan="3">Requestor:  <span style="font-weight:normal">{{ isset($request->office) ? $request->office->code : 'None' }}</span> </th>
                        </tr>
                        <tr rowspan="2">
                            <th class="text-left" colspan="3">Remarks:  <span style="font-weight:normal">{{ $request->remarks }}</span> </th>
                            <th class="text-left" colspan="3">Status:  <span style="font-weight:normal">{{ ($request->status == '') ? ucfirst(config('app.default_status')) : $request->status }}</span> </th>
                        </tr>
                        <tr rowspan="2">
                            <th class="text-left" colspan="6">Purpose:  <span style="font-weight:normal">{{ $request->purpose }}</span> </th>
                        </tr>
                        <tr>          
						    <th>Stock Number</th>
						    <th>Details</th>
						    <th>Quantity Requested</th>
						    <th>Quantity Issued</th>
						    <th>Quantity Released</th>
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
    var table = $('#requestTable').DataTable({
		language: {
			searchPlaceholder: "Search..."
		},
		"dom":  "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
			    "<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-5'i><'col-sm-7'p>>",
		"processing": true,
		ajax: "{{ url("request/$request->id") }}",
		columns: [
				{ data: "stocknumber" },
				{ data: "details" },
				{ data: "pivot.quantity_requested" },
				{ data: "pivot.quantity_issued" },
				{ data: "pivot.quantity_released" },
				{ data: "pivot.comments" }
		],
    });

    $('div.toolbar').html(`
        @if(($request->status == 'Approved' || $request->status == 'Released') && (Auth::user()->access == 1 || Auth::user()->access == 6 || Auth::user()->access == 3))
        <a href="{{ url("request/$request->id/print") }}" target="_blank" id="print" class="print btn btn-sm btn-default ladda-button" data-style="zoom-in">
          <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
          <span id="nav-text"> Download</span>
        </a>
        @endif
 

        @if(Auth::user()->access == 1 || Auth::user()->access == 6)
        
          @if($request->status == 'Approved')
          <a id="release" href="{{ url("request/$request->id/release") }}" class="btn btn-sm btn-danger ladda-button" data-style="zoom-in">
            <span class="ladda-label"><i class="glyphicon glyphicon-share-alt"></i> Release</span>
          </a>

          @endif

          @if($request->status == null || $request->status == 'Pending' || ( strpos($request->status, 'pdated') != false ))
          <a type="button" href="{{ url("request/$request->id/accept") }}" data-id="{{ $request->id }}" class="accept btn btn-success btn-sm">
              <i class="fa fa-thumbs-up" aria-hidden="true"> Accept</i>
              <a href="{{ url("request/$request->id/cancel") }}" class="btn btn-danger btn-sm">
        	<i class="fa fa-hand-stop-o" aria-hidden="true"></i> Cancel
        </a>
          </a>
          @endif

          @if($request->status != null && ($request->status == 'Approved')) 
          <button id="expire" type="button" data-id="{{ $request->id }}" class="btn btn-warning btn-sm"> 
            <i class="fa fa-refresh" aria-hidden="true"> Expire</i> 
          </button> 
 
          @endif

        @endif
        
    `)
    /*<a id="comment" href="{{ url("request/$request->id/comments") }}" class="btn btn-sm btn-primary ladda-button" data-style="zoom-in">
          <span class="ladda-label"><i class="fa fa-comment" aria-hidden="true"></i> Messages  <span class="label label-danger"> {{ App\RequestComments::where('request_id', '=', $request->id)->count() }} </span> </span>
        </a>*/
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
            success: function(response){
                if(response == 'success'){
                    swal('Operation Successful','Operation Complete please reload the page!','success'),
                    location.reload();
                }else{
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

	} );
</script>
@endsection
