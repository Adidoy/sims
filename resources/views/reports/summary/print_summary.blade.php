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
    <table cellspacing="0" width="100%" style="font-size: 12px">
        <tr>
          <td rowspan = 4><img src="{{ asset('images/logo.png') }}" style="height: 80px;width:auto;" /></td>
          <td style="font-size:11pt; text-align: justify; color: #800000;">Republic of the Philippines</td>
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
          <td style="font-size:14pt; text-align: center;" colspan = 15><strong>ENDING INVENTORY</strong></td>
        </tr>
        <tr>
          <td style="font-size:12pt; text-align: center;" colspan = 16>For the Month of {{ $asof }}</td>
        </tr>        
        <tr>
          <td style="font-size:11pt; text-align: justify; color: #800000;"><br/></td>
        </tr>        
      </table>    
      <table id="rsmiTable" cellspacing="0" width="100%" style="font-size: 12px">
        <tbody>
          <tr>
            <td style="white-space: nowrap; text-align:center; font-weight:bold;">Stock No.</th>
            <td style="white-space: nowrap; text-align:left; font-weight:bold;">Details</th>
            <td style="white-space: nowrap; text-align:center; font-weight:bold;">Qty Received</th>
            <td style="white-space: nowrap; text-align:center; font-weight:bold;">Qty Issued</th>
            <td style="white-space: nowrap; text-align:center; font-weight:bold;">Balance</th>
          </tr>
          @foreach($endingBalance as $report)
            <tr>
              <td style="white-space: nowrap; text-align:center;">{{ $report->stocknumber }}</td>
              <td style="white-space: nowrap; text-align:left;">{{ $report->details }}</td>
              <td style="white-space: nowrap; text-align:right;">{{ $report->received }}</td>
              <td style="white-space: nowrap; text-align:right;">{{ $report->issued }}</td>
              <td style="white-space: nowrap; text-align:right;">{{ $report->balance }}</td>
            </tr>
          @endforeach
          <tr>
            <td colspan = 5><p style="font-weight:bold; text-align: center;">  ******************* Nothing Follows ******************* </p></td>
          </tr>
        </tbody>
      </table>
      <br /><br />
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
  </body>