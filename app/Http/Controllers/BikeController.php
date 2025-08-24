<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Bike;
use App\Models\RealCtegorie;

class BikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $bikes = Bike::all();
         return view('Bike.index', compact('bikes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { 
        $realCtegorie = RealCtegorie::all();
         return view('Bike.create', compact('realCtegorie'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     
        $request->validate([
            'name' => 'required',
        ]);

        Bike::create([
            'categorie_id' => $request->categorie_id, // assuming "categorie_id" maps to "brand_id"
            'name' => $request->name,
        ]);
        return redirect()->route('bikes.index');
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
        
        $category = Bike::findOrFail($id);
        $category->delete();
        
        return redirect()->route('bikes.index');
    }
}
