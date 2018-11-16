<?php

namespace App\Http\Controllers\Maintenance;

use Illuminate\Http\Request;
use App\Models\Supply\Supplier;
use App\Commands\Supplier\CreateSupplier;
use App\Commands\Supplier\UpdateSupplier;
use App\Commands\Supplier\RemoveSupplier;
use App\Http\Requests\SupplierRequest\SupplierStoreRequest;
use App\Http\Requests\SupplierRequest\SupplierUpdateRequest;

class SupplierController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $suppliers = Supplier::all();
            return datatables($suppliers)->toJson();
        }

        return view('maintenance.supplier.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('maintenance.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierStoreRequest $request)
    {
        $this->dispatch(new CreateSupplier($request));
        return redirect('maintenance/supplier');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('maintenance.supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierUpdateRequest $request, $id)
    {
        $this->dispatch(new UpdateSupplier($request, $id));
        return redirect('maintenance/supplier');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->dispatch(new RemoveSupplier($request, $id));
        return redirect('maintenance/supplier');
    }
}
