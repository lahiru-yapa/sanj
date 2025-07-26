<?php

namespace App\Http\Controllers;

use App\Models\GRN;
use App\Models\GRNItem;
use App\Models\Warehouse;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductWarehouse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class GRNController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
        $grns = GRN::with(['items', 'warehouse', 'supplier'])
        ->where('delete_flag', 0)
        ->get();
        
        $supllier = Supplier::where('delete_flag', 0)->get();
        return view('grn.index', compact('grns','supllier'));
    }
    
    // updateall
    public function updateall(Request $request)
    {
    $validated = $request->validate([
        'grn_id' => 'required',
        'warehouse_id' => 'required|exists:warehouses,id',
        'supplier_id' => 'required|exists:suppliers,id',
        'received_date' => 'required|date',
        'grn_number' => 'required',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.purchase_price' => 'required|numeric|min:0',
        'items.*.supplier_discount' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {
        // Check for duplicate GRN number (excluding current GRN)
        $existingGRN = GRN::where('grn_number', $validated['grn_number'])
            ->where('id', '!=', $validated['grn_id'])
            ->exists();

        if ($existingGRN) {
            return redirect()->back()->with('error', 'GRN number already exists.');
        }

        // Update the GRN
        $grn = GRN::findOrFail($validated['grn_id']);
        $grn->grn_number = $validated['grn_number'];
        $grn->warehouse_id = $validated['warehouse_id'];
        $grn->supplier_id = $validated['supplier_id'];
        $grn->received_date = $validated['received_date'];
        $grn->save();

        // Save or update GRN items
        foreach ($request['items'] as $item) {
          
            GRNItem::updateOrCreate(
                [
                    'grn_id' => $grn->id,
                    'product_id' => $item['product_id'],
                ],
                [
                    'brand' => $item['brand'] ?? null,
                    'code' => $item['code'] ?? null,
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'unit_price' => $item['set_price'] ?? 0,
                    'total_price' => ($item['set_price'] ?? 0) * $item['quantity'],
                    'set_price' => $item['set_price'] ?? 0,
                    'retail_sell_dis' => $item['retail_sell_dis'] ?? 0,
                    'wholesale_discount' => $item['wholesale_discount'] ?? 0,
                    'retail_price' => $item['retail_price'] ?? 0,
                    'warranty_period' => $item['warranty_period'] ?? null,
                    'rack_id' => $item['rack_id'] ?? null,
                    'retail_sell_discount' => $item['retail_sell_discount'] ?? null,
                    'supplier_discount' => $item['supplier_discount'] ?? null,
                    'wholesale_price' => $item['whole_sell_price'] ?? null,
                ]
            );
        }

        DB::commit();
      return redirect()->route('grns.index')->with('success', 'GRN saved successfully!');


    } catch (\Exception $e) {
        dd($e);
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to save GRN. ' . $e->getMessage());
    }
}
    //store all grn 
public function storeall(Request $request)
{
    $request->validate([
        'warehouse_id' => 'required|exists:warehouses,id',
        'supplier_id' => 'required|exists:suppliers,id',
        'received_date' => 'required|date',
        'grn_number' => 'required',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.purches' => 'required|numeric|min:0',
        'items.*.suplier_discount' => 'required|numeric|min:0',
    ]);
       // Calculate total price (before discount)

    // Create or find GRN record
    $grn = GRN::firstOrCreate(
        ['grn_number' => $request->grn_number], // Search condition
        [
            'warehouse_id' => $request->warehouse_id,
            'supplier_id' => $request->supplier_id,
            'received_date' => $request->received_date,
            'remarks' => $request->remarks,
        ]
    );

    // Save GRN Items
    foreach ($request->items as $item) {
      
        GRNItem::firstOrCreate(
            [
                'grn_id' => $grn->id,
                'product_id' => $item['product_id'],
            ], // Search condition
            [
                'brand' => $item['brand'] ?? null,
                'code' => $item['code'] ?? null,
                'quantity' => $item['quantity'],
                'purches' => $item['purches'],
                'set_price' => $item['set_price'] ?? 0,
                'unit_price'=> $item['set_price'] ?? 0,
                'purchase_price'=>$item['purches'] ?? 0,
                'total_price' => $item['set_price'] ?? 0,
                'retail_sell_dis' => $item['retail_sell_dis'] ?? 0,
                'whole_sell_dis' => $item['whole_sell_dis'] ?? 0,
                'retail_price' => $item['retail_price'] ?? 0,
                'warranty_period' => $item['warranty_period'] ?? null,
                'rack_id' => $item['rack'] ?? null,
                'retail_sell_discount'=>$item['retail_sell_dis'] ?? null,
                'supplier_discount'=>$item['suplier_discount'] ?? null,
                'wholesale_discount'=>$item['whole_sell_dis'] ?? null,
                'wholesale_price'=>$item['whole_sell_Price'] ?? null,
            ]
        );
    }

    return redirect()->route('grns.index')->with('success', 'GRN saved successfully!');
}
public function storeItem(Request $request)
{
    try {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'received_date' => 'required|date',
            'grn_number' => 'required|string',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'purches' => 'required|numeric|min:0',
            'suplier_discount' => 'required|numeric|min:0',
            'set_price' => 'required|numeric|min:0',
            'retail_sell_dis' => 'required|numeric|min:0',
            'whole_sell_dis' => 'required|numeric|min:0',
            'whole_sell_Price' => 'required|numeric|min:0',
            'retail_price' => 'required|numeric|min:0',
            'warranty_period' => 'nullable|string',
            'rack' => 'nullable|string',
        ]);

        DB::beginTransaction();

        // Find or create the GRN record
        $grn = Grn::firstOrCreate(
            [
                'grn_number' => $validated['grn_number']
            ],
            [
                'warehouse_id' => $validated['warehouse_id'],
                'supplier_id' => $validated['supplier_id'],
                'received_date' => $validated['received_date']
            ]
        );

        // Calculate total price (before discount)
        $unit_price = $validated['purches'];
        $total_price = $validated['quantity'] * $unit_price;

        // Create a new GRN item
        $grnItem = new GrnItem();
        $grnItem->grn_id = $grn->id;
        $grnItem->product_id = $validated['product_id'];
        $grnItem->quantity = $validated['quantity'];
        $grnItem->unit_price = $unit_price;
        $grnItem->total_price = $total_price;
        $grnItem->purchase_price = $validated['purches'];
        $grnItem->supplier_discount = $validated['suplier_discount'];
        $grnItem->set_price = $validated['set_price'];
        $grnItem->retail_sell_discount = $validated['retail_sell_dis'];
        $grnItem->wholesale_discount = $validated['whole_sell_dis'];
        $grnItem->wholesale_price = $validated['whole_sell_Price'];
        $grnItem->retail_price = $validated['retail_price'];
        $grnItem->warranty_period = $validated['warranty_period'];
        $grnItem->rack_id = $validated['rack'];
        $grnItem->save();

        // ✅ Update or insert stock into product_warehouse
        ProductWarehouse::updateOrInsert(
            [
                'product_id' => $validated['product_id'],
                'warehouse_id' => $validated['warehouse_id'],
                'grn_item_id' => $grnItem->id
            ],
            [
                'stock' => DB::raw("stock + {$validated['quantity']}")
            ]
        );

        DB::commit();
        return response()->json(['success' => true, 'message' => 'Item stored successfully'], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Handle validation errors
        $errors = $e->validator->errors();
        $message = $errors->first() . " (and " . ($errors->count() - 1) . " more errors)";
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error storing GRN item: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to store item, please try again later'
        ], 500);
    }
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Category::where('delete_flag', 0)->get();
        $warehouses = Warehouse::all();
        $supllier = Supplier::where('delete_flag', 0)->get();
        $products = Product::with('category')->where('delete_flag', 0)->get();
        return view('grn.create', compact('warehouses','supllier','products','brands'));
    }

 public function createItem(Request $request)
{  
   
    // ✅ Validation
    $validated = $request->validate([
        'grn_id' => 'required', // You missed this!
        'warehouse_id' => 'required|exists:warehouses,id',
        'grn_number' => 'required|string',
        'received_date' => 'required|date',
        'supplier_id' => 'required|exists:suppliers,id',
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'purches' => 'required|numeric',
        'suplier_discount' => 'required',
        'set_price' => 'required',
        'retail_sell_dis' => 'required',
        'whole_sell_dis' => 'required',
        'whole_sell_Price' => 'required',
        'retail_price' => 'required',
        'warranty_period' => 'required',
        'rack' => 'required',
    ]);
    try {
        DB::beginTransaction();

        // ✅ Check for GRN number duplication (excluding current GRN)
        $existingGRN = GRN::where('grn_number', $validated['grn_number'])
            ->where('id', '!=', $validated['grn_id'])
            ->exists();

        if ($existingGRN) {
            return redirect()->back()->with('error', 'GRN number already exists.');
        }

        // ✅ Update GRN
        $grn = GRN::findOrFail($validated['grn_id']);
        $grn->grn_number = $validated['grn_number'];
        $grn->warehouse_id = $validated['warehouse_id'];
        $grn->supplier_id = $validated['supplier_id'];
        $grn->received_date = $validated['received_date'];
        $grn->save();

        // ✅ Update GRN Item
        $grnItem = GrnItem::where('grn_id', $validated['grn_id'])
            ->firstOrFail();

        $unit_price = $validated['purches'];
        $total_price = $validated['quantity'] * $unit_price;

        $grnItem->product_id = $validated['product_id'];
        $grnItem->quantity = $validated['quantity'];
        $grnItem->unit_price = $unit_price;
        $grnItem->total_price = $total_price;
        $grnItem->purchase_price = $validated['purches'];
        $grnItem->supplier_discount = $validated['suplier_discount'];
        $grnItem->set_price = $validated['set_price'];
        $grnItem->retail_sell_discount = $validated['retail_sell_dis'];
        $grnItem->wholesale_discount = $validated['whole_sell_dis'];
        $grnItem->wholesale_price = $validated['whole_sell_Price'];
        $grnItem->retail_price = $validated['retail_price'];
        $grnItem->warranty_period = $validated['warranty_period'];
        $grnItem->rack_id = $validated['rack'];
        $grnItem->save();

        // ✅ Update or insert stock into product_warehouse
        ProductWarehouse::updateOrInsert(
            [
                'product_id' => $validated['product_id'],
                'warehouse_id' => $validated['warehouse_id'],
            ],
            [
                'stock' => DB::raw("stock + {$validated['quantity']}") // You can adjust this if needed
            ]
        );

        DB::commit();
        return redirect()->route('grns.index')->with('success', 'GRN item updated successfully.');
    } catch (\Exception $e) {
        dd($e);
        DB::rollBack();
        return redirect()->route('grns.index')->with('error', 'Failed to update GRN item: ' . $e->getMessage());
    }
}
    
 public function updateItem(Request $request)
{  
    // ✅ Validation
    $validated = $request->validate([
        'grn_id' => 'required', // You missed this!
        'grn_item_id' => 'required', // Also missing
        'warehouse_id' => 'required|exists:warehouses,id',
        'grn_number' => 'required|string',
        'received_date' => 'required|date',
        'supplier_id' => 'required|exists:suppliers,id',
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'purches' => 'required|numeric',
        'suplier_discount' => 'required',
        'set_price' => 'required',
        'retail_sell_dis' => 'required',
        'whole_sell_dis' => 'required',
        'whole_sell_Price' => 'required',
        'retail_price' => 'required',
        'warranty_period' => 'required',
        'rack' => 'required',
    ]);

    try {
        DB::beginTransaction();

        // ✅ Check for GRN number duplication (excluding current GRN)
        $existingGRN = GRN::where('grn_number', $validated['grn_number'])
            ->where('id', '!=', $validated['grn_id'])
            ->exists();

        if ($existingGRN) {
            return redirect()->back()->with('error', 'GRN number already exists.');
        }

        // ✅ Update GRN
        $grn = GRN::findOrFail($validated['grn_id']);
        $grn->grn_number = $validated['grn_number'];
        $grn->warehouse_id = $validated['warehouse_id'];
        $grn->supplier_id = $validated['supplier_id'];
        $grn->received_date = $validated['received_date'];
        $grn->save();


        // ✅ Update GRN Item
        $grnItem = GrnItem::where('id', $validated['grn_item_id'])
            ->where('grn_id', $validated['grn_id'])
            ->firstOrFail();

        $unit_price = $validated['purches'];
        $total_price = $validated['quantity'] * $unit_price;

        $grnItem->product_id = $validated['product_id'];
        $grnItem->quantity = $validated['quantity'];
        $grnItem->unit_price = $unit_price;
        $grnItem->total_price = $total_price;
        $grnItem->purchase_price = $validated['purches'];
        $grnItem->supplier_discount = $validated['suplier_discount'];
        $grnItem->set_price = $validated['set_price'];
        $grnItem->retail_sell_discount = $validated['retail_sell_dis'];
        $grnItem->wholesale_discount = $validated['whole_sell_dis'];
        $grnItem->wholesale_price = $validated['whole_sell_Price'];
        $grnItem->retail_price = $validated['retail_price'];
        $grnItem->warranty_period = $validated['warranty_period'];
        $grnItem->rack_id = $validated['rack'];
        $grnItem->save();

        // ✅ Update or insert stock into product_warehouse
    ProductWarehouse::updateOrInsert(
    [
        'grn_item_id' => $grnItem->id,   // This should be the actual GRN Item ID
    ],
    [
        'product_id' => $validated['product_id'],
        'warehouse_id' => $validated['warehouse_id'],
        'stock' => DB::raw("COALESCE(stock, 0) + {$validated['quantity']}")
    ]
);

        DB::commit();
        return redirect()->route('grns.index')->with('success', 'GRN item updated successfully.');
    } catch (\Exception $e) {
        dd($e);
        DB::rollBack();
        return redirect()->route('grns.index')->with('error', 'Failed to update GRN item: ' . $e->getMessage());
    }
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
       $request->validate([
        'warehouse_id' => 'required',
        'grn_number' => 'required',
        'received_date' => 'required|date',
        'supllier_id' => 'required',
        'items.*.product_id' => 'required',
        'items.*.quantity' => 'required|integer|min:1',
          ]);
        
          try {
            DB::beginTransaction(); // Start transaction
    
            // Check if the GRN number already exists to prevent duplication
            if (GRN::where('grn_number', $request->grn_number)->exists()) {
                return redirect()->back()->with('error', 'GRN number already exists.');
            }
    
            // Create GRN record
            $grn = GRN::create([
                'grn_number' => $request->grn_number,
                'warehouse_id' => $request->warehouse_id,
                'received_date' => $request->received_date,
                'supplier_id' => $request->supllier_id,
                'remarks' => $request->remarks,
            ]);
    
            // Loop through each item and store it
            foreach ($request->items as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];
                $unitPrice = 0;
                $warehouseId = $request->warehouse_id;
    
                // Add GRN item
                GRNItem::create([
                    'grn_id' => $grn->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => 0,
                    'total_price' => $quantity * $unitPrice,
                    'warranty_period' => $item['warranty_period'] ?? null,
                ]);
    
                // Update stock in the product_warehouse table
                ProductWarehouse::updateOrInsert(
                    ['product_id' => $productId, 'warehouse_id' => $warehouseId],
                    ['stock' => DB::raw("stock + $quantity")]
                );
            }
    
            DB::commit(); // Commit transaction
    
            return redirect()->route('grns.index')->with('success', 'GRN created successfully.');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack(); // Rollback in case of error
            return redirect()->route('grns.index')->with('error', 'Failed to create GRN: ' . $e->getMessage());
        }
   
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $grns = GRN::with(['items', 'warehouse','supplier'])
        ->whereHas('items', function ($query) use ($id) {
            $query->where('grn_id', $id);
        })
        ->first();
        $suplliers = Supplier::where('delete_flag', 0)->get();
        $products  = Product::where('delete_flag', 0)->get();
        $warehouses = Warehouse::all();
        return view('grn.show', compact('grns','products','suplliers','warehouses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $grns = GRN::with(['items', 'warehouse','supplier'])
        ->whereHas('items', function ($query) use ($id) {
            $query->where('grn_id', $id);
        })
        ->first();
    
        $brands = Category::where('delete_flag', 0)->get();
        $warehouses = Warehouse::all();
        $suplliers = Supplier::where('delete_flag', 0)->get();
        $products  = Product::where('delete_flag', 0)->get();
     
        return view('grn.edit', compact('warehouses','suplliers','grns','products','brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      
       // Validate the input data
    $request->validate([
        'warehouse_id' => 'required',
        'grn_number' => 'required',
        'received_date' => 'required|date',
        'supplier_id' => 'required',
        'items.*.product_id' => 'required',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.unit_price' => 'required|numeric|min:0',
    ]);
 
     // Find the GRN to update
     $grn = GRN::findOrFail($id);

     // Update the GRN attributes
     $grn->update([
         'grn_number' => $request->grn_number,
         'warehouse_id' => $request->warehouse_id,
         'received_date' => $request->received_date,
         'supplier_id' => $request->supplier_id,
         'remarks' => $request->remarks,
     ]);
     // Remove existing GRN items associated with this GRN
     $grn->items()->delete(); // Assuming a one-to-many relationship
 
     foreach ($request->items as $index => $itemData) {
        if (isset($itemData['id'])) {
            // Update existing item
            $grnItem = GRNItem::findOrFail($itemData['id']);
            $grnItem->update([
                'product_id' => $itemData['product_id'],
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'],
                'warranty_period' => $itemData['warranty_period'],
            ]);
        } else {
            // Add new item
         
            $grn->items()->create([
                'grn_id' => $request->grn_number,
                'product_id' => $itemData['product_id'],
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'],
                'total_price' => $itemData['quantity'] * $itemData['unit_price'],
                'warranty_period' => $itemData['warranty_period'],
            ]);
        }
    }
   
     // Redirect back with a success message
     return redirect()->route('grns.index')->with('success', 'GRN updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

     
         // Find the user by ID
         $grn = GRN::findOrFail($id);
    
         // Set delete_flag to 1
         $grn->delete_flag = 1;
         $grn->save();
         return redirect()->route('grns.index')->with('success', 'GRN deleted successfully.');
    }
      /**
     * Remove the specifiedgrn item
     */
 
public function destroyItem(Request $request)
{
    $grnId = $request->input('grn_id');
    $grnItemId = $request->input('grn_item_id');

    try {
            $grnItem = GrnItem::where('id',$grnItemId)
            ->where('grn_id',$grnId)
             ->delete();
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        dd($e);
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

}