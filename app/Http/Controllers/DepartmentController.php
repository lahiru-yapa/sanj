<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Department;
use App\Models\RealCtegorie;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function index()
    {
        $department = Department::get();
         return view('Department.index', compact('department'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { 
        $department = Category::all();
         return view('Department.create', compact('department'));
    }
// ..
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        Department::create([
            'name' => $request->name,
            'category_id'=>$request->department_id,
        ]);
         $department = Department::all();

      
         return view('Department.index', compact('department'));
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
        
        $category = Department::findOrFail($id);
        $category->delete();
        
        $department = Department::all();
         return view('Department.index', compact('department'));
    }
}
