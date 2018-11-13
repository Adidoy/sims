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
            <table class="table table-striped table-bordered table-condensed" id="inventoryTable" width="100%" cellspacing="0">
                <thead>
                    <tr><th colspan="16" style="color: #800000;">
                        <div style="margin-left: 5em;">
                            <div style="font-size:11pt; text-align: justify;">Republic of the Philippines  </div>
                            <div style="font-size:13pt; text-align: justify;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES </div>
                            <div style="font-size:11pt; text-align: justify;">Sta. Mesa, Manila</div>
                            <div style="font-size:10pt; text-align: justify;">Date Printed: <span class="pull-right" > {{ Carbon\Carbon::now()->format('d F Y h:m A') }} </span></div>
                        </div>
                    </th></tr>
                    <tr >
                        <th colspan="16"><h2 class="text-center">DELIVERY ACCEPTANCE REPORT  <small class="pull-right" style="text-align: right;">{{ $delivery->local }}</small></h2></th>
                    </tr>
                    <tr rowspan="2">
                        <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="16">Supplier: 
                            <u><span style="font-weight:normal">{{ isset($delivery->supplier_name) ? $delivery->supplier_name : 'None' }}</span></u>
                        </th>
                    </tr>                    
                    <tr rowspan="2">
                        <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="8">Purchase Order No.: 
                            <u><span style="font-weight:normal">{{ isset($delivery->purchaseorder_no) ? $delivery->purchaseorder_no : 'None' }}</span></u>
                        </th>
                        <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="8">Purchase Order Date: 
                            <u><span style="font-weight:normal">{{ isset($delivery->date_purchaseorder) ? $delivery->date_purchaseorder : 'None' }}</span></u>
                        </th>
                    </tr>
                    <tr rowspan="2">
                        <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="8">Invoice No.: 
                            <u><span style="font-weight:normal">{{ isset($delivery->invoice_no) ? $delivery->invoice_no : 'None' }}</span></u>
                        </th>
                        <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="8">Invoice Date: 
                            <u><span style="font-weight:normal">{{ isset($delivery->date_invoice) ? $delivery->date_invoice : 'None' }}</span></u>
                        </th>
                    </tr>
                    <tr rowspan="2">
                        <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="8">Delivery Receipt No.: 
                            <u><span style="font-weight:normal">{{ isset($delivery->delrcpt_no) ? $delivery->delrcpt_no : 'None' }}</span></u>
                        </th>
                        <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="8">Delivery Date: 
                            <u><span style="font-weight:normal">{{ isset($delivery->date_delivered) ? $delivery->date_delivered : 'None' }}</span></u>
                        </th>
                    </tr>                    
                    </thead>
            </table>
            <table class="table table-striped table-bordered table-condensed" width="100%" cellspacing="0">     
            <br/><br/>
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
                    <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;">  Delivery Acceptance Processed By: </td>
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
                    <td width="375px" style="text-align:center; font-weight:bold; font-size: 12px;" width=50px>
                        <span>{{ strtoupper($delivery->user_name) }}</span>  
                    </td>
                    <td width="375px" style="text-align:center; font-weight:bold; font-size: 12px;" width=50px>
                        <span>{{ strtoupper(App\Office::findByCode(App\User::find($delivery->received_by)->office)->head) }}</span>  
                    </td>
                </tr>
                <!-- Signatories Designation-->
                <tr>
                    <td width="105px" style="white-space: nowrap; text-align:left; font-weight:bold;" class="text-left">Designation:</td>
                    <td width="375px" style="white-space: nowrap; text-align:center; font-weight:bold;font-size:10px; word-wrap: break-word;">
                        <span>{{ App\User::find($delivery->received_by)->position }}, {{ App\Office::findByCode(App\User::find($delivery->received_by)->office)->name }}</span>
                    </td>
                    <td width="375px" style="white-space: nowrap; text-align:center; font-weight:bold;font-size:10px; word-wrap: break-word;">
                        <span>{{ App\Office::findByCode(App\User::find($delivery->received_by)->office)->head_title }}, {{ App\Office::findByCode(App\User::find($delivery->received_by)->office)->name }}</span>
                    </td>
                </tr>
                <!-- Signatories Date-->
                <tr>
                    <td width="105px" style="white-space: nowrap; text-align:left; font-weight:bold;" class="text-left">Date:</td>
                    <td width="375px" style="white-space: nowrap; text-align:center; font-weight:bold;font-size:10px; word-wrap: break-word;">{{ isset($delivery->date_processed) ? $delivery->date_processed : '' }}</td>
                    <td width="375px" style="white-space: nowrap; text-align:center; font-weight:bold;font-size:10px; word-wrap: break-word;">{{ Carbon\Carbon::now()->format('d F Y h:m A') }}</td>
                </tr>
            </table>
        </div>  
    </body>
</html>
