<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Category;

class BrandController extends Controller
{
      // Show list of brands
    public function index()
    {
       $brands = Category::where('delete_flag', 0)->get();

        return view('brands.index', compact('brands'));
    }

    // Show form to add a brand
    public function create()
    {
        return view('brands.create');
    }

public function store(Request $request)
{

    // Validate the input
    $request->validate([
        'name' => 'required|unique:categories,name|max:255',
        'description' => 'nullable|string',
    ]);

    // Generate category code
    $categoryCode = 'CAT-' . strtoupper(Str::random(6)); // Example: CAT-X7J9K2
 
    // Create the category
    Category::create([
        'name' => $request->name,
        'sku' => $categoryCode, 
        'description' => $request->description,
        'delete_flag' => 0, // Default to active (1) if not provided
    ]);

    return redirect()->route('categories.index')->with('success', 'Category added successfully!');
}

    // Show edit form
    public function edit($id)
    {
        $brand = Category::findOrFail($id);
        return view('brands.edit', compact('brand'));
    }

    // Update brand
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255|unique:brands,name,' . $id,
        ]);

        $brand = Category::findOrFail($id);
        $brand->update([
            'name' => $request->name,
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully!');
    }

    // Delete brand
    public function destroy($id)
    {
      
      $brand = Category::findOrFail($id);
    $brand->delete_flag = 1;
    $brand->save();
        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully!');
    }
}
