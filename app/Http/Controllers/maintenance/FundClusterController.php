<?php

namespace App\Http\Controllers;

use App\Models\FundCluster;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Commands\FundCluster\CreateFundCluster;
use App\Http\Requests\FundClusterRequest\FundClusterStoreRequest;
use App\Http\Requests\FundClusterRequest\FundClusterUpdateRequest;

class FundClusterController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $fundclusters = FundCluster::all();
			return datatables()->toJson();
        }
        
		return view('fundcluster.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fundcluster.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $this->dispatch(new CreateFundCluster($request, $id));
        return redirect('fundcluster');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FundClusterStoreRequest $request, $id)
    {
        $id = $this->sanitizeString($id);
        $fundcluster = App\FundCluster::find($id);

		if($request->ajax())
		{
            $purchaseorders = App\PurchaseOrder::with('supplier')->whereHas('fundclusters', function($query) use($id){
                $query->where('id', '=', $id);
            })->get();
			return datatables($purchaseorders)->toJson();
		}

		return view('fundcluster.show')
				->with('fundcluster',$fundcluster);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $fundcluster = FundCluster::findOrFail($id);
        return view('fundcluster.edit', compact('fundcluster'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(FundClusterUpdateRequest $request, $id)
    {
        $this->dispatch(new UpdateFundCluster($request, $id));
        return view('fundcluster.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->dispatch(new RemoveFundCluster($request, $id));
        return redirect('fundcluster');

    }
}
