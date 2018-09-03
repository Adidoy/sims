<!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <title>$request->code</title>


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

        #stockcard {
          border-collapse: collapse;
          
        }

        #stockcard td, tbody, tfoot{
          border: solid #dde;
          font-family: "Arial";
          font-size: 12px;
        }

        #stockcard th {
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
        <table class="table table-striped table-bordered table-condensed" id="inventoryTable" width="100%" cellspacing="0">
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
            <tr >
              <th colspan="16"><h2 class="text-center">STOCK CARD</small></h2></th>
            </tr>
            <tr>
              <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="6">
                ITEM:
                <span style="font-weight:normal;
                  @if(strlen($supply->details) > 0)
                  @if(strlen($supply->details) > 80) font-size: 11px; 
                  @elseif(strlen($supply->details) > 60) font-size: 12px; 
                  @elseif(strlen($supply->details) > 20) font-size: 13px; 
                  @endif 
                  @endif">{{ $supply->details }}
                </span>
              </th>
              <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="10">
                STOCK NO.:  
                <span style="font-weight:normal">
                  {{ $supply->stocknumber }}
                </span>
              </th>
            </tr>
            <tr>
              <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="6">
                UNIT OF MEASURE:
                <span style="font-weight:normal;">
                  {{ $supply->unit->name }}
                </span>
              </th>
              <th class="text-left" style="font-size:10pt; text-align: justify;" colspan="10">
                REORDER POINT:  
                <span style="font-weight:normal">
                  {{ $supply->reorderpoint }}
                </span>
              </th>
            </tr>
          </thead>
        </table>
        <hr color=#00000 />
        <table id="stockcard" cellspacing="0" width="100%" style="font-size: 12px">
          <tbody>
            <tr>
              <td class="col-sm-5" style="white-space: nowrap; text-align:center; font-weight:bold;">Date</td>
              <td class="col-sm-5" style="white-space: nowrap; text-align:center; font-weight:bold;">Reference</td>
              <td class="col-sm-5" style="white-space: nowrap; text-align:center; font-weight:bold;">Receipt Qty</td>
              <td class="col-sm-5" style="white-space: nowrap; text-align:center; font-weight:bold;">Issue Qty</td>
              <td class="col-sm-5" style="white-space: nowrap; text-align:center; font-weight:bold;">Office</td>
              <td class="col-sm-5" style="white-space: nowrap; text-align:center; font-weight:bold;">Balance Qty</td>
              <td class="col-sm-5" style="white-space: nowrap; text-align:center; font-weight:bold;">Days to Consume</td>
            </tr>
            @if(count($supply->stockcards) > 0)
            @foreach($supply->stockcards as $stockcard)
            <tr>
              <td style="white-space: nowrap; text-align: left; padding-left: 5px; padding-right: 5px;">{{ Carbon\Carbon::parse($stockcard->date)->toFormattedDateString() }}</td>
              <td style="white-space: nowrap; text-align: left; padding-left: 5px; padding-right: 5px;">{{ $stockcard->reference_information }}</td>
              <td align="right">{{ $stockcard->received_quantity }}</td>
              <td align="right">{{ $stockcard->issued_quantity }}</td>
              <td style="white-space: nowrap; text-align: left; padding-left: 5px; padding-right: 5px;">
              <span style="font-weight:normal; 
                @if(strlen($stockcard->organization) > 0)
                  @if(strlen($stockcard->organization) > 60) font-size: 12px; 
                  @elseif(strlen($stockcard->organization) > 40) font-size: 12px; 
                  @elseif(strlen($stockcard->organization) > 20) font-size: 12px; 
                  @endif 
                @endif">{{ $stockcard->organization }}
              </span> </td>
              <td align="right">{{ $stockcard->balance_quantity }}</td>
              <td style="white-space: nowrap; text-align: center; padding-left: 5px; padding-right: 5px;">{{ $stockcard->daystoconsume == 'Not Applicable' ? 'N/A': $stockcard->daystoconsume}}</td>
            </tr>
            @endforeach
            @else
            <tr>
              <td colspan=7 class="col-sm-12"><p class="text-center">  No record </p></td>
            </tr>
            @endif
            <tr>
              <td colspan=16 class="col-sm-12" style="font-weight:bold; text-align: center;">******************* Nothing Follows *******************</td>
            </tr>
          </tbody>
        </table>
        <hr color=#00000 />
        <br/><br/><br/>
        <table class="table table-striped table-bordered table-condensed" id="inventoryTable" width="100%" cellspacing="0">
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
        </table>
      </div>
    </body>