<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Print Summary</title>

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
                  <div style="font-size:10pt; text-align: justify;"><span class="pull-right">Date Printed: {{ Carbon\Carbon::now()->format("d F Y h:m A") }} </span></div>
                </div>
            </th>
          </tr>
          <tr >
              <th><h2 class="text-center text-muted">SUPPLIES QUANTITY as of {{ $asof }}<small class="pull-right"></small></h2></th>
          </tr>
        </thead>
      </table>
      <table id="rsmiTable" cellspacing="0" width="100%" style="font-size: 12px">
        <tbody>
          <tr>
            <td style="white-space: nowrap; text-align:center; font-weight:bold;">Stock No.</th>
            <td style="white-space: nowrap; text-align:left; font-weight:bold;">Details</th>
            <td style="white-space: nowrap; text-align:center; font-weight:bold;">Balance</th>
          </tr>
          @foreach($endingBalance as $report)
            <tr>
              <td style="white-space: nowrap; text-align:center;">{{ $report->stocknumber }}</td>
              <td style="white-space: nowrap; text-align:left;">{{ $report->details }}</td>
              <td style="white-space: nowrap; text-align:right;">{{ $report->balance }}</td>
            </tr>
          @endforeach
          <tr>
            <td colspan=3><p style="font-weight:bold; text-align: center;">  ******************* Nothing Follows ******************* </p></td>
          </tr>
        </tbody>
      </table>
      <br /><br />
      <table cellspacing="0" width="100%" style="font-size: 12px">
        <tbody>
          <tr>
            <td class="text-center" colspan="6">  Prepared By: </th>
            <td class="text-center" colspan="6">  Approved By: </th>
          </tr>
          <tr>
            <td style="text-align: center;" colspan="6">
              <br />
              <br />
              <span id="name" style="margin-top: 30px; font-size: 15px;  font-weight: bold;"> {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
              <br />
              <span id="office" class="text-center" style="font-size:10px;">{{ App\Office::findByCode(Auth::user()->office)->name }}</span>
            </td>
            <td style="text-align: center;" colspan="6">
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
  </body>
