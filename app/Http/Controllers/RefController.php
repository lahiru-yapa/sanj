<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Payment;
use App\Models\Invoice; 
use App\Models\GRN; 
use App\Models\GRNItem; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Warehouse;
use App\Models\ProductWarehouse;
use App\Models\Category;


class RefController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('shop','warehouse')
        ->orderBy('created_at', 'desc') // Order by latest created_at
        ->where('delete_flag', 0)
        ->paginate(10);
        return view('invoices.ref.viewInvoice', compact('invoices')); 
    }


    public function filterIndex()
    {
        $query = Invoice::with('shop')->where('delete_flag', 0);
    
        // Apply filter based on the query parameter
        if ($filter = request()->get('filter')) {
            $query->where('description', ucfirst($filter)); // Capitalize the filter value (e.g., "approved")
        }
    
        $invoices = $query->paginate(10);
    
        return view('invoices.ref.viewInvoice', compact('invoices'));
    }
    
    
    public function handleAction(Request $request)
{
  
    $action = $request->input('action');
    $invoiceId = $request->input('invoice_id');

    switch ($action) {
        case 'view':
            // Eager load the invoice products and shop details along with the invoice
         $invoice = Invoice::with(['invoiceProducts.product', 'shop'])->findOrFail($invoiceId);
     
         return view('invoices.ref.show', compact('invoice')); // Return the view for the invoice details

        case 'edit':
            $warehouse = Warehouse::all();
            $invoices = Invoice::with('shop', 'invoiceProducts','warehouse','invoiceProducts.product.grnItems')
            ->where('id',$invoiceId)
            ->first(); // Fetch a single invoice
        

                $shops = Shop::all();
                 $products = Product::all();  
                
            return view('invoices.ref.edit', compact('invoices','shops','products','warehouse')); 

        case 'delete':
            // Handle the delete action (e.g., confirm deletion or perform the delete)
            $invoice = Invoice::findOrFail($invoiceId);
           
            $invoice->delete_flag = 1;
            $invoice->save();
            return redirect()->route('refinvoice.index')->with('success', 'Invoice deleted successfully.');

        default:
            return redirect()->back()->with('error', 'Invalid action selected.');
    }
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

        if (!$selectedProducts || !is_array($selectedProducts)) {
            return back()->withErrors(['selected_products' => 'Invalid product data.']);
        }

$priceColumn = auth()->user()->role === 'retail'
    ? 'retail_price'
    : 'wholesale_price';



     // Get the invoice and previous products
        $invoice = Invoice::findOrFail($invoiceId);
        $previousProducts = $invoice->invoiceProducts()->get();

      foreach ($previousProducts as $prevProduct) {
    if ($prevProduct->grn_item_id) {
        // Get the GRN item details
      $grnItem = GRNItem::find($prevProduct->grn_item_id);

if ($grnItem) {
    
    // Update the stock only for the relevant GRN item in the warehouse
    ProductWarehouse::where('product_id', $prevProduct->product_id)
        ->where('warehouse_id', $invoice->warehouse_id)
        ->where('grn_item_id', $prevProduct->grn_item_id) // ✅ Using grn_item_id directly
        ->increment('stock', $prevProduct->quantity);
}
    } 
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
          return redirect()->route('refinvoice.index', ['error' => 'Sorry, Available credit: ' . number_format($availableCredit, 2)]);
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
                'grn_item_id'=>$productData['grn_item_id'],
                'product_id' => $productId,
                'quantity' => $productData['count2'],
                'price' => $price,
                'total' => $total,
                'discount'=>$productData['discount'],
                'final_price'=>$productData['final_price'],
            ]);

   
            // Decrement stock in the correct warehouse
            ProductWarehouse::where('product_id', $productId)
                ->where('warehouse_id', $invoice->warehouse_id)
                ->where('grn_item_id', $productData['grn_item_id'])
                ->decrement('stock', $productData['count2']);
        }
        DB::commit(); // Commit the transaction if all operations are successful
        return redirect()->route('refinvoice.index')->with('success', 'Invoice created successfully.');
    } catch (\Exception $e) {
        dd($e);
        DB::rollBack(); // Rollback the transaction if any operation fails
        return redirect()->back()->with('error', 'An error occurred while creating the invoice. Please try again.');
    }
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
        $brands = Category::all();
        return view('invoices.ref.create', compact('shops', 'user', 'products','warehouse','brands'));
    }
    public function storeInvoice(Request $request)
    {
    
        // Validate the request
        $validatedData = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'selected_products' => 'required|json', // Ensure it's valid JSON
        ]);
        try {
            DB::beginTransaction(); // Start transaction
            $dueDate = Carbon::today();
            $shop = Shop::findOrFail($request->shop_id);
            $grnItemIds = $request->input('grn_item_id');
            //Get the selected products and counts from the request
            $selectedProducts = json_decode($request->input('selected_products'), true);
            
            // Loop through each product and add the discount
foreach ($selectedProducts as &$product) {
    $productId = $product['id'];

    // Check if discount exists for this product
    if (isset($request->discount[$productId])) {
        $product['discount'] = $request->discount[$productId];
    } else {
        $product['discount'] = 0; // Default 0 if no discount
    }
    
    if (isset($grnItemIds[$product['id']])) {
        $product['grn_item_id'] = $grnItemIds[$product['id']];
    }
}

            $counts = $request->input('counts', []);
    
            if (!$selectedProducts || !is_array($selectedProducts)) {
                throw new \Exception('Invalid product data.');
            } 
            
            // Group the selected products by warehouse_id
            $productsByWarehouse = [];
            $warehouseTotals = [];
                    // 3. Group products by warehouse_id
            $productsByWarehouse = collect($selectedProducts)
                ->groupBy('warehouse_id')
                ->toArray();
            
            // 4. Calculate total per warehouse
            $warehouseTotals = [];
            foreach ($productsByWarehouse as $warehouseId => $products) {
                $total = 0;
                foreach ($products as $product) {
                    $amount = (float) $product['amount'];
                    $count = (int) $product['count'];
                    $discountPercentage = (float) $product['discount'];
            
                    $subtotal = $amount * $count;
                    $discountAmount = ($subtotal * $discountPercentage) / 100;
                    $finalTotal = $subtotal - $discountAmount;
            
                    $total += $finalTotal;
                }
                $warehouseTotals[$warehouseId] = $total;
            }


           
              // Calculate grand total
        $grandTotal = array_sum($warehouseTotals);
    
        $discountPercentage=0;
               // Apply discount if needed
                   if ($request->discount_percentage !== null) {
                    $discountPercentage = (float) $request->discount_percentage;
                    $discountValue = ($grandTotal * $discountPercentage) / 100;
                    $grandTotal = max(0, $grandTotal - $discountValue);
                }
                
                 // ✅ Check available credit
        $availableCredit = $shop->credit_limit - $shop->current_credit;

        if ($grandTotal > $availableCredit) {
    return redirect()->route('ref.addinvoice', ['error' => 'Credit limit exceeded. Cannot create invoice.']);

}

            // Loop through each warehouse and create a separate invoice for it
            foreach ($productsByWarehouse as $warehouseId => $products) {
                $invoiceNumber = 'INV-' . date('Ymd') . '-' . Str::random(6);
                  // Get total amount for this warehouse
    $warehouseTotal = $warehouseTotals[$warehouseId];
   
    // Apply proportionate discount per warehouse
            $discountValue = ($warehouseTotal * $discountPercentage) / 100;
            $warehouseTotal = max(0, $warehouseTotal - $discountValue);

                // Create invoice for this warehouse
                $invoice = Invoice::create([
                    'shop_id' => $request->shop_id,
                    'user_id' => auth()->user()->id,
                    'invoice_number' => $invoiceNumber,
                    'total_amount' => $warehouseTotal,
                    'paid_amount' => 0,
                    'paid_status' => 0,
                    'due_date' => $dueDate,
                    'discount'=>$discountPercentage,
                    'invoice_date' => Carbon::today(),
                    'warehouse_id' => $warehouseId,
                    'description' => 'pending',
                ]);
    
                // Assign product counts for this warehouse
                foreach ($products as &$product) { 
                    $productId = $product['id'];
                    $product['count'] = $counts[$productId] ?? 0;
                }
                // Insert invoice products and update stock for this warehouse
                foreach ($products as $productData) {
                    $productId = $productData['id']; // ✅ Define $productId here
    
                    $total = $productData['count'] * $productData['amount'];
                    $final_price = $total*(1-$productData['discount']/100);
                    $invoice->invoiceProducts()->create([
                        'grn_item_id'=>$productData['grn_item_id'],
                        'product_id' => $productId,
                        'quantity' => $productData['count'],
                        'price' => $productData['amount'],
                        'total' => $total,
                        'discount'=>$productData['discount'],
                        'final_price'=>$final_price,
                    ]);
 
                    // Decrement the stock for the product in the correct warehouse
                    ProductWarehouse::where('product_id', $productId)
                        ->where('warehouse_id', $warehouseId)
                        ->decrement('stock', $productData['count']);
                }
            }
    
        // ✅ Update shop's current credit balance
        $shop->current_credit += $grandTotal;
        $shop->save();
        
            DB::commit(); // Commit transaction if everything is successful
    
            return redirect()->route('refinvoice.index')->with('success', 'Invoices added successfully!');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack(); // Rollback transaction in case of error
            return redirect()->route('refinvoice.index')->with('error', 'Failed to add invoice: ' . $e->getMessage());
        }
    }
    
}