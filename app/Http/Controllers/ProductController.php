<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\RealCtegorie;
use App\Models\Bike;
use App\Models\RackDetail;
use App\Models\Department;
use App\Models\GRNItem;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;

use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function allproducts()
    {
        $products = Product::with('category')->where('delete_flag', 0)->get();
        return view('Products.allProducts', compact('products'));
    }

    public function stock(Request $request)
    {
        $grnItem = GRNItem::get();
        $category = Category::get();
        $warehouse = Warehouse::get();
        return view('Products.stock', compact('grnItem','warehouse','category'));
    }

public function lowStock(Request $request)
{
   
     $suppliers = Supplier::all();
     $query = ProductWarehouse::with(['product.grnItems.grn.supplier', 'warehouse'])
        ->where('stock', '<', 2);

    if ($request->filled('supplier_id')) {
        $query->whereHas('product.grnItems.grn', function ($q) use ($request) {
            $q->where('supplier_id', $request->supplier_id);
        });
    }

    $lowStockItems = $query->get();
    return view('Products.lowstock', compact('lowStockItems', 'suppliers'));
}

public function lowStockAjax(Request $request)
{
   
    $query = ProductWarehouse::with(['product.grnItems.grn.supplier', 'warehouse'])
        ->where('stock', '<', 2);

    if ($request->filled('supplier_id')) {
        $query->whereHas('product.grnItems.grn', function ($q) use ($request) {
            $q->where('supplier_id', $request->supplier_id);
        });
    }

    $items = $query->get()->map(function ($item) {
        return [
            'product' => [
                'name' => $item->product->name ?? null,
                'sku' => $item->product->sku ?? null,
                'grn_supplier' => optional($item->product->grnItems->first()?->grn->supplier)->name ?? null,
            ],
            'warehouse' => [
                'name' => $item->warehouse->name ?? null
            ],
            'stock' => $item->stock,
        ];
    });

    return response()->json($items);
}



public function getFilteredProducts(Request $request)
{
    $brandId = $request->input('brand');
    $code = $request->input('code');
    $warehouseId = $request->input('warehouse_id');

    $products = DB::table('products')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->when(!empty($brandId), function ($query) use ($brandId) {
            return $query->where('products.category_id', $brandId);
        })
        ->when(!empty($code), function ($query) use ($code) {
            return $query->where('products.sku', 'LIKE', "%$code%");
        })->select(
            'products.id',
            'products.name',
            'products.sku',
            'categories.name as category_name'
        )
        ->get();

    return response()->json($products);
}

public function getFilteredProducts2(Request $request)
{
    $brandId = $request->input('brand');
    $code = $request->input('code');
    $warehouseId = $request->input('warehouse_id');

    $products = DB::table('product_warehouse')
        ->join('products', 'product_warehouse.product_id', '=', 'products.id')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->when(!empty($brandId), function ($query) use ($brandId) {
            return $query->where('products.category_id', $brandId);
        })
        ->when(!empty($code), function ($query) use ($code) {
            return $query->where('products.sku', 'LIKE', "%$code%");
        })
        ->when(!empty($warehouseId), function ($query) use ($warehouseId) {
            return $query->where('product_warehouse.warehouse_id', $warehouseId);
        })
        ->select(
            'products.id',
            'products.name',
            'products.sku',
            'categories.name as category_name',
            'product_warehouse.stock as available_stock'
        )
        ->get();

    return response()->json($products);
}


    public function addproduct (Request $request)
    {
        $subDepartment = Department::get();
        $ctegories = RealCtegorie::get();
        $bikes = Bike::get();
        $rackDetail = RackDetail::get();
        $brands = Category::where('delete_flag', 0)->get();
        $supllier = Supplier::where('delete_flag', 0)->get();

        return view('Products.addProducts', compact('supllier','brands','ctegories','bikes','subDepartment','rackDetail'));
    }

    public function view($id)
    {
       
        $product = Product::with('category')->findOrFail($id);

        $supplier = Supplier::findOrFail($product->supplier_id);
 
        return view('Products.viewProductsr', compact('product','supplier'));
    }

    public function edit($id)
    {
       
        $product = Product::findOrFail($id);
    
        // $department = $product->category;
        // dd($department);
          $ctegories = RealCtegorie::get();
          $departments = Department::get();
         $bikes = Bike::get();
        $brands = Category::where('delete_flag',0)->get();
        $rackDetail = RackDetail::get();
        // $supplier = Supplier::findOrFail($product->supplier_id);
        // $allSuplliers = Supplier::where('delete_flag', 0)->get();
        
        return view('Products.editProducts', compact('product','brands','ctegories','bikes','departments','rackDetail'));
    }
    
    public function store(Request $request)
    {
            // Validation
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'department' => 'required|integer',
                'subdepartment' => 'required|integer',
                'rack_name' => 'required',
                'category' => 'required|integer',
                'subCategory' => 'required|integer',
                'low_stock' => 'required|integer',
                
            ]);

         // Handle file upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('product_photos', 'public');
            }
            $code = $request->input('code');
            if (!empty($code)) {
                $sku = $code;
            } else {
                $sku = 'PRD-' . strtoupper(substr($request->input('description'), 0, 3)) . '-' . date('YmdHis');
            }
         
                // Store in the database
                Product::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'sku' => $request->code,
                    'description' => $request->department,
                    'photo' => $photoPath,
                    'category_id' => $request->department,
                    'department_id' => $request->subdepartment,
                    'real_category_id' => $request->category,
                    'bikes_id' => $request->subCategory,
                    'low_stock' => $request->low_stock,
                    'rack_name'=> $request->rack_name,
                ]);
          
                return redirect()->route('allproduct')->with('success', 'Shop created successfully!');

            
    }

    public function editProduct(Request $request)
    {

        // Validation
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'department' => 'required|integer',
                'subdepartment' => 'required|integer',
                'rack_name' => 'required',
                'category' => 'required|integer',
                'subCategory' => 'required|integer',
                'low_stock' => 'required|integer',
                 ]);
            dd("fr");
        $product = Product::find($request->Product_id); // Replace `user_id` with the actual field you're using
        //  // Handle file upload
         $photoPath = null;
         if ($request->hasFile('photo')) {
             $photoPath = $request->file('photo')->store('product_photos', 'public');
         }
         
         
        // Update the user details
        if ($product) {
            $product->update([
                    'name'=> $request->name,
                    'sku' => 'PRD-' . strtoupper(substr($request->description, 0, 3)) . '-' . date('YmdHis'),
                    // 'price' => $request->price,
                    // 'stock' => $request->stock,
                    'category_id' => $request->category_id,
                    'photo' => $photoPath, 
                    // 'sell_price'=>$request->sell_price,
                    'description'=>$request->description,
                    'bike'=>$request->bike,
                     'real_category'=>$request->real_category,
            ]);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
      
        return redirect()->route('allproduct')->with('success', 'Shop created successfully!');
    }

     
    public function delete($id)
    {
        try {
            // Find the user by ID
            $product = Product::findOrFail($id);
    
            // Set delete_flag to 1
            $product->delete_flag = 1;
            $product->save();
    
            // Redirect back with success message
            return redirect()->route('allproduct')->with('success', 'User flagged as deleted successfully!');
        } catch (\Exception $e) {
            // Handle exceptions (e.g., user not found)
            return redirect()->route('allproduct')->with('error', 'User could not be flagged as deleted.');
        }
    }

    
}