<?php

namespace App\Http\Controllers\Requests\Custodian;


use DB;
use App;
use PDF;
use Auth;
use Event;
use Carbon;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Requests\Custodian\RequestCustodian;
use App\Models\Requests\Client\RequestDetailsClient;

class RequestsCustodianController extends Controller
{
  public function index(Request $request) 
  {
    if(isset($request->type)) {
      if($request->type == 'pending') {
          $requests = RequestCustodian::whereNull('status')->get();
      } elseif ($request->type == 'approved') {
          $requests = RequestCustodian::where('status','=','approved')->get();
      } elseif ($request->type == 'released') {
          $requests = RequestCustodian::where('status','=','released')->get();
      } elseif ($request->type == 'disapproved') {
          $requests = RequestCustodian::where(function($query) {
                  $query->where('status','=','cancelled')
                          ->orWhere('status','=','disapproved');
              })
              ->get();
      }
    } else {
      return redirect("/");
    }
    $type = $request->type;
    if($request->ajax()) {
      return datatables($requests)->toJson();
    }
    return view('requests.custodian.forms.index', compact('type', 'requests'));
  }

  public function show(Request $request, $id)
  {
    $requests = RequestCustodian::find($id);
    if($request->ajax()) {
      $supplies = $requests->supplies;
      return json_encode([
        'data' => $supplies
      ]);
    }
    if ($requests->status == 'Pending') {
        return redirect('request/custodian/'.$id.'/approve');
    } else if($requests->status == 'Approved') {
      return redirect('request/custodian/'.$id.'/release');
    } else {
        return view('requests.custodian.forms.show')
        ->with('request',$requests)
        ->with('title','Request');
    }
  }

  public function getApprovalForm(Request $request, $id)
  {
    $requests = RequestCustodian::find($id);
    return view('requests.custodian.forms.approval')
      ->with('request', $requests)
      ->with('action', 'request')
      ->with('title', "Approval :: RIS-".$requests->local);
  }

  public function getReleaseForm(Request $request, $id)
  {
    $requests = RequestCustodian::find($id);
    return view('requests.custodian.forms.release')
      ->with('request', $requests)
      ->with('action', 'request')
      ->with('title', "Release :: RIS-".$requests->local);
  }

  public function printRIS($id) 
  {
    $orientation = 'Portrait';
    $request = RequestCustodian::find($id);
    $signatory = '';

    $row_count = 16;
    $adjustment = 0;
    if(isset($request->supplies)):
      $data_count = count($request->supplies) % $row_count;
      if($data_count == 0 || (($data_count < 5) && (count($request->supplies) > $row_count))):
        if((count($request->supplies) > $row_count) && ($data_count < 7)):
          $remaining_rows = $data_count + $row_count + $adjustment;
        else:
          $remaining_rows = 0;
        endif;
      else:
        $remaining_rows = $row_count - $data_count;
      endif;
    endif;

    $user = App\User::where('id','=',$request->requestor_id)->first();
    $office = App\Office::where('code','=',$user->office)->first();
    $sector = App\Office::where('id','=',$office->head_office)->first();
    $issuedby = App\User::where('id','=',$request->issued_by)->first();
    
    //checks if the sector has a head_office
    //for lvl 2 offices
    if(isset($sector->head_office)): 
      $office = App\Office::where('id','=',$office->head_office)->first(); 
      $sector = App\Office::where('id','=',$sector->head_office)->first(); 
    elseif($office->head_office == NULL): 
        if(App\Office::where('code','like',$office->code.'-A'.$office->code)->first() !== NULL):
          $office = App\Office::where('code','like',$office->code.'-A'.$office->code)->first();
        endif;
    endif; 
    
    //checks if the sector has a head_office
    //for lvl 3 offices
    if(isset($sector->head_office)):
      $office = App\Office::where('id','=',$office->head_office)->first();
      $sector = App\Office::where('id','=',$sector->head_office)->first();
    endif;
    
    //checks if the sector has a head_office
    //for lvl 4 offices
    if(isset($sector->head_office)):
        $office = App\Office::where('id','=',$office->head_office)->first();
        $sector = App\Office::where('id','=',$sector->head_office)->first();
    endif;

    if($request->status == 'Released' || $request->status == 'released'):
      $signatory = App\RequestSignatory::where('request_id','=',$request->id)->get();
    endif;
    $data = [
      'request' => $request, 
      'office' => $office,
      'sector' => $sector,
      'signatory' => $signatory,
      'issuedby' => $issuedby,
      'row_count' => $row_count,
      'pages' => $data_count,
      'end' => $remaining_rows
    ];

    $filename = "Request-".Carbon\Carbon::now()->format('mdYHm')."-"."$request->code".".pdf";
    $view = "requests.client.reports.print_ris";
    return $this->printPreview($view,$data,$filename,$orientation);
  }

  public function approveRIS(Request $request, $id) 
  {
    $quantity = $request->get('quantity');
    $comment = $request->get('comment');
    $stocknumbers = $request->get('stocknumber');
    $requested = $request->get('requested');
    $array = [];
    $remarks = $request->get('remarks');
    $action = $request->get('action');
    $issued_by = Auth::user()->id;

    try {

      DB::beginTransaction();

      $updateRequestDetails = new RequestDetailsClient;
      $updateRequest = new RequestCustodian;

      $validator = Validator::make([
        'Remarks' => $remarks
      ], $updateRequest->approveRules(), $updateRequest->approveMessages());

      if($validator->fails()) {
        return redirect("request/custodian/$id/approve")
          ->with('total',count($stocknumbers))
          ->with('stocknumber',$stocknumbers)
          ->with('quantity',$quantity)
          ->with('comment', $comment)
          ->withInput()
          ->withErrors($validator);
      }

      foreach($stocknumbers as $stocknumber) {
        $validator = Validator::make([
          'Stock Number' => $stocknumber,
          'Quantity' => $quantity["$stocknumber"]
        ],$updateRequestDetails->approveRules(), $updateRequestDetails->approveMessages());

        if($validator->fails()) {
          return redirect("request/custodian/$id/approve")
            ->with('total',count($stocknumbers))
            ->with('stocknumber',$stocknumbers)
            ->with('quantity',$quantity)
            ->withInput()
            ->withErrors($validator);
        }
      }
      //return $quantity;
      foreach($stocknumbers as $stocknumber) {
        $supply = App\Supply::findByStockNumber($stocknumber);
        $array [ $supply->id ] = [
          'quantity_issued' => $quantity[$stocknumber],
          'comments' => $comment[$stocknumber]
        ];
      }

      $updateRequest = RequestCustodian::find($id);
      $updateRequest->supplies()->sync($array);
      $updateRequest->remarks = $remarks;
      $updateRequest->issued_by = $issued_by;
      $updateRequest->status = 'approved'; 
      $updateRequest->approved_at = Carbon\Carbon::now();
      $updateRequest->save();

      DB::commit();
      \Alert::success('Request Approved!')->flash();
      return redirect('request/custodian?type=pending');
    } catch(\Exception $e) {
      DB::rollback();
      \Alert::error('An error occured! Please try again. Message: '.$e->getMessage())->flash();
      return redirect("request/custodian/$id/approve"); 
    }
  }
}