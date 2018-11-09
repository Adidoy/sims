<?php

namespace App\Http\Controllers\maintenance\Category;

use Illuminate\Http\Request;
use App\Models\Supply\Supply;
use App\Models\Supply\Category;
use App\Http\Controllers\Controller;
use App\Commands\Category\AssignCategory;

class AssignmentController extends Controller
{
    /**
     * Display the form
     *
     * @param int $id
     * @return Response
     */
	public function show($id)
	{
		$category = Category::find($id);
		$supply = Supply::categoryName($category->name)->get();
		return view('maintenance.category.assign', compact('category', 'supply'));
	}

    /**
     * Process the data from the form
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
	public function store(Request $request, $id)
	{
		$this->dispatch(new AssignCategory($request, $id));
		return redirect("maintenance/category/assign/$id");

	}
}
