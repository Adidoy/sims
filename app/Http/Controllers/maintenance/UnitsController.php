<?php

namespace App\Http\Controllers\Maintenance;

use App;
use Session;
use Validator;
use Illuminate\Http\Request;

class UnitsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $units = App\Unit::all();
            return datatables($units)->toJson();
        }

        return view('maintenance.unit.index')
                ->with('title','Unit');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('maintenance.unit.create')
                ->with('title','Unit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $name = $request->get('name');
        $description = $request->get("description");
        $abbreviation = $request->get("abbreviation");

        $validator = Validator::make([
            'Name' => $name,
            'Description' => $description,
            'Abbreviation' => $abbreviation
        ],App\Unit::$rules);

        if($validator->fails())
        {
            return redirect()->back()
                    ->withInput()
                    ->withErrors($validator);
        }

        $unit = new App\Unit;
        $unit->name = $name;
        $unit->description = $description;
        $unit->abbreviation = $abbreviation;
        $unit->save();

        \Alert::success('Unit Created')->flash();
        return redirect('maintenance/unit');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $unit = App\Unit::find($id);

        if( count($unit) <= 0 )
        {
            \Alert::error('Invalid Unit Information')->flash();
            return redirect('maintenance/unit');
        }

        return view('maintenance.unit.edit')
                ->with('title','Unit')
                ->with('unit',$unit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = $id; 
        $name = $request->get('name');
        $description = $request->get("description");
        $abbreviation = $request->get("abbreviation");

        $unit = App\Unit::find($id);

        $validator = Validator::make([
            'Name' => $name,
            'Description' => $description,
            'Abbreviation' => $abbreviation
        ],$unit->updateRules());

        if($validator->fails())
        {
            return redirect()->back()
                    ->withInput()
                    ->withErrors($validator);
        }
        $unit->name = $name;
        $unit->description = $description;
        $unit->abbreviation = $abbreviation;
        $unit->save();

        \Alert::success('Unit Information Updated')->flash();
        return redirect('maintenance/unit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        if($request->ajax())
        {
            $unit = App\Unit::find($id);

            if(count($unit) <= 0)
            {
                return json_encode('error');
            }

            $unit->delete();
            return json_encode('success');
        }

        $unit = App\Unit::find($id);

        if(count($unit) <= 0)
        {
            return json_encode('error');
        }

        $unit->delete();

        \Alert::success('Unit Removed')->flash();
        return redirect('maintenance/unit');
    }
}
