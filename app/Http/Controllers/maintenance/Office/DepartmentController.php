<?php

namespace App\Http\Controllers\Maintenance\Office;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Commands\Office\RemoveOffice;
use App\Models\Office\Office as Department;
use App\Commands\Department\CreateDepartment;
use App\Commands\Department\UpdateDepartment;
use App\Http\Requests\DepartmentRequest\DepartmentStoreRequest;
use App\Http\Requests\DepartmentRequest\DepartmentUpdateRequest;

class DepartmentController extends Controller 
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function index(Request $request)
	{
		if($request->ajax())
		{
			return datatables(App\Department::All())->toJson();
		}
		return view('maintenance.department.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$department = Department::where('code','NOT LIKE','%-A%')->orderBy('name')->pluck('name','id');
		return view('maintenance.department.create', compact('department'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(DepartmentStoreRequest $request)
	{
		$this->dispatch(new CreateDepartment($request));
		return redirect('maintenance/office/' . $office);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id = null)
	{
		$department = Department::find($id);
		return view('maintenance.department.show', compact('department'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$department = Department::findOrFail($id);
		$departments = Department::where('code', 'NOT LIKE', '%-A%')->orderBy('name')->pluck('name', 'id');

		return view('maintenance.department.edit', compact('department', 'departments'));
	}

	/**
		 * Update the specified resource in storage.
		 *
		 * @param  int  $id
		 * @return Response
	 */
	public function update(DepartmentUpdateRequest $request, $id)
	{
		$this->dispatch(new UpdateDepartment($request, $id));
		return redirect('maintenance/office/' . $id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$this->dispatch(new RemoveOffice($request, $id));
		return redirect('maintenance/office');
	}

}
