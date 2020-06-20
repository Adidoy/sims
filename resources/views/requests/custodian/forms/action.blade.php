<div class="col-sm-12" style="padding: 10px;">
  <legend><h3 class="text-center text-muted">REQUEST DETAILS</h3></legend>
  <table class="table table-hover table-condensed table-striped table-bordered" id="supplyTable" style="padding:20px;margin-right: 10px;">
    <thead>
      <tr class="text-center">
        <th class="col-sm-1 text-center">Stock Number</th>
        <th class="col-sm-1 text-center">Information</th>
        <th class="col-sm-1 text-center">Unit</th>
        <th class="col-sm-1 text-center">Remaining Balance</th>
        <th class="col-sm-1 text-center">Requested Quantity</th>
        <th class="col-sm-1 text-center">Issued Quantity</th>
        <th class="col-sm-1 text-center">Comments</th>
      </tr>
    </thead>
    @if(isset($request->supplies))
      @foreach($request->supplies as $supply)
        <tr @if($supply->temp_balance <= 0) class="danger" @endif>
          <td class="text-center">{{ $supply->stocknumber }}<input type="hidden" name="stocknumber[]" value="{{ $supply->stocknumber }}" /></td>
          <td class="text-left">{{ $supply->details }}</td>
          <td class="text-center">{{ $supply->unit->name }}</td>
          <td class="supply-balance text-center">{{ $supply->temp_balance }}</td>
          <td class="text-center">
            {{ $supply->pivot->quantity_requested }}
            <input type="hidden" name="requested[{{ $supply->stocknumber }}]" class="form-control" value="{{ $supply->pivot->quantity_requested }}"/>
          </td>
          <td>
            <input type="number" name="quantity[{{ $supply->stocknumber }}]" class="form-control" value="{{ ($supply->pivot->quantity_requested <= $supply->temp_balance) ? $supply->pivot->quantity_requested : 0 }}" />
          </td>
          <td>
            <input type="text" name="comment[{{ $supply->stocknumber }}]" class="comment form-control" value="" />
          </td>
        </tr>
      @endforeach
    @endif
    <tr>
      <td colspan=7 class="text-muted text-center">  <legend><h3 class="text-center text-muted">******************* Nothing Follows *******************</h3></legend></td>
    </tr>
  </table>
  <div class="col-sm-12">
    <label>Purpose</label>
    <blockquote> 
      <p style="font-size: 20px;">{{ $request->purpose }}</p> 
    </blockquote>
  </div>
  <div class="form-group" style="padding: 10px;">
    <div class="col-md-12">
      <label>Additional Remarks</label>
      <textarea class="form-control" rows="8"  id="remarks" name="remarks" placeholder="Input additional comments/remarks">{{  old('remarks') }}</textarea>
    </div>
  </div>
  <div class="pull-right">
      <input type="hidden" name="action" id="action" />
      <button type="submit" name="disapprove" id="disapprove" style="text-align:justify; font-size:11pt;" class="btn btn-md btn-danger" value="disapproved">
        <i class="fa fa-thumbs-down" aria-hidden="true"> Disapprove Request </i>
      </button>
      <button type="submit" name="approve" id="approve" style="text-align:justify; font-size:11pt;" class="btn btn-md btn-success" value="approved">
        <i class="fa fa-thumbs-up" aria-hidden="true"> Approve Request</i>
      </button>
      <a type="button" id="cancel" style="text-align:justify; font-size:11pt;" class="btn btn-md btn-default" href="{{ url("request/custodian/pending") }}">
        Go Back
      </a>
  </div>
  <div class="clearfix">
  </div>
</div>

@section('after_scripts')

<script>
  jQuery(document).ready(function($) {
    $(document).ready(function(){
      trigger = 0;
      $('.supply-balance').each(function(){
        text = $(this).text()
        if( text != 0 )
        {
          trigger = 1;
        }
      })

      if( trigger == 0 )
      {
        swal({
          title: "Warning!",
          text: "You have no items to release for this request. Do you want to disapprove it?",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, disapprove it!",
          cancelButtonText: "No, cancel it!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function(isConfirm){
          if (isConfirm) {
            $('.comment').val('')
            $('#remarks').val('No items to allocate')
            $('#disapprove').trigger('click')
          } else {
            swal("Cancelled", "Operation Cancelled", "error");
          }
        })
      }
    })

    $('#approve, #disapprove, #resubmission').on('click',function(event)
    {
      action = $(this).val();
      event.preventDefault()

      if($('#supplyTable > tbody > tr').length == 0)
      {
        swal('Blank Field Notice!','Supply table must have atleast 1 item','error')
      } else {
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
                $('#action').val(action)
                $('#requestForm').submit();
              } else {
                swal("Cancelled", "Operation Cancelled", "error");
              }
            })
      }
    })

    $('#supplyInventoryTable').on('click','.add-stock',function(){

      insertRow($(this).data('id'))
      $('#addStockNumberModal').modal('hide')

    })

    function insertRow(stocknumber, quantity=0, issued=0)
    {

      error = false

      $('.stocknumber-list').each(function() {
          if (stocknumber == $(this).val())
          {
            error = true; 
            return;
          }
      });

      if(error)
      {
        swal("Error", "Stocknumber already exists", "error");
        return false;
      }

      $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'get',
        url: '{{ url('inventory/supply') }}' +  '/' + stocknumber,
        dataType: 'json',
        success: function(response){
            $('#supplyTable > tbody ').prepend(`
                <tr>
                  <td>`+response.data.stocknumber+`<input type="hidden" name="stocknumber[]" value="`+response.data.stocknumber+`" /></td>
                  <td>`+response.data.details+`</td>
                  <td class="supply-balance">`+response.data.temp_balance+`</td>
                  <td>`+quantity+`<input type="hidden" name="requested[`+response.data.stocknumber+`]" class="form-control" value="`+quantity+`"  /></td>
                  <td><input type="number" name="quantity[`+response.data.stocknumber+`]" class="form-control" value="`+issued+`"  /></td>
                  <td><input type="text" name="comment[`+response.data.stocknumber+`]" class="comment form-control" /></td>
                </tr>
            `)

        }
      })
    }

  });
</script>
@endsection