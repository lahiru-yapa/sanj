<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Payment;
use App\Models\InvoiceProduct; 
use App\Models\Invoice;
use App\Models\ProductReturn;
use App\Models\ReturnItem;
use Illuminate\Support\Facades\DB;

class ReturnProductController extends Controller
{
    public function allReturns()
    {
       $returnedProducts = ProductReturn::with([
        'returnItems.product', 
        'invoice', 
        'shop' // Now, you can directly get the shop details
    ])
    ->orderBy('return_date', 'desc')
    ->get();

    return view('Returns.allReturns', compact('returnedProducts'));
      
    }
    
    public function edit($id, $product_id)
    {
      
        // Fetch the ProductReturn model
        $returnProduct = ProductReturn::with(['returnItems', 'shop','invoice'])->find($id);
        $productDetails = Product::where('id',$product_id)->first();
        
        // Fetch the related ReturnItem
        $returnItem = $returnProduct->returnItems()->where('product_id', $product_id)->first();
   
        return view('Returns.edit', compact('returnProduct','returnItem','productDetails'));
    }


    public function addReturns(Request $request)
    {
      
        $shop =Shop::where('delete_flag', 0)->get();
        $invoice = Invoice::with('shop')
        ->where('delete_flag', 0)
        ->get();
     $products = Product::get();
        return view('Returns.addReturn', compact('invoice','shop','products'));
    }

        public function getInvoiceProducts(Request $request)
    {
        $invoiceId = $request->get('invoice_id');

   $products = InvoiceProduct::with('product:id,name') // Load related product name
        ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id') // Join with invoices table
        ->where('invoice_items.invoice_id', $invoiceId)
        ->select('invoice_items.product_id', 'invoice_items.quantity', 'invoice_items.price', 'invoices.discount') // Include discount
        ->get();


        return response()->json(['products' => $products]);
    }
public function getReturnedProducts(Request $request)
{
 // Fetching product returns with related return items and products
$returnedProducts = ProductReturn::with('returnItems.product') // Eager load returnItems and their products
->where('invoice_id', $request->invoice_id)
->get();

    return response()->json(['returned_products' => $returnedProducts]);
}

//when submit return modal
    public function returnProduct(Request $request)
    {
   
     $validatedData = $request->validate([
        'invoice_id'      => 'required|integer',
        'productId'       => 'required|integer',
        'salable_status'  => 'required|string',
        'return_quantity' => 'required|integer|min:1',
        'return_reason'   => 'nullable|string',
    ]);

    DB::beginTransaction();
    try {
        
        $discountPrasantage = Invoice::where('id', $request->invoice_id)
                                            ->where('delete_flag', 0)
                                            ->first();

        // Step 1: Create ProductReturn entry (if not already created for this invoice)
        $productReturn = ProductReturn::firstOrCreate(
            [
                'invoice_id' => $validatedData['invoice_id'],
                'shop_id'    => 1,  // Replace with dynamic shop_id from the request or session
            ],
            [
                'return_date'    => now(),
                'salable_status' => $validatedData['salable_status'],
                'total_amount'   => 0,
            ]
        );
       
        // Step 2: Create ReturnItem entry
        $returnAmount = $this->calculateReturnAmount($validatedData['productId'], $validatedData['return_quantity']);
       if ($discountPrasantage->discount > 0) {
            $returnAmount *= $discountPrasantage->discount;
        }

        $returnItem = ReturnItem::create([
            'product_return_id' => $productReturn->id,
            'product_id'        => $validatedData['productId'],
            'quantity'          => $validatedData['return_quantity'],
            'salable_status'    => $validatedData['salable_status'],
            'reason'            => $validatedData['return_reason'],
            'return_amount'     => $returnAmount,
        ]);

        // Step 3: Update total amount in ProductReturn
        $productReturn->total_amount += $returnAmount;
        $productReturn->save();

         // Step 5: If salable, update product stock
         if ($validatedData['salable_status'] === 'salable') {
            Product::where('id', $validatedData['productId'])->increment('stock', $validatedData['return_quantity']);
        }


        DB::commit();


        $returnedProducts = ProductReturn::with('returnItems.product') // Eager load returnItems and their products
    ->where('invoice_id',  $validatedData['invoice_id'])
    ->get();

    return response()->json(['returned_products' => $returnedProducts]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
    return response()->json(['success' => true, 'message' => 'Product return processed successfully']);
    }

    private function calculateReturnAmount($productId, $quantity)
    {
        // Example calculation: Replace with actual logic to calculate the return amount
        $product = DB::table('products')->find($productId);
        $unitPrice = $product ? $product->price : 0;  // Replace 'price' with the actual column name
        return $unitPrice * $quantity;
    }
}