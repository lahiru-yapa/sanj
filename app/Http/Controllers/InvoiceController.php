<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Payment;
use App\Models\Warehouse;
use App\Models\ProductWarehouse;
use App\Models\Invoice; // Replace with your actual model
use Carbon\Carbon;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{

public function filter(Request $request)
{
    $query = Invoice::query();

    if ($request->shop_id) {
        $query->where('shop_id', $request->shop_id);
    }
    if ($request->products_id) {
        $query->whereHas('products', function ($q) use ($request) {
            $q->where('product_id', $request->products_id);
        });
    }

   if ($request->start_date && $request->end_date) {
    $query->whereBetween('invoice_date', [$request->start_date, $request->end_date]);
} elseif ($request->start_date) {
    $query->whereDate('invoice_date', '>=', $request->start_date);
} elseif ($request->end_date) {
    $query->whereDate('invoice_date', '<=', $request->end_date);
}


    $invoices = $query->where('delete_flag',0)->get();

    return response()->json($invoices);
}


  public function index(Request $request)
{
    $query = Invoice::with('shop')->where('delete_flag',0);
    if ($request->has('shop') && $request->shop != '') {
        $query->where('shop_id', $request->shop);
    }

    if ($request->has('start_date') && $request->has('end_date')) {
        $query->whereBetween('due_date', [$request->start_date, $request->end_date]);
    }

    if ($request->has('filter')) {
        $query->where('description', $request->filter);
    }

    $invoices = $query->paginate(10);
    $shops = Shop::all();

    return view('invoices.viewInvoice', compact('invoices', 'shops'));
}



  public function suggest(Request $request)
    {
        $query = $request->input('query');

        // Search invoices based on the typed query (case insensitive)
        $invoices = Invoice::where('invoice_number', 'like', '%' . $query . '%')->where('delete_flag',0)->get(['invoice_number']);

        return response()->json($invoices);
    }

 public function getShopByInvoice(Request $request)
    {
        $invoiceId = $request->input('invoice_id');
      
        // Find the invoice
        $invoice = Invoice::find($invoiceId);
        
        if ($invoice) {
            // Get the shop related to the invoice
            $shop = $invoice->shop;
            return response()->json(['shop_id' => $shop->id, 'shop_name' => $shop->name]);
        }
        
        return response()->json(['error' => 'Invoice not found']);
    }
    
    public function filterIndex()
    {
        $query = Invoice::with('shop')->where('delete_flag', 0);
  
        // Apply filter based on the query parameter
        if ($filter = request()->get('filter')) {
            $query->where('description', ucfirst($filter)); // Capitalize the filter value (e.g., "approved")
        }
    
        $invoices = $query->paginate(10);
    
        return view('invoices.viewInvoice', compact('invoices'));
    }

   public function getProductsByWarehouse($warehouseId, $brandId = null)
{
    try {
$priceColumn = auth()->user()->role === 'retail'
    ? 'g_r_n_items.retail_price'
    : 'g_r_n_items.wholesale_price';


$products = DB::table('product_warehouse')
    ->join('products', 'product_warehouse.product_id', '=', 'products.id')
    ->join('categories', 'products.category_id', '=', 'categories.id')
    ->leftJoin('g_r_n_items', 'g_r_n_items.id', '=', 'product_warehouse.grn_item_id')
    ->leftJoin('g_r_n_s', 'g_r_n_items.grn_id', '=', 'g_r_n_s.id')
    ->when(!empty($warehouseId), function ($query) use ($warehouseId) {
        return $query->where('product_warehouse.warehouse_id', $warehouseId);
    })
    ->when(!empty($brandId), function ($query) use ($brandId) {
        return $query->where('products.category_id', $brandId);
    })
    ->select(
        'product_warehouse.grn_item_id',
        'products.id',
        'products.name',
        'products.sku',
        DB::raw('SUM(product_warehouse.stock) as stock'),
        'categories.name as category_name',
        'categories.sku as category_sku',
        DB::raw("$priceColumn as grn_price")
    )
    ->groupBy(
        'product_warehouse.grn_item_id',
        'products.id',
        'products.name',
        'products.sku',
        'categories.name',
        'categories.sku',
        $priceColumn // Added the price column to the groupBy
    )
    ->get();


        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching products for warehouse: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching products.',
            'error' => $e->getMessage()
        ], 500);
    }
}

     public function getProductsByBrand($brandId)
    {

        try {
          
          $products = DB::table('product_warehouse')
    ->join('products', 'product_warehouse.product_id', '=', 'products.id')
    ->join('categories', 'products.category_id', '=', 'categories.id')  // Join with categories table
    ->where('products.category_id', $brandId)
    ->select('products.id', 'products.name', 'products.sku', 'product_warehouse.stock', 'categories.name as category_name', 'categories.sku as category_sku')  // Select category details
    ->get();

            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        } catch (\Exception $e) {
           
            // Log the error if needed
            \Log::error('Error fetching products for warehouse: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching products.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    


    public function updateDescription(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:invoices,id',
            'description' => 'required|string|in:approved,rejected',
        ]);

        $updated = DB::table('invoices')
            ->where('id', $request->id)
            ->update(['description' => $request->description]);

        if ($updated) {
            return response()->json(['success' => true, 'message' => 'Invoice updated successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to update invoice.'], 500);
    }

    public function edit($id)
    {
        $invoices = Invoice::with('shop', 'invoiceProducts','invoiceProducts.product')
        ->where('id',$id)
        ->first(); // Fetch a single invoice

    $shops = Shop::all();
    $products = Product::all();  
        return view('invoices.edit', compact('invoices','shops','products')); 
    }

      /**
     * Show the form for creating a new invoice.
     */
    public function addinvoice()
    {

        $shops = Shop::all();
        $user =  auth()->user()->name;
        $products = Product::all();
        $warehouse = Warehouse::all();

        return view('invoices.create', compact('shops', 'user', 'products','warehouse'));
    }


    public function handleAction(Request $request)
{
    $action = $request->input('action');
    $invoiceId = $request->input('invoice_id');

    switch ($action) {
        case 'view':
            return redirect()->route('invoices.show', $invoiceId);

        case 'edit':
              $warehouse = Warehouse::all();
            $invoices = Invoice::with('shop', 'invoiceProducts','warehouse','invoiceProducts.product.grnItems')
            ->where('id',$invoiceId)
            ->first(); // Fetch a single invoice
        
            $shops = Shop::all();
            $products = Product::all();  
                
              return view('invoices.edit', compact('invoices','shops','products','warehouse')); 

        case 'delete':
            // Handle the delete action (e.g., confirm deletion or perform the delete)
            $invoice = Invoice::findOrFail($invoiceId);
           
            $invoice->delete_flag = 1;
            $invoice->save();
            return redirect()->route('invoice.index')->with('success', 'Invoice deleted successfully.');

        default:
            return redirect()->back()->with('error', 'Invalid action selected.');
    }
}


    public function show($id)
    {
         // Eager load the invoice products and shop details along with the invoice
         $invoice = Invoice::with(['invoiceProducts.product', 'shop'])->findOrFail($id);
        return view('invoices.show', compact('invoice')); // Return the view for the invoice details
    }

    // Suggest products based on search query
    public function suggestProducts(Request $request)
    {
        $query = $request->get('query');
        $products = Product::where('name', 'like', '%' . $query . '%')
        ->orWhere('sku', 'like', '%' . $query . '%')
        ->pluck('stock', 'name');// Or you can return more data like SKU
        return response()->json($products);
    }

    // Fetch product details based on the selected product name
    public function getProductDetails(Request $request)
    {
        
        $priceColumn = auth()->user()->role === 'retail'
    ? 'g_r_n_items.retail_price'
    : 'g_r_n_items.wholesale_price';

 $price = auth()->user()->role === 'retail'
    ? 'retail_price'
    : 'wholesale_price';
    
$groupByPriceColumn = auth()->user()->role === 'retail'
    ? 'g_r_n_items.retail_price'
    : 'g_r_n_items.wholesale_price';
    
    //  $productName = preg_replace('/\s*\(.*?\)/', '', $request->get('product_name'));
$productId=$request->query('product_name');
$warehouseId =$request->query('wherehouse');
        $product = Product::where('id', $productId)->first();
        $stock = ProductWarehouse::where('product_id', $productId)
                         ->where('warehouse_id', $warehouseId)
                         ->value('stock');
    
   
    $setPrice = DB::table('g_r_n_items')
    ->join('g_r_n_s', 'g_r_n_items.grn_id', '=', 'g_r_n_s.id')
    ->where('g_r_n_items.id', $request->grn_item_id)
    ->where('g_r_n_items.product_id', $productId)
    ->where('g_r_n_s.warehouse_id', $warehouseId)
    ->select('g_r_n_items.id', $priceColumn)
    ->first();
        if ($product) {
            return response()->json([
                'grn_item_id'=>$setPrice->id,
                'id' => $product->id,
                'name' => $product->name,
                'amount' => $setPrice->$price,
                'stock' => $stock,
                'image' => asset('storage/' . $product->photo), // Assuming image is stored in storage
            ]);
        }
    
        return response()->json([], 404); // If product not found
    }

    // Store the selected products (multiple)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'selected_products' => 'required|array',
            'selected_products.*.name' => 'required|string',
            'selected_products.*.amount' => 'required|numeric',
            'selected_products.*.image' => 'required|url', // Assuming image is a URL
        ]);

        // Loop through the selected products and store them
        foreach ($validatedData['selected_products'] as $productData) {
            Product::create([
                'id' => $productData['id'],
                'name' => $productData['name'],
                'amount' => $productData['amount'],
                'image' => $productData['image'],
            ]);
        }

        return redirect()->route('product.index')->with('success', 'Products added successfully!');
    }



public function updateInvoice(Request $request, $invoiceId)
{ 

    DB::beginTransaction(); // Start a database transaction

    try {
        // Validate the request
        $validatedData = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'selected_products' => 'required|json',
            // Ensure it's valid JSON
        ]);
    
    $counts = $request->input('counts'); // array: [12 => 100]
        // Parse the selected products from JSON
        $selectedProducts = json_decode($validatedData['selected_products'], true);

foreach ($selectedProducts as &$product) {
    $id = $product['id'];
    $product['count'] = $request->input("counts.$id", $product['count'] ?? 0);
    $product['discount'] = $request->input("discount.$id", $product['discount'] ?? 0);
    $product['final_price'] = $request->input("final_price.$id", $product['final_price'] ?? 0);
}

unset($product); // good practice after using reference
        if (!$selectedProducts || !is_array($selectedProducts)) {
            return back()->withErrors(['selected_products' => 'Invalid product data.']);
        }


     // Get the invoice and previous products
        $invoice = Invoice::findOrFail($invoiceId);
        $previousProducts = $invoice->invoiceProducts()->get();

 
 // Restore previous stock
        foreach ($previousProducts as $prevProduct) {
            ProductWarehouse::where('product_id', $prevProduct->product_id)
                ->where('warehouse_id', $invoice->warehouse_id)
                ->increment('stock', $prevProduct->quantity);
        }

 // Check shop's credit limit
       $shop = Shop::find($validatedData['shop_id']);

if ($shop) {
    $creditLimit = $shop->credit_limit ?? 0;
    $currentBalance = $shop->current_balance ?? 0;
    $currentCredit = $shop->current_credit ?? 0;

    if ($currentCredit > 0) {
        // Shop has used credit already
        $availableCredit = $creditLimit - $currentCredit;
    } else {
        // Shop owes us money
        $availableCredit = $creditLimit + $currentBalance;
    }

    if ($request->totalAmount > $availableCredit) {
          return redirect()->route('invoice.index', ['error' => 'Sorry, Available credit: ' . number_format($availableCredit, 2)]);
    }
}

          // Set the invoice_date to today's date if not provided
        $invoiceDate = $request->invoice_date ?? Carbon::today();
        
         // Calculate total amount
        $totalAmount = 0;
        $counts = $request->input('counts', []);
      
        
        $discountPercentage = 0;
        // Apply discount if provided
        if (!empty($request->discount_percentage)) {
            $discountPercentage = (float) $request->discount_percentage;
            $discountValue = ($request->totalAmount * $discountPercentage) / 100;
            $totalAmount = max(0, $request->totalAmount - $discountValue);
        }
     // Step 9: Update shop current balance (if needed)
        $previousTotal = $invoice->total_amount;
        $balanceDifference = $previousTotal - $totalAmount;

        if ($balanceDifference != 0) {
            $shop->current_balance += $balanceDifference; // reduce debt if total reduced
            $shop->save();
        }
 // Update the invoice
        $invoice->update([
            'shop_id' => $validatedData['shop_id'],
            'user_id' => auth()->user()->id,
            'total_amount' => $totalAmount,
            'paid_amount' => $request->paidAmount ?? $invoice->paid_amount, 
            'paid_status' => ($invoice->paid_amount >= $totalAmount), // Adjust based on new total
            'due_date' => Carbon::today(),
            'invoice_date' => $invoiceDate,
            'discount'=>$discountPercentage,
        ]);
        
          // Delete old invoice products
        $invoice->invoiceProducts()->delete();


        // Save new invoice products and update stock
        foreach ($selectedProducts as $productData) {
            $productId = $productData['id'];
            $total = $request->totalAmount;

$price = str_replace(',', '', $productData['amount']);
$total = str_replace(',', '', $total);


            $invoice->invoiceProducts()->create([
                'product_id' => $productId,
                'quantity' => $productData['count'],
                'price' => $price,
                'total' => $total,
                'discount'=>$productData['discount'],
                'final_price'=>$productData['final_price'],
            ]);

            // Decrement stock in the correct warehouse
            ProductWarehouse::where('product_id', $productId)
                ->where('warehouse_id', $invoice->warehouse_id)
                ->decrement('stock', $productData['count']);
        }
        DB::commit(); // Commit the transaction if all operations are successful
           return redirect()->route('invoice.index')->with('success', 'Invoice created successfully.');
    } catch (\Exception $e) {
        DB::rollBack(); // Rollback the transaction if any operation fails
        return redirect()->back()->with('error', 'An error occurred while creating the invoice. Please try again.');
    }
}

}