<?php

namespace App\Http\Controllers\Maintenance\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Commands\Category\CreateCategory;
use App\Commands\Category\UpdateCategory;
use App\Http\Requests\CategoryRequest\CategoryStoreRequest;
use App\Http\Requests\CategoryRequest\CategoryUpdateRequest;

class CategoryController extends Controller
{

	/**
	 * Constructor class for the class
	 * Initialize the title of the current
	 * page
	 */
	public function __construct()
	{
		View::share('title', 'Category');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax()) {
			$categories = Category::all();
			return datatables($categories)->toJson();
		}

		return view('maintenance.category.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('maintenance.category.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CategoryStoreRequest $request)
	{
		$this->dispatch(new CreateCategory($request));
		return redirect('maintenance/category');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id = null)
	{
		if($request->ajax())
		{
			if($request->has('term')) {
				return Category::codeLike($request->term)->assignTo('data')->json();
			}

			return Category::code($id)->assignTo('data')->json();
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
		$category = Category::findOrFail($id);
		return view('maintenance.category.edit', compact('category'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(CategoryUpdateRequest $request, $id)
	{
		$this->dispatch(new UpdateCategory($request, $id));
		return redirect('maintenance/category');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$this->dispatch(new RemoveCategory($request, $id));
		return redirect('maintenance/category');
	}
}
