<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Delivery Report :: {{ $delivery->local }}</title>


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

            #deliveryTable {
                border-collapse: collapse;
            }

            #deliveryTable td, tbody, tfoot{
                border: solid #dde;
                font-family: "Arial";
                font-size: 12px;
            }

            #deliveryTable th {
                border: solid #dde;
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
            <table class="table table-striped table-bordered table-condensed" width="100%" cellspacing="0">     
                <thead>                                     
                    <tr><th colspan="1" style="color: #800000;">
                        <div style="margin-left: 5em;">
                            <div style="font-size:11pt; text-align: justify;">Republic of the Philippines  </div>
                            <div style="font-size:13pt; text-align: justify;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES </div>
                            <div style="font-size:11pt; text-align: justify;">Sta. Mesa, Manila</div>
                            <div style="font-size:10pt; text-align: justify;"><span class="pull-right">Date Printed: {{ Carbon\Carbon::now()->toDayDateTimeString() }} </span></div>
                        </div>
                    </th></tr>
                    <tr>
                        <th colspan="1">
                            <div style="white-space: nowrap; text-align: center; padding: 0px;">
                                <div><h2>DELIVERY ACCEPTANCE REPORT</h2></div>
                                <div><h3>Delivery Acceptance No.: {{ $delivery->local }}</h3></div>
                            </div>
                        </th>
                    </tr>
                </thead>
            </table>
            <br/><br/>
            <table class="table table-striped table-bordered table-condensed" width="100%" cellspacing="0">     
                <thead>                                     
                    <tr style="font-size:13pt; text-align: justify;">
                        <th>Processed By:  <span style="font-weight:normal">{{ isset($delivery->user_name) ? $delivery->user_name : 'None' }}</span> </th>						
                        <th>Date Processed:  <span style="font-weight:normal">{{ isset($delivery->date_processed) ? $delivery->date_processed : 'None' }}</span> </th>
                    </tr>
                    <tr style="font-size:13pt; text-align: justify;">
                        <th>Purchase Order No.:  <span style="font-weight:normal">{{ isset($delivery->purchaseorder_no) ? $delivery->purchaseorder_no : 'None' }}</span> </th>
                        <th>Purchase Order Date:  <span style="font-weight:normal">{{ isset($delivery->date_purchaseorder) ? $delivery->date_purchaseorder : 'None' }}</span> </th>
                    </tr>
                    <tr style="font-size:13pt; text-align: justify;">
                        <th>Invoice No.:  <span style="font-weight:normal">{{ isset($delivery->invoice_no) ? $delivery->invoice_no : 'None' }}</span> </th>
                        <th>Invoice Date:  <span style="font-weight:normal">{{ isset($delivery->date_invoice) ? $delivery->date_invoice : 'None' }}</span> </th>
                    </tr>
                    <tr style="font-size:13pt; text-align: justify;">
                        <th>Delivery Receipt No.:  <span style="font-weight:normal">{{ isset($delivery->delrcpt_no) ? $delivery->delrcpt_no : 'None' }}</span> </th>
                        <th>Delivery Date:  <span style="font-weight:normal">{{ isset($delivery->date_delivered) ? $delivery->date_delivered : 'None' }}</span> </th>
                    </tr>	
                </thead>
            </table>
            <br/> <br/>
            <table class="table table-striped table-bordered table-condensed" id="deliveryTable" width="100%" cellspacing="0">
                <thead>
                    <th class="col-sm-1">Stock No.</th>
                    <th class="col-sm-1">Item Name</th>
                    <th class="col-sm-1">Unit of Measure</th>
                    <th class="col-sm-1">Quantity Delivered</th>
                    <th class="col-sm-1">Unit Cost</th>
                </thead>
                <tbody>
                    @foreach($delivery->supplies as $supply)
                        @if($supply->details <> 'N/A')
                            <tr height="5">
                                <td align="left">{{ $supply->stocknumber }}</td>
                                <td align="left"> 
                                    <span style="font-size: @if(strlen($supply->details) > 130) 10px @elseif(strlen($supply->details) > 90) 11px @else 12px @endif">
                                        {{ $supply->details }}
                                    </span>
                                </td>
                                <td>{{ $supply->unit->name }}</td>
                                <td align="right">{{ $supply->pivot->quantity_delivered }}</td>
                                <td align="right">{{ $supply->pivot->unit_cost }}</td>
                            </tr>
                            @endif
                    @endforeach
                    <tr>
                        <td colspan=16 class="col-sm-12"><p style="font-weight:bold; text-align: center;">  ******************* Nothing Follows ******************* </p></td>
                    </tr>
                </tbody>
            </table>
            <hr color=#00000 />
            <table width="100%" style="font-size: 12px">
                <!-- Signatories Header-->
                <tr>
                    <td width="105px">   </td>
                    <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;">  Prepared By: </td>
                    <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;">  Delivery Received By: </td>
                    <td style="white-space: nowrap; text-align:center; font-weight:bold;"  class="col-xs-2">  Acknowledged By: </td>
                </tr>
                <!-- Signatories Signature-->
                <tr>
                    <td width="105px" class="text-left" style="white-space: nowrap; text-align:left; font-weight:bold;"  >Signature:</td>
                    <td width="250px" class="text-center">   <br/><br/>       </td>
                    <td width="250px" class="text-center">   <br/><br/>       </td>
                    <td class="text-center">                 <br/><br/>       </td>
                </tr>
                <!-- Signatories Name-->
                <tr>
                    <td width="105px" style="white-space: nowrap; text-align:left; font-weight:bold;" class="text-left">  Printed Name:</td>
                    <td width="250px" style="text-align:center; font-weight:bold; font-size: 12px;" width=50px>
                        <span>{{Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname}}</span>
                    </td>
                    <td width="250px" style="text-align:center; font-weight:bold; font-size: 12px;" width=50px>
                        <span>{{ $delivery->user_name }}</span>  
                    </td>
                    <td width="250px" style="text-align:center; font-weight:bold; font-size: 12px;" width=50px>
                        <span>{{ App\Office::findByCode(App\User::find($delivery->received_by)->office)->head }}</span>  
                    </td>
                </tr>
                <!-- Signatories Designation-->
                <tr>
                    <td width="105px" style="white-space: nowrap; text-align:left; font-weight:bold;" class="text-left">Designation:</td>
                    <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;font-size:10px; word-wrap: break-word;">
                        <span>{{ App\User::find(Auth::user()->id)->position }}, {{ App\Office::findByCode(Auth::user()->office)->name }}</span>
                    </td>
                    <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;font-size:10px; word-wrap: break-word;">
                        <span>{{ App\User::find($delivery->received_by)->position }}, {{ App\Office::findByCode(App\User::find($delivery->received_by)->office)->name }}</span>
                    </td>
                    <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;font-size:10px; word-wrap: break-word;">
                        <span>{{ App\Office::findByCode(App\User::find($delivery->received_by)->office)->head_title }}, {{ App\Office::findByCode(App\User::find($delivery->received_by)->office)->name }}</span>
                    </td>
                </tr>
                <!-- Signatories Date-->
                <tr>
                    <td width="105px" style="white-space: nowrap; text-align:left; font-weight:bold;" class="text-left">Date:</td>
                    <td width="250px" class="text-center"> </td>
                    <td width="250px" class="text-center"> </td>
                    <td class="text-center"> </td>
                </tr>
            </table>
        </div>  
    </body>
</html>
