<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Inspection Report :: {{ $inspection->local }}</title>


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

            #inspectionTable {
                border-collapse: collapse;
            }

            #inspectionTable td, tbody, tfoot{
                border: solid #dde;
                font-family: "Arial";
                font-size: 12px;
            }

            #inspectionTable th {
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
            <table class="table table-striped table-bordered table-condensed" id="headerTable" width="100%" cellspacing="0">
                <thead>
                    <tr><th colspan="16" style="color: #800000;">
                        <div style="margin-left: 5em;">
                            <div style="font-size:11pt; text-align: justify;">Republic of the Philippines  </div>
                            <div style="font-size:13pt; text-align: justify;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES </div>
                            <div style="font-size:11pt; text-align: justify;">Sta. Mesa, Manila</div>
                            <div style="font-size:10pt; text-align: justify;">Date Printed: <span class="pull-right" > {{ Carbon\Carbon::now()->format('d F Y h:m A') }} </span></div>
                        </div>
                    </th></tr>
                    <tr>
                        <th colspan="28"><div style="text-align: center;"><h2>INSPECTION AND ACCEPTANCE REPORT</h2></div></th>
                    </tr>
                    <tr rowspan="2">
                        <th class="text-right" style="font-size:10pt; text-align: justify;" colspan="14">Supplier: 
                            <u><span style="font-weight:normal">{{ isset($inspection->delivery->supplier_name) ? $inspection->delivery->supplier_name : 'None' }}</span></u>
                        </th>
                        <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="14">IAR No.: 
                            <u><span style="font-weight:normal">{{ $inspection->local }}</span></u>
                        </th>
                    </tr>                    
                    <tr rowspan="2">
                        <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="7">Purchase Order No.: 
                            <u><span style="font-weight:normal">{{ isset($inspection->delivery->purchaseorder_no) ? $inspection->delivery->purchaseorder_no : 'None' }}</span></u>
                        </th>
                        <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="7">Purchase Order Date: 
                            <u><span style="font-weight:normal">{{ isset($inspection->delivery->date_purchaseorder) ? $inspection->delivery->date_purchaseorder : 'None' }}</span></u>
                        </th>
                        <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="7">Invoice No.: 
                            <u><span style="font-weight:normal">{{ isset($inspection->delivery->invoice_no) ? $inspection->delivery->invoice_no : 'None' }}</span></u>
                        </th>
                        <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="7">Invoice Date: 
                            <u><span style="font-weight:normal">{{ isset($inspection->delivery->date_invoice) ? $inspection->delivery->date_invoice : 'None' }}</span></u>
                        </th>                        
                    </tr>                    
                </thead>
            </table>
            <br/> <br/>
            <table class="table table-striped table-bordered table-condensed" id="inspectionTable" width="100%" cellspacing="0">
                <thead>
                    <th class="text-center" style="width:20px;">Stock No.</th>
                    <th class="text-center">Item Name</th>
                    <th class="text-center">Unit of Measure</th>
                    <th class="text-center">Unit Cost</th>
                    <th class="text-center">Quantity Delivered</th>
                    <th class="text-center">Quantity Good</th>
                    <th class="text-center">Quantity Rejected</th>
                    <th class="text-center">Inspector's Remarks</th>
                </thead>
                <tbody>
                    @foreach($inspection->supplies as $supply)
                        @if($supply->details <> 'N/A')
                            <tr height="5">
                                <td style="text-align: justify;" width="75">{{ $supply->stocknumber }}</td>
                                <td style="text-align: justify;"> 
                                    <span style="font-size: @if(strlen($supply->details) > 130) 10px @elseif(strlen($supply->details) > 90) 11px @else 12px @endif">
                                        {{ $supply->details }}
                                    </span>
                                </td>
                                <td>{{ $supply->unit->name }}</td>
                                <td style="text-align: right;" width="75">{{ $supply->pivot->unit_cost }}</td>
                                <td style="text-align: right;"width="50">{{ $supply->pivot->quantity_passed + $supply->pivot->quantity_failed }}</td>
                                <td style="text-align: right;"width="50">{{ $supply->pivot->quantity_passed }}</td>
                                <td style="text-align: right;"width="50">{{ $supply->pivot->quantity_failed }}</td>
                                <td style="text-align: justify;"width="100">{{ $supply->pivot->comment }}</td>
                            </tr>
                            @endif
                    @endforeach
                    <tr>
                        <td colspan=16 class="col-sm-12">
                            <i><div style="text-align: center;">  Per Delivery Receipt No. {{ isset($inspection->delivery->delrcpt_no) ? $inspection->delivery->delrcpt_no : 'None' }}</div></i>
                            <i><div style="text-align: center;">  Delivery Date: {{ isset($inspection->delivery->date_delivered) ? $inspection->delivery->date_delivered : 'None' }}</div></i>
                            <p style="font-weight:bold; text-align: center;">  ******************* Nothing Follows ******************* </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr color=#00000 />
            <table style="font-size: 12px" id="inspectionTable" width="100%" cellspacing="0">
                <!-- Signatories Header-->
                <tr>
                    <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;" colspan="2">Inspection</td>
                    <td style="white-space: nowrap; text-align:center; font-weight:bold;"  class="col-xs-2" colspan="1">Acceptance</td>
                </tr>
                <!-- Notes -->
                <tr>
                    <td width="250px" class="text-center" colspan="2">
                        <div><i>Inspected, verified and found in order as to quantity and specification, delivery within allowable period.</i></div>
                    </td>
                    <td class="text-center">
                        @if($inspection->remarks == 'Complete')
                            <div style="position:relative; width:150px">
                                <div style="background-color:black; border-style:solid; border-width:1px; width:40px; height:15px; position:absolute; float:left;"></div>
                                <div style="position:relative; margin-left:5em">Complete</div>
                            </div>
                            <div style="position:relative; height:5px"></div>
                            <div style="position:relative; width:150px">
                                <div style="background-color:white; border-style:solid; border-width:1px; width:40px; height:15px; position:absolute; float:left;"></div>
                                <div style="position:relative; margin-left:5em">Partial</div>
                            </div>
                        @else
                            <div style="position:relative; width:150px">
                                <div style="background-color:white; border-style:solid; border-width:1px; width:40px; height:15px; position:absolute; float:left;"></div>
                                <div style="position:relative; margin-left:5em">Complete</div>
                            </div>
                            <div style="position:relative; height:5px"></div>
                            <div style="position:relative; width:150px">
                                <div style="background-color:black; border-style:solid; border-width:1px; width:40px; height:15px; position:absolute; float:left;"></div>
                                <div style="position:relative; margin-left:5em">Partial</div>
                            </div>
                        @endif
                    </td>
                </tr>
                <!-- Signatures -->
                <tr>
                    <td width="250px" class="text-center"><br/><br/><br/><br/></td>
                    <td width="250px" class="text-center"><br/><br/><br/><br/></td>
                    <td class="text-center"><br/><br/><br/><br/></td>
                </tr>                
                <!-- Signatories Name-->
                <tr>
                    <td width="250px" style="text-align:center; font-weight:bold; font-size: 12px;" width=50px>
                        <span>{{strtoupper($inspection->inspection_personnel)}}</span>
                    </td>
                    <td width="250px" style="text-align:center; font-weight:bold; font-size: 12px;" width=50px>
                        <span>{{strtoupper($inspection->inspection_approval)}}</span>  
                    </td>
                    <td width="250px" style="text-align:center; font-weight:bold; font-size: 12px;" width=50px>
                        <span>{{strtoupper($inspection->property_custodian_acknowledgement)}}</span>  
                    </td>
                </tr>
                <!-- Signatories Designation-->
                <tr>
                    <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;font-size:10px; word-wrap: break-word;">
                        <span>Member, Inspection Management Unit</span>
                    </td>
                    <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;font-size:10px; word-wrap: break-word;">
                        <span>Chairman, Inspection Management Unit</span>
                    </td>
                    <td width="250px" style="white-space: nowrap; text-align:center; font-weight:bold;font-size:10px; word-wrap: break-word;">
                        <span>Director, PSMO</span>
                    </td>
                </tr>
                <!-- Signatories Date-->
                <tr>
                    <td width="250px" class="text-center" colspan="2">Date Inspected: {{$inspection->date_inspected}}</td>
                    <td width="250px" class="text-center">Date Acknowledged: {{$inspection->acknowledgement_date}}</td>
                </tr>
            </table>            
        </div>  
    </body>
</html>
