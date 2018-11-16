<?php

namespace App\Http\Controllers\Account;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Commands\User\CreateUser;
use App\Commands\User\UpdateUser;
use App\Commands\User\RemoveUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest\UserStoreRequest;
use App\Http\Requests\UserRequest\UserUpdateRequest;

class AccountsController extends Controller 
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{

		if($request->ajax()) {
			$users = User::all();
			return datatables($users)->toJson();
		}

		return view('maintenance.account.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() 
	{
		return view('maintenance.account.create')
			->with('title','Accounts')
			->with('office',App\Office::orderBy('name')->pluck('name','code'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(UserStoreRequest $request)
	{
		$this->dispatch(new CreateUser($request));
		return redirect('account');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {
		$person = User::findOrFail($id);
		return view('maintenance.account.show', compact('person'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id) 
	{
		$user = User::findOrFail($id);
		$office = Office::orderBy('name')->pluck('name', 'code');
		return view('account.edit', compact('office', 'user'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(UserUpdateRequest $request, $id)	
	{
		$this->dispatch(new UpdateUser($request, $id));
		return redirect('account');
	}

 	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request,$id) 
	{
		$this->dispatch(new RemoveUser($request, $id));
		return redirect('account');
	}
}
