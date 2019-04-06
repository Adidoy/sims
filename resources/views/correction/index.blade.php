@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
	    Inventory Correction
	  </h1>
	  <ol class="breadcrumb">
	    <li>Correction</li>
	    <li class="active">Home</li>
	  </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box" style="padding:10px">
    <div class="box-body">
      <table class="table table-hover table-striped" id="correctionTable" width=100%>
        <thead>
          <tr>
            <th class="col-sm-1">Control No.</th>
            <th class="col-sm-1">Requested By</th>
            <th class="col-sm-1">Date Filled</th>
            <th class="col-sm-1">Processed By</th>
            <th class="col-sm-1">Date and Time Processed</th>
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

    var table = $('#correctionTable').DataTable({
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
        ajax: "{{ url('correction') }}",
        columns: [
                { data: "control_number" },
                { data: 'requestor_name' },
                { data: 'filled_at' },
                { data: "processed_" },
                { data: function(callback) {

                  ret_val =  `
                    <a href="{{ url('correction') }}/`+ callback.id +`" class="btn btn-default btn-sm"><i class="fa fa-list-ul" aria-hidden="true"></i> View</a>
                  `

                    return ret_val;
                } }
        ],
    });

		$("div.toolbar").html(`
				<a href="{{ url('correction/create') }}" class="btn btn-sm btn-primary">
					<span class="glyphicon glyphicon-tag ladda-button" aria-hidden="true"></span>
					<span id="nav-text">Create Correction</span>
				</a>
		`);

    $('#correctionTable').on('click','button.remove',function(){
      var removeButton = $(this);
      removeButton.button('loading');
      $.ajax({
        type: 'delete',
        url: '{{ url("adjustment") }}' + '/' + $(this).data('id'),
        dataType: 'json',
        success: function(response){
          if(response == 'success')
          swal("Operation Success",'Disposal Report has been removed.',"success")
          else
            swal("Error Occurred",'An error has occurred while processing your data.',"error")
            table.ajax.reload()
            removeButton.button('reset');
        },
        error: function(response){
          swal("Error Occurred",'An error has occurred while processing your data.',"error")
        }

      })
    })
  });
</script>
@endsection
