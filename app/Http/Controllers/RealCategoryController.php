<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\RealCtegorie;

class RealCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $realCtegorie = RealCtegorie::all();
        return view('realCategory.index', compact('realCtegorie'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('realCategory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
           $request->validate([
            'name' => 'required',
        ]);
        RealCtegorie::create($request->all());
        return redirect()->route('real-categories.index');

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
      
        $category = RealCtegorie::findOrFail($id);
        $category->delete();
        
        return redirect()->route('real-categories.index');
    }
}
