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
use App\Models\Requests\Custodian\RequestSignatory;
use App\Models\Requests\Custodian\RequestCustodian;
use App\Models\Requests\Client\RequestDetailsClient;
use App\Http\Controllers\Inventory\Stockcards\StockCardController;

class RequestsCustodianController extends Controller
{
  public function index(Request $request) 
  {
    if(isset($request->type)) {
      if($request->type == 'pending') {
          $requests = RequestCustodian::whereNull('status')
            ->orderBy('created_at', 'desc')
            ->get();
      } elseif ($request->type == 'approved') {
          $requests = RequestCustodian::where('status','=','approved')
            ->orderBy('approved_at', 'desc')
            ->get();
      } elseif ($request->type == 'released') {
          $requests = RequestCustodian::where('status','=','released')
            ->orderBy('released_at', 'desc')
            ->get();
      } elseif ($request->type == 'disapproved') {
          $requests = RequestCustodian::where(function($query) {
                  $query->where('status','=','cancelled')
                          ->orWhere('status','=','disapproved');
              })
              ->orderBy('updated_at', 'desc')
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

        $supply = App\Supply::findByStockNumber($stocknumber);
        if($requested["$stocknumber"] < $quantity["$stocknumber"]) {
          return back()
            ->withInput()
            ->withErrors(['Quantity to be issued MUST NOT BE GREATER THAN quantity requested.']);
        }

        if($supply->temp_balance < $quantity["$stocknumber"]) {
          return back()
            ->withInput()
            ->withErrors(['Remaining balance is less than the quantity issued.']);
        }
      }

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

  public function releaseRIS(Request $request, $id) 
  {
    // $remarks = $request->get('remarks');
    // $date = Carbon\Carbon::now();
    
    // $releaseRequest = new RequestCustodian;
    // $validator = Validator::make([
    //   'Remarks' => $remarks,
    // ],$releaseRequest->releaseRules(), $releaseRequest->releaseMessages());

    // if($validator->fails()) {
    //   return redirect("request/custodian/$id/release")
    //     ->withInput()
    //     ->withErrors($validator);
    // }

    //try {
      $updateRequest = RequestCustodian::find($id);
      // return $updateRequest->office->id;
      // DB::beginTransaction();
      // if( count($updateRequest) <= 0 || !in_array($updateRequest->status, [ 'Approved', 'approved']) || Auth::user()->access != 1 && Auth::user()->access != 6) {
      //   return view('errors.404');
      // }
      $office = App\Office::where('id','=',$updateRequest->office->id)->first();
      // return $office->head_office;
      if(!isset($office->head_office)) {
        $sector = $office;
        $office = App\Office::where('code','=',$office->code.'-A'.$office->code)->first();
      } else {
        $headOffice = App\Office::where('id','=',$office->head_office)->first();
        while(isset($headOffice->head_office)) {
          $office = $headOffice;
          $headOffice = App\Office::where('id','=',$headOffice->head_office)->first();
        }
      }

      //return $office;
      // $updateRequest->status = 'released';
      // $updateRequest->remarks = 'Received by: '.$remarks;
      // $updateRequest->released_at = $date;
      // $updateRequest->released_by = Auth::user()->id;
      // $updateRequest->save();

      // $sector = $headOffice;
      // $signatory = new RequestSignatory;
      // $signatory->request_id = $updateRequest->id;
      // $signatory->requestor_name = isset($office->name) ? $office->head != "None" ?$office->head : "" : "";
      // $signatory->requestor_designation = isset($office->name) ? $office->head_title != "None" ? $office->head_title : "" : "";
      // $signatory->approver_name = isset($sector->name) ? $sector->head : $updateRequest->office->head;
      // $signatory->approver_designation = isset($sector->head) ? $sector->head_title : $updateRequest->office->head_title;
      // $signatory->save();
      // DB::commit();

      // $stockCardController = new StockCardController;
      // $stockCardController->releaseSupplies($request, $id);
      // $quantity = $request->get('quantity');
      // $stocknumber = $request->get('stocknumber');
      // foreach($stocknumber as $stocknumber) {
      //   $_quantity = $quantity["$stocknumber"];
			// 	$supply = App\Supply::findByStockNumber($stocknumber);
      //   $updateRequest->supplies()->updateExistingPivot($supply->id, [
      //     'quantity_released' => $_quantity
      //   ]);
			// }

      \Alert::success('Items for RIS No.:'.$updateRequest->local.' are now released.')->flash();
      return redirect('request/custodian?type=approved');
    // } catch(\Exception $e) {
    //   DB::rollback();
    //   \Alert::error('An error occured! Please try again. Message: '.$e->getMessage())->flash();
    //   return redirect("request/custodian/$id/release"); 
    // }  
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
}