<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Print RSMI</title>

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
      <table id="rsmiTable" cellspacing="0" width="100%" style="font-size: 12px">
        <thead>
          <tr>
            <th colspan="16" style="color: #800000;">
                <div style="margin-left: 5em;">
                  <div style="font-size:11pt; text-align: justify;">Republic of the Philippines  </div>
                  <div style="font-size:13pt; text-align: justify;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES </div>
                  <div style="font-size:11pt; text-align: justify;">Sta. Mesa, Manila</div>
                  <div style="font-size:10pt; text-align: justify;"><span class="pull-right">Date Printed: {{ Carbon\Carbon::now()->format("d F Y h:m A") }}</span></div>
                </div>
            </th>
          </tr>
          <tr >
              <th><h2 class="text-center text-muted">REPORT ON SUPPLIES AND MATERIALS USED <small class="pull-right">Appendix 64</small></h2></th>
          </tr>
          <tr>
            <th class="pull-right" style="white-space: nowrap;font-weight: normal;"><h3>R.I.S. {{ isset($start) ? $start : 'N/A' }} to {{ isset($end) ? $end : 'N/A' }}</h3></th>
          </tr>
        </thead>
      </table>
      <table id="rsmiTable" cellspacing="0" width="100%" style="font-size: 12px">
        <tbody>
          <tr>
            <td class="col-sm-5" style="white-space: nowrap; text-align:center; font-weight:bold;">RIS No.</th>
            <td class="col-sm-1" style="white-space: nowrap; text-align:center; font-weight:bold;">Responsibility Center Code</th>
            <td class="col-sm-2" style="white-space: nowrap; text-align:center; font-weight:bold;">Stock No.</th>
            <td class="col-sm-1" style="white-space: nowrap; text-align:center; font-weight:bold;">Item</th>
            <td class="col-sm-1" style="white-space: nowrap; text-align:center; font-weight:bold;">Unit</th>
            <td class="col-sm-1" style="white-space: nowrap; text-align:center; font-weight:bold;">Qty Issued</th>
            <td class="col-sm-1" style="white-space: nowrap; text-align:center; font-weight:bold;">Unit Cost</th>
            <td class="col-sm-3" style="white-space: nowrap; text-align:center; font-weight:bold;">Amount</th>
          </tr>
          @foreach($rsmi->stockcards as $report)
            <tr>
              <td style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;">{{ $report->reference }}</td>
              <td style="white-space: normal;text-align: justify; padding-left: 5px; padding-right: 5px;">{{ isset($report->sector_office) ? $report->sector_office : 'n/a' }} - {{ $report->organization }}</td>
              <td style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;">{{ $report->supply->stocknumber }}</td>
              <td style="white-space: normal;text-align: justify; padding-left: 5px; padding-right: 5px;">{{ $report->supply->details }}</td>
              <td align="center">{{ $report->supply->unit_name }}</td>
              <td align="right">{{ $report->issued_quantity }}</td>
              <td align="right">{{ number_format($report->pivot->unitcost,2) }}</td>
              <td align="right">{{ number_format($report->issued_quantity * $report->pivot->unitcost, 2) }}</td>
            </tr>
          @endforeach
          <tr>
            <td colspan="5" style="font-weight:bold; text-align: right;">Total Quantity Released: </td>
            <td <span class="pull-right"style="font-weight:bold; text-align: right;"> {{ $rsmi->stockcards->sum('issued_quantity') }} </span></td>
            <td colspan="1">N/A</td>
            <td colspan="1">N/A</td>
          </tr>
          <tr>
            <td colspan=8 class="col-sm-12"><p style="font-weight:bold; text-align: center;">  ******************* Nothing Follows ******************* </p></td>
          </tr>
          <tr>
            <td class="text-center" colspan="3">  Prepared By: </th>
            <td class="text-center" colspan="5">  Approved By: </th>
          </tr>
          <tr>
            <td style="text-align: center;" colspan="3">
              <br />
              <br />
              <span id="name" style="margin-top: 30px; font-size: 15px;  font-weight: bold;"> {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
              <br />
              <span id="office" class="text-center" style="font-size:10px;">{{ App\Office::findByCode(Auth::user()->office)->name }}</span>
            </td>
            <td style="text-align: center;" colspan="5">
              <br />
              <br />
              <span id="name" class="text-muted" style="margin-top: 30px; font-size: 15px;  font-weight: bold;">{{ (App\Office::findByCode(Auth::user()->office)->head != '') ? App\Office::findByCode(Auth::user()->office)->head : '[ Signature Over Printed Name ]' }}</span>
              <br />
              <span id="office" class="text-center" style="font-size:10px;">{{ App\Office::findByCode(Auth::user()->office)->name }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <p style="page-break-after: always;">&nbsp;</p>
    <div id="content" class="col-sm-12">
      <table id="rsmiTable" cellspacing="0" width="100%" style="font-size: 12px">
        <thead>
          <tr>
            <th colspan="16" style="color: #800000;">
              <div style="margin-left: 5em;">
                <div style="font-size:11pt; text-align: justify;">Republic of the Philippines  </div>
                <div style="font-size:13pt; text-align: justify;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES </div>
                <div style="font-size:11pt; text-align: justify;">Sta. Mesa, Manila</div>
                <div style="font-size:10pt; text-align: justify;"><span class="pull-right"> {{ Carbon\Carbon::now()->toDayDateTimeString() }} </span></div>
              </div>
            </th>
          </tr>
          <tr>
            <th class="pull-right" style="white-space: nowrap;font-weight: normal;"><h3>Recapitulation</h3></th>
          </tr>
        </thead>
      </table>
      <table  id="rsmiTable" cellspacing="0" width="100%" style="font-size: 12px">
        <tr>
          <td class="col-md-1" style="white-space: nowrap; text-align:center; font-weight:bold;">Stock No.</th>
          <td class="col-md-1" style="white-space: nowrap; text-align:center; font-weight:bold;">Item Description</th>
          <td class="col-md-1" style="white-space: nowrap; text-align:center; font-weight:bold;">Qty</th>
          <td class="col-md-1" style="white-space: nowrap; text-align:center; font-weight:bold;">Unit Cost</th>
          <td class="col-md-1" style="white-space: nowrap; text-align:center; font-weight:bold;">Total Cost</th>
          <td class="col-md-1" style="white-space: nowrap; text-align:center; font-weight:bold;">UACS Object Code</th>
        </tr>
        <tbody>
          @foreach($recapitulation as $report)
          <tr>
            <td style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;">{{ $report->stocknumber }}</td>
            <td style="white-space: normal;text-align: justify; padding-left: 5px; padding-right: 5px;">{{ $report->details }}</td>
            <td style="white-space: normal;text-align: right; padding-left: 15px; padding-right: 15px;">{{ $report->issued_quantity }}</td>
            <td align="right">{{ number_format($report->unitcost,2) }}</td>
            <td align="right">{{ number_format($report->amount, 2) }}</td>
            <td>{{ $report->uacs_code }}</td>
          </tr>
          @endforeach
          <tr>
            <td colspan="2" style="font-weight:bold; text-align: right;">Total Quantity Released: </td>
            <td <span style="white-space: normal;text-align: right; padding-left: 15px; padding-right: 15px;"> {{ $recapitulation->sum('issued_quantity') }} </span></td>
            <td colspan="1" style="text-align: right;">N/A</td>
            <td colspan="1" style="text-align: right;">N/A</td>
            <td colspan="1"></td>
          </tr>
          <tr>
            <td colspan=7 class="col-sm-12"><p style="font-weight:bold; text-align: center;">  ******************* Nothing Follows ******************* </p></td>
          </tr>
          <tr>
            <td class="text-center" colspan="2">  Prepared By: </th>
            <td class="text-center" colspan="4">  Approved By: </th>
          </tr>
          <tr>
            <td style="text-align: center;" colspan="2">
              <br />
              <br />
              <span id="name" style="margin-top: 30px; font-size: 15px;  font-weight: bold;"> {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
              <br />
              <span id="office" class="text-center" style="font-size:10px;">{{ App\Office::findByCode(Auth::user()->office)->name }}</span>
            </td>
            <td style="text-align: center;" colspan="4">
              <br />
              <br />
              <span id="name" class="text-muted" style="margin-top: 30px; font-size: 15px;  font-weight: bold;">{{ (App\Office::findByCode(Auth::user()->office)->head != '') ? App\Office::findByCode(Auth::user()->office)->head : '[ Signature Over Printed Name ]' }}</span>
              <br />
              <span id="office" class="text-center" style="font-size:10px;">{{ App\Office::findByCode(Auth::user()->office)->name }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <p style="page-break-after: always;">&nbsp;</p>
    <div id="content" class="col-sm-12">
      <table id="rsmiTable" cellspacing="0" width="100%" style="font-size: 12px">
        <thead>
          <tr>
            <th colspan="16" style="color: #800000;">
              <div style="margin-left: 5em;">
                <div style="font-size:11pt; text-align: justify;">Republic of the Philippines  </div>
                <div style="font-size:13pt; text-align: justify;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES </div>
                <div style="font-size:11pt; text-align: justify;">Sta. Mesa, Manila</div>
                <div style="font-size:10pt; text-align: justify;"><span class="pull-right"> {{ Carbon\Carbon::now()->toDayDateTimeString() }} </span></div>
              </div>
            </th>
          </tr>
          <tr>
            <th class="pull-right" style="white-space: nowrap;font-weight: normal;"><h3>RIS List and Statuses</h3></th>
          </tr>
        </thead>
      </table> 
      <table  id="rsmiTable" cellspacing="0" width="100%" style="font-size: 12px">
        <tr>
          <td class="col-md-1" style="white-space: nowrap; text-align:center; font-weight:bold;">RIS No.</td>
          <td class="col-md-1" style="white-space: nowrap; text-align:center; font-weight:bold;">Office</td>
          <td class="col-md-1" style="white-space: nowrap; text-align:center; font-weight:bold;">Status</td>
          <td class="col-md-1" style="white-space: nowrap; text-align:center; font-weight:bold;">Remarks</td>
        </tr>
        <tbody>
          @foreach($ris as $request)
          <tr>
            <td style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;">{{ $request->code }}</td>
            <td style="white-space: normal;text-align: justify; padding-left: 5px; padding-right: 5px;">{{ App\Office::find($request->office_id)->name }}</td>
            <td style="white-space: normal;text-align: center; padding-left: 15px; padding-right: 15px;">{{ $request->status }}</td>
            <td style="white-space: normal;text-align: left; padding-left: 15px; padding-right: 15px;">{{ $request->remarks }}</td>
          </tr>
          @endforeach
          <tr>
            <td colspan=7 class="col-sm-12"><p style="font-weight:bold; text-align: center;">  ******************* Nothing Follows ******************* </p></td>
          </tr>
          <tr>
            <td class="text-center" colspan="2">  Prepared By: </th>
            <td class="text-center" colspan="4">  Approved By: </th>
          </tr>
          <tr>
            <td style="text-align: center;" colspan="2">
              <br />
              <br />
              <span id="name" style="margin-top: 30px; font-size: 15px;  font-weight: bold;"> {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
              <br />
              <span id="office" class="text-center" style="font-size:10px;">{{ App\Office::findByCode(Auth::user()->office)->name }}</span>
            </td>
            <td style="text-align: center;" colspan="4">
              <br />
              <br />
              <span id="name" class="text-muted" style="margin-top: 30px; font-size: 15px;  font-weight: bold;">{{ (App\Office::findByCode(Auth::user()->office)->head != '') ? App\Office::findByCode(Auth::user()->office)->head : '[ Signature Over Printed Name ]' }}</span>
              <br />
              <span id="office" class="text-center" style="font-size:10px;">{{ App\Office::findByCode(Auth::user()->office)->name }}</span>
            </td>
          </tr>
        </tbody>
      </table>             
    </div>   
