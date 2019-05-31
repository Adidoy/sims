<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Print :: RIS No.: {{ $request->local }}</title>


    <!-- Bootstrap 3.3.5 -->
    <style>
      thead { display: table-header-group }
      tfoot { display: table-footer-group }
      tr { page-break-inside: avoid }
      th , tbody {
        text-align: justify-all;
        font-size: 12px;
        border-style: solid;
      }

      #rsmiTable {
        border-collapse: collapse;
        
      }

      #rsmiTable td, tbody, tfoot{
        border: solid #dde;
        font-family: "Arial";
        font-size: 12px;
      }

      #rsmiTable th {
        border: solid #fff;
      }

      #content{
        font-family: "Arial";
        font-size: 12px;
      }

      @media print {
          .page-break  { display: block; page-break-after:always; }
      }   
    </style>
  </head>
  <body>
    
  <div id="content" class="col-sm-12">
    <table cellspacing="0" width="100%" style="font-size: 12px">
      <tr>
        <td rowspan = 4><img src="{{ asset('images/logo.png') }}" style="height: 80px;width:auto;" /></td>
        <td style="font-size:11pt; text-align: justify; color: #800000;">Republic of the Philippines</td>
        <td><small>Appendix 64</small></td>
      </tr>
      <tr>
        <td style="font-size:13pt; text-align: justify; color: #800000;"><strong>POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</strong></td>
      </tr>
      <tr>
        <td style="font-size:11pt; text-align: justify; color: #800000;">Sta. Mesa, Manila</td>
      </tr>        
      <tr>
        <td style="font-size:11pt; text-align: justify; color: #800000;">Date Printed: {{ Carbon\Carbon::now()->format("d F Y h:m A") }}</td>
      </tr> 
      <tr>
        <td style="font-size:11pt; text-align: justify; color: #800000;"><br/><br/></td>
      </tr>               
      <tr>
        <td style="font-size:14pt; text-align: center;" colspan = 15><strong>REQUISITION AND ISSUE SLIP</strong></td>
        <td class="pull-right"></td>
      </tr>      
      <tr>
        <td style="font-size:11pt; text-align: justify; color: #800000;"><br/></td>
      </tr>        
    </table>
    <table class="table table-striped table-bordered table-condensed" id="inventoryTable" width="100%" cellspacing="0">
      <thead>
        <tr><th colspan="16" style="color: #800000;">
            <div style="margin-left: 5em;">
              <div style="font-size:11pt; text-align: justify;">Republic of the Philippines  </div>
              <div style="font-size:13pt; text-align: justify;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES </div>
              <div style="font-size:11pt; text-align: justify;">Sta. Mesa, Manila</div>
              <div style="font-size:10pt; text-align: justify;"><span class="pull-right">Date Printed: {{ Carbon\Carbon::now()->format("d F Y h:m A") }} </span></div>
            </div>
        </th></tr>
          <tr >
              <th colspan="16"><h2 class="text-center">REQUISITION AND ISSUE SLIP  <small class="pull-right">Appendix 63</small></h2></th>
          </tr>
          <tr rowspan="2">
              <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="16">Fund Cluster:  <span style="font-weight:normal"></span> </th>
          </tr>
          <tr rowspan="2">

              <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="6">Division:  
                @if($request->office->head_office == null)
                <span style="font-weight:normal; font-size:10pt; text-align: justify;">N/A</span> 
                @else
                <span style="font-weight:normal; font-size:10pt; text-align: justify;">{{ isset($request->office->headoffice) ? $request->office->headoffice->name : $request->office->name }}</span> 
                @endif
              </th>
              <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="10">Responsibility Center Code:  <span style="font-weight:normal">{{ isset($request->office->code) ? $request->office->code : $request->office }}</span> </th>
          </tr>
          <tr rowspan="2">
              <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="6">Office: 
                @if($request->office->head_office == null)
                <span style="font-weight:normal">{{ isset($request->office->name) ? $request->office->name : $request->office }}</span> 
                @else
                <span style="font-weight:normal">{{ isset($request->office) ? $request->office->name : $request->office }}</span> 
                @endif
              </th>
              <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="10">RIS No.:  <span style="font-weight:normal">{{ $request->local }}</span> </th>
          </tr>
        </thead>
      </table>
      <table id="rsmiTable" cellspacing="0" width="100%" style="font-size: 12px">
          <tr>
              <td class="text-center" style="white-space: nowrap; text-align:center; font-weight:bold;" colspan="8"> <i> Requisition </i> </td>
              <td width="120px" style="white-space: nowrap; text-align:center; font-weight:bold;" class="text-center" colspan="4"> <i> Stock Available? </i> </td>
              <td class="text-center" style="white-space: nowrap; text-align:center; font-weight:bold;" colspan="4"> <i> Issue </i> </td>
          </tr>
          <tr>
            <td colspan="2" style="white-space: nowrap; text-align:center; font-weight:bold;" width="75px">Stock No.</td>
            <td colspan="2" style="white-space: nowrap; text-align:center; font-weight:bold;" class="col">Unit</td>
            <td colspan="2" style="white-space: nowrap; text-align:center; font-weight:bold;">Details</td>
            <td colspan="2" style="white-space: nowrap; text-align:center; font-weight:bold;">Quantity</td>
            <td colspan="2" style="white-space: nowrap; text-align:center; font-weight:bold;" width="55px">Yes</td>
            <td colspan="2" style="white-space: nowrap; text-align:center; font-weight:bold;" width="55px">No</td>
            <td colspan="2" style="white-space: nowrap; text-align:center; font-weight:bold;">Quantity </td>
            <td colspan="2" style="white-space: nowrap; text-align:center; font-weight:bold;">Remarks</td>
          </tr>
      <tbody>
        @foreach($request->supplies as $key=>$supply)
        <div class="page-break"></div>
        <tr style="font-size: 12px;" class="{{ ((($key+1) % 18) == 0) ? "page-break;" : "" }}">
          <td colspan="2" style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;">{{ $supply->stocknumber }}</td>
          <td colspan="2" style="white-space: normal;text-align: justify; padding-left: 5px; padding-right: 5px;"><span style="font-size: 11px; font-family:'verdana' ">{{ $supply->unit->abbreviation }}</span></td>
          <td colspan="2" style="white-space: nowrap; text-align: justify; padding-left: 5px; padding-right: 5px;" class="text-left">
            <span style="font-family:'verdana'; font-size: 
            @if(strlen($supply->details) > 50) 9px 
              @elseif(strlen($supply->details) > 40) 11px 
              @elseif(strlen($supply->details) > 20) 12px 
            @else 11px @endif">
              {{ $supply->details }}
            </span>
          </td>
          <td colspan="2" style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;" class="text-center">{{ $supply->pivot->quantity_requested }}</td>
          @if($supply->pivot->quantity_issued > 0 && ($request->status == 'approved' || $request->status == 'Approved' || ucfirst($request->status) == 'Released'))
          <td colspan="2" style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;" class="text-center"> ✔ </td>
          <td colspan="2" style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;" class="text-center">  </td>
          @elseif($supply->pivot->quantity_issued <= 0 && ($request->status == 'approved' || $request->status == 'Approved' || ucfirst($request->status) == 'Released'))
          <td colspan="2" style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;" class="text-center">  </td>
          <td colspan="2" style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;" class="text-center"> ✔ </td>
          @else
          <td colspan="2" style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;" class="text-center"></td>
          <td colspan="2" style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;" class="text-center">  </td>
          @endif
          <td colspan="2" style="white-space: nowrap; text-align: right; padding-left: 5px; padding-right: 5px;">{{ $supply->pivot->quantity_issued }}</td>
          <td colspan="2" style="white-space: nowrap; text-align: right; padding-left: 5px; padding-right: 5px;">{{ $supply->pivot->comments }}</td>
        </tr>
        @endforeach
        <tr>
          <td colspan=16 class="col-sm-12"><p style="font-weight:bold; text-align: center;">  ******************* Nothing Follows ******************* </p></td>
        </tr>

      
      <!-- Purpose -->
      <tfoot>
        <tr>
              <td class="text-left" colspan="16"><span style="font-size: 12px; "><b>Purpose:</b></span></td>
          </tr>
        <tr>
          <td  colspan="16">
            <p class="text-left" word-wrap="break-word;">{{ $request->purpose }}<p>
            
          </td>
        </tr>
      </tbody>
    </table>
    <hr color=#00000 />
    <table width="100%" style="font-size: 12px">
        <!-- Signatories Header-->
        <tr>
          <td width="105px">   </td>
          <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;">  Requested By: </td>
          <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;">  Approved By: </td>
          <td style="white-space: nowrap; text-align:center; font-weight:bold;"  class="col-xs-2">  Issued By: </td>
          <td style="white-space: nowrap; text-align:center; font-weight:bold;"  class="col-xs-2">  Received By: </td>
        </tr>
        <!-- Signatories Signature-->
        <tr>
          <td width="105px" class="text-left" style="white-space: nowrap; text-align:left; font-weight:bold;"  >Signature:</td>
          <td width="250px" class="text-center">   <br/><br/>       </td>
          <td width="250px" class="text-center">   <br/><br/>       </td>
          <td class="text-center">                 <br/><br/>       </td>
          <td class="text-center">                 <br/><br/>       </td>
        </tr>
        <!-- Signatories Name-->
        <tr>
          <td width="105px" style="white-space: nowrap; text-align:left; font-weight:bold;" class="text-left">  Printed Name:</td>
          <td width="250px" style="text-align:center; font-weight:bold; font-size: 12px;" width=50px>
            <span> 
              @if( isset($signatory->requestor_name) )
                {{ strtoupper($signatory->requestor_name) }}
              @elseif( $officeSignatory <> 'NONE' )
                {{ strtoupper($officeSignatory->head) }}
              @endif
            </span>
            
          </td>
          <td width="250px" style="text-align:center; font-weight:bold; font-size: 12px;" width=50px>
            <span>
              @if( isset($signatory->approver_name) )
                {{ strtoupper($signatory->approver_name) }}
              @elseif( $headOffice <> 'NONE' )
                {{ strtoupper($headOffice->head) }}
              @endif
            </span>  
          </td>
          <td class="text-center">  </td>
          <td class="text-center">  </td>
        </tr>
        <!-- Signatories Designation-->
        <tr>
          <td width="105px" style="white-space: nowrap; text-align:left; font-weight:bold;" class="text-left">Designation:</td>
          <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;font-size:10px; word-wrap: break-word;">
            <span> 
              @if( isset($signatory->requestor_designation) )
                {{ $signatory->requestor_designation }}
              @elseif( $officeSignatory <> 'NONE' )
                {{ $officeSignatory->head_title }}
              @endif
            </span>
          </td>
          <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;font-size:10px; word-wrap: break-word;">
            <span>
              @if( isset($signatory->approver_designation) )
                {{ $signatory->approver_designation }}
              @elseif( $headOffice <> 'NONE' )
                {{ $headOffice->head_title }}
              @endif
            </span>
          </td>
          <td class="text-center">          </td>
          <td class="text-center">          </td>
        </tr>
        <!-- Signatories Date-->
        <tr>
          <td width="105px" style="white-space: nowrap; text-align:left; font-weight:bold;" class="text-left">Date:</td>
          <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;font-size:10px; word-wrap: break-word;">
            <span> 
              {{ $request->date_requested }} 
            </span>
          </td>
          <td width="250px" class="text-center"> </td>
          <td class="text-center"> </td>
          <td class="text-center"> </td>
        </tr>
          <td colspan="16" style="border: none; font-size: 12px;"><br/><br/> <b>
            *This request is valid for 3 working days (5 days for Branches and Campuses) upon approval after which, if items are not picked up, the request is automatically <span class="text-danger"> cancelled</span>.
            @if(isset($sector->code) == 'OVPBSC')
             <br>*Request will expire on <span class="text-danger">{{ $request->created_at->addWeekdays(5)}} </span>
            @else
             <br>*Request will expire on <span class="text-danger">{{ $request->created_at->addWeekdays(3)}} </span>
            @endif
          <br>*Supplies and Materials will be released <span class="text-danger">only </span> to authorized personnel of the requesting office.  
          <br>*Please pay attention to the <span class="text-danger">UNIT</span> of the item. The unit of measurement to be followed is in the unit column above  </b>
        </td>
        </tr>
      </tfoot>
    </table>
    @if($request->status == 'Released')
        <table>
          <tr>
            <td colspan=16 class="col-sm-12">
              <p style="color:red; font-weight:bold; text-align: justified; font-size:12pt;">
                NOTE: Supplies and materials for this Requisition and Issuance Slip are tagged as RELEASED. <br/>
                Processed By: {{ App\User::find($request->released_by)->fullname }}  <br />
                Date Processed: {{ $request->date_released }}
              </p>
            </td>
          </tr>
        </table>
    @endif
  </div>  
</body>
</html>
