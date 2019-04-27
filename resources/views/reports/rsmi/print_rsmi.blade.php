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
            <td class="col-sm-1" style="white-space: nowrap; text-align:center; font-weight:bold;">Amount</th>
          </tr>
          @foreach($rsmi as $report)
            <tr>
              <td style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;">{{ $report->local }}</td>
              <td style="white-space: normal;text-align: justify; padding-left: 5px; padding-right: 5px;">{{ isset($report->office) ? App\Models\Sector::findSectorCode($report->office) : 'n/a' }} - {{ App\Office::find($report->office)->name }}</td>
              <td style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;">{{ $report->stocknumber }}</td>
              <td style="white-space: normal;text-align: justify; padding-left: 5px; padding-right: 5px;">{{ $report->details }}</td>
              <td align="center">{{ $report->name }}</td>
              <td align="right">{{ $report->quantity_issued }}</td>
              <td align="right">{{ isset($report->unitprice) ? $report->unitprice : '0.00' }}</td>
              <td align="right">{{ isset($report->amount) ? $report->amount : '0.00' }}</td>
            </tr>
          @endforeach
          <tr>
            <td colspan="5" style="font-weight:bold; text-align: right;">Total Quantity Released: </td>
            <td <span class="pull-right"style="font-weight:bold; text-align: right;"> 0.00 </span></td>
            <td colspan="1">N/A</td>
            <td colspan="1">N/A</td>
          </tr>
          <tr>
            <td colspan=8 class="col-sm-12"><p style="font-weight:bold; text-align: center;">  ******************* Nothing Follows ******************* </p></td>
          </tr>
          <tr>
        </tbody>
      </table>
      <br /><br /><br />
      <table cellspacing="0" width="100%" style="font-size: 12px">
        </tbody>
            <td class="text-center" colspan="3">  Prepared By: </th>
            <td class="text-center" colspan="5">  Approved By: </th>
          </tr>
          <tr>
            <td style="text-align: center;" colspan="3">
              <br />
              <br />
              <span id="name" style="margin-top: 30px; font-size: 15px;  font-weight: bold;"> {{ strtoupper(Auth::user()->firstname) }} {{ strtoupper(Auth::user()->lastname) }}</span>
              <br />
              <span id="office" class="text-center" style="font-size:10px;">{{ isset(Auth::user()->position) ? ucwords(Auth::user()->position) : '[ Designation ]' }}</span>
              <br />
              <span id="office" class="text-center" style="font-size:10px;">{{ App\Office::findByCode(Auth::user()->office)->name }}</span>
            </td>
            <td style="text-align: center;" colspan="5">
              <br />
              <br />
              <span id="name" class="text-muted" style="margin-top: 30px; font-size: 15px;  font-weight: bold;">{{ (App\Office::findByCode(Auth::user()->office)->head != '') ? strtoupper(App\Office::findByCode(Auth::user()->office)->head) : '[ Signature Over Printed Name ]' }}</span>
              <br />
              <span id="office" class="text-center" style="font-size:10px;">{{ (App\Office::findByCode(Auth::user()->office)->head_title != '') ? ucwords(App\Office::findByCode(Auth::user()->office)->head_title) : '[ Designation ]' }}</span>
              <br />              
              <span id="office" class="text-center" style="font-size:10px;">{{ App\Office::findByCode(Auth::user()->office)->name }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- End of RSMI -->
    <p style="page-break-after: always;">&nbsp;</p>
    <!-- Start of Recap -->
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
          @foreach($recap as $report)
          <tr>
            <td style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;">{{ $report->stocknumber }}</td>
            <td style="white-space: normal;text-align: justify; padding-left: 5px; padding-right: 5px;">{{ $report->details }}</td>
            <td style="white-space: normal;text-align: right; padding-left: 15px; padding-right: 15px;">{{ $report->quantity_issued }}</td>
            <td align="right">{{ isset($report->unitprice) ? $report->unitprice : '0.00' }}</td>
            <td align="right">{{ isset($report->amount) ? $report->amount : '0.00' }}</td>
            <td align="center">{{ isset($report->uacs) ? $report->uacs : 'N/A' }}</td>
          </tr>
          @endforeach
          <tr>
            <td colspan="2" style="font-weight:bold; text-align: right;">Total Quantity Released: </td>
            <td <span style="white-space: normal;text-align: right; padding-left: 15px; padding-right: 15px;"> 0.00 </span></td>
            <td colspan="1" style="text-align: right;">N/A</td>
            <td colspan="1" style="text-align: right;">N/A</td>
            <td colspan="1" align="center">N/A</td>
          </tr>
          <tr>
            <td colspan=7 class="col-sm-12"><p style="font-weight:bold; text-align: center;">  ******************* Nothing Follows ******************* </p></td>
          </tr>
          <tr>
        </tbody>
      </table>
      <br /><br /><br />
      <table cellspacing="0" width="100%" style="font-size: 12px">
        </tbody>
            <td class="text-center" colspan="3">  Prepared By: </th>
            <td class="text-center" colspan="5">  Approved By: </th>
          </tr>
          <tr>
            <td style="text-align: center;" colspan="3">
              <br />
              <br />
              <span id="name" style="margin-top: 30px; font-size: 15px;  font-weight: bold;"> {{ strtoupper(Auth::user()->firstname) }} {{ strtoupper(Auth::user()->lastname) }}</span>
              <br />
              <span id="office" class="text-center" style="font-size:10px;">{{ isset(Auth::user()->position) ? ucwords(Auth::user()->position) : '[ Designation ]' }}</span>
              <br />
              <span id="office" class="text-center" style="font-size:10px;">{{ App\Office::findByCode(Auth::user()->office)->name }}</span>
            </td>
            <td style="text-align: center;" colspan="5">
              <br />
              <br />
              <span id="name" class="text-muted" style="margin-top: 30px; font-size: 15px;  font-weight: bold;">{{ (App\Office::findByCode(Auth::user()->office)->head != '') ? strtoupper(App\Office::findByCode(Auth::user()->office)->head) : '[ Signature Over Printed Name ]' }}</span>
              <br />
              <span id="office" class="text-center" style="font-size:10px;">{{ (App\Office::findByCode(Auth::user()->office)->head_title != '') ? ucwords(App\Office::findByCode(Auth::user()->office)->head_title) : '[ Designation ]' }}</span>
              <br />              
              <span id="office" class="text-center" style="font-size:10px;">{{ App\Office::findByCode(Auth::user()->office)->name }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- End of Recap -->         