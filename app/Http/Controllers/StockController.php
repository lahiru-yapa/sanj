<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Warehouse;
use App\Models\ProductWarehouse;

class StockController extends Controller
{
    public function index()
    {

       $invoices = Invoice::with('shop')
        ->where('delete_flag', 0)  // Filter for delete_flag
        ->whereIn('description', ['Approved', 'Completed'])  // Filter for both Approved and Completed
        ->paginate(10);

    
        return view('invoices.stock.viewInvoice', compact('invoices')); 
    }

    public function handleAction(Request $request)
    {
      
        $action = $request->input('action');
        $invoiceId = $request->input('invoice_id');
    
        switch ($action) {
            case 'view':
                // Eager load the invoice products and shop details along with the invoice
             $invoice = Invoice::with(['invoiceProducts.product', 'shop'])->findOrFail($invoiceId);
             return view('invoices.stock.show', compact('invoice')); // Return the view for the invoice details
    
            case 'edit':
                 $warehouse = Warehouse::all();
             
              $invoices = Invoice::with('shop', 'invoiceProducts','invoiceProducts.product','warehouse')
            ->where('id',$invoiceId)
            ->first(); // Fetch a single invoice
        
        
                    $shops = Shop::all();
                     $products = Product::all();  
                return view('invoices.stock.edit', compact('invoices','shops','products','warehouse')); 
    
            case 'delete':
                // Handle the delete action (e.g., confirm deletion or perform the delete)
                $invoice = Invoice::findOrFail($invoiceId);
               
                $invoice->delete_flag = 1;
                $invoice->save();
                return redirect()->route('refinvoice.index ')->with('success', 'Invoice deleted successfully.');
    
            default:
                return redirect()->back()->with('error', 'Invalid action selected.');
        }
    }
    
    public function updateInvoice(Request $request, $id)
    {   
     DB::beginTransaction(); // Start a database transaction
     try {
            DB::beginTransaction(); // Start transaction
            $dueDate = Carbon::today();
            
            //Get the selected products and counts from the request
            $selectedProducts = json_decode($request->input('selected_products'), true);
            $counts = $request->input('counts');
            $warehouseId = $request->input('warehouse');
            $finalArray = [];
            foreach ($selectedProducts as $product) {
            $productId = $product['id'];
            $product['warehouse_id'] = $warehouseId;
            $product['count'] = $counts[$productId] ?? "0"; // Default to "0" if not found
            $finalArray[] = $product;
            }

            if (!$selectedProducts || !is_array($selectedProducts)) {
                throw new \Exception('Invalid product data.');
            }
  
     // Get the invoice and previous products
        $invoice = Invoice::findOrFail($id);
         $previousProducts = $invoice->invoiceProducts()->get();
        
            // Delete old invoice products
        $invoice->invoiceProducts()->delete();
        $invoice->delete_flag = 1;
        $invoice->save();
        
 // Restore previous stock
        foreach ($previousProducts as $prevProduct) {
            ProductWarehouse::where('product_id', $prevProduct->product_id)
                ->where('warehouse_id', $invoice->warehouse_id)
                ->increment('stock', $prevProduct->quantity);
        }


        $invoice_number=$invoice->invoice_number;
     
            // Group the selected products by warehouse_id
            $productsByWarehouse = [];
            $warehouseTotals = [];
            foreach ($finalArray as $product) {
                $warehouseId = $product['warehouse_id'];
                $productsByWarehouse[$warehouseId][] = $product;
                  // Calculate warehouse-wise total
    $warehouseTotals[$warehouseId] = ($warehouseTotals[$warehouseId] ?? 0) + $product['amount']*$product['count'];

            }
            
            // Loop through each warehouse and create a separate invoice for it
            foreach ($productsByWarehouse as $warehouseId => $products) {
                $invoiceNumber = $invoice_number;
                  // Get total amount for this warehouse
    $warehouseTotal = $warehouseTotals[$warehouseId];
   
$discountPercentage=0;
   // Apply discount if needed
   if ($request->discount_percentage !== null) {
    $discountPercentage = (float) $request->discount_percentage;
    $discountValue = ($warehouseTotal * $discountPercentage) / 100;
    $warehouseTotal = max(0, $warehouseTotal - $discountValue);
}
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
                    'description' => 'completed',
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
                    $invoice->invoiceProducts()->create([
                        'product_id' => $productId,
                        'quantity' => $productData['count'],
                        'price' => $productData['amount'],
                        'total' => $total,
                    ]);
    
                    // Decrement the stock for the product in the correct warehouse
                    ProductWarehouse::where('product_id', $productId)
                        ->where('warehouse_id', $warehouseId)
                        ->decrement('stock', $productData['count']);
                }
            }
            DB::commit(); // Commit transaction if everything is successful
            return redirect()->route('stockinvoice.index')->with('success', 'Invoices added successfully!');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack(); // Rollback transaction in case of error
            return redirect()->route('stockinvoice.index')->with('error', 'Failed to add invoice: ' . $e->getMessage());
        }
    }
    
}
