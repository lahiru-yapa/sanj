<?php

namespace App\Http\Controllers;
use App\Models\RackDetail;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class RackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rack = RackDetail::get();
        return view('Rack.index', compact('rack'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouse = Warehouse::get();
        return view('Rack.create', compact('warehouse'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        RackDetail::create([
        'rack_name' => $request->name,
        'rack_code'=>$request->rackCode,
        'warehouse_id'=>$request->warehouse_id,
        'row_number'=>$request->row_number,
        'column_number'=>$request->column_number,
           ]);
          
           return redirect()->route('rack.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
     
        $rack = RackDetail::find($id);

        $rack->delete();
        $rack = RackDetail::get();
        return redirect()->route('rack.index');
      
    }
}
