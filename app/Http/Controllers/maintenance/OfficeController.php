<?php

namespace App\Http\Controllers\Maintenance;

use App;
use Carbon;
use Session;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
class OfficeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
	  $office = App\Office::All();
      /*
      $issuedby = App\User::where('id','=',$request->issued_by)->first();*/
		if($request->ajax())
		{
			return datatables($office)->toJson();
		}

		return view('maintenance.office.index')
					->with('title','Office');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('maintenance.office.create')
					->with('title','Office');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		$name = Input::get('name');
		$code = Input::get('code');
		$head = Input::get('head');
		$head_title = Input::get('head_title');
		$description = Input::get('description');

		$office = new App\Office;

		$validator = Validator::make([
			'Name' => $name,
			'Code' => $code
		],$office->rules());

		if($validator->fails())
		{
			return redirect('maintenance/office/create')
				->withInput()
				->withErrors($validator);
		}
		$office->code = $code;
		$office->name = $name;
		$office->description = $description;
		$office->head = $head;
		$office->head_title = $head_title;
		$office->save();

		\Alert::success('Office added')->flash();
		return redirect('maintenance/office');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id = null, $code = null)
	{
		$office = App\Office::find($id);
		if($request->ajax())
		{
			if(Input::has('term'))
			{
				$code = Input::get('term');
				$offices = App\Office::where('code','like',$code.'%')->pluck('code')->toArray();
				return json_encode( App\Office::where('code','like',$code.'%')->pluck('code')->toArray());
			}

			if($office->count() > 0 )
			{
				return datatables($office->departments)->toJson();
			}

			return json_encode([
				'data' => App\Office::findByCode($id)
			]);
		}

		if($office->count() <= 0 )
		{
			 return view('errors.404');
		}

		return view('maintenance.office.show')
				->with('title', "$office->code")
				->with('office', $office);
	}


	public function showOfficeCodes(Request $request, $id = null)
	{
		if($request->ajax())
		{
			if($request->has('term'))
			{
				$code = $this->sanitizeString($request->get('term'));
				$office = App\Office::where('code','like',$code.'%')->pluck('code');
				return json_encode($office);
			}
		}
	}

	public function showOfficeDetails(Request $request, $code)
	{
		$office = App\Office::findByCode($code);
		return json_encode($office);
		if($request->ajax())
		{
			if($request->has('term'))
			{
				//$code = $this->sanitizeString($request->get('term'));
				$office = App\Office::findByCode($code);
				return json_encode($office);
			}
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$office = App\Office::find($id);

		if(count($office) <= 0)
		{
			return view('errors.404');
		}
		return view("maintenance.office.edit")
				->with('office',$office)
				->with('title','Office');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$name = Input::get('name');
		$code = Input::get('code');
		$head = Input::get('head');
		$head_title = Input::get('head_title');
		$description = Input::get('description');

		$office = App\Office::find($id);

		$validator = Validator::make([
			'Name' => $name,
			'Code' => $code
		],$office->updateRules());

		if($validator->fails())
		{
			return redirect("maintenance/office/$id/edit")
				->withInput()
				->withErrors($validator);
		}
		$office->code = $code;
		$office->name = $name;
		$office->description = $description;
		$office->head = $head;
		$office->head_title = $head_title;
		$office->save();

		\Alert::success('Office Information Updated')->flash();
		return redirect('maintenance/office');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		if($request->ajax())
		{
			$office = App\Office::find($id);
			$office->delete();
			return json_encode('success');
		}

		try
		{
			$office = App\Office::find($id);
			$office->delete();
			\Alert::success('Office Removed')->flash();
		} catch (Exception $e) {
			\Alert::error('Problem Encountered While Processing Your Data')->flash();
		}
		return redirect('maintenance/office');
	}

	public function getAllCodes()
	{
		if($request->ajax())
		{
			return json_encode( App\Office::pluck('code')->toArray());
		}
	}


}
