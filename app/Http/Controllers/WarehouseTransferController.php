<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use App\Models\Category;
use App\Models\WarehouseTransfer;
use App\Models\WarehouseTransferItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseTransferController extends Controller
{
     // Show all warehouse transfers
    public function index()
    {
        $transfers = WarehouseTransfer::with('fromWarehouse', 'toWarehouse')->latest()->paginate(10);
         $warehouses = Warehouse::pluck('name', 'id'); // [id => name]
        return view('warehouse_transfer.index', compact('transfers','warehouses'));
    }

    // Show form to create new transfer
    public function create()
    {
        $warehouses = Warehouse::all();
        $products = Product::with('category')->where('delete_flag', 0)->get();
         $brands = Category::where('delete_flag', 0)->get();
      
        return view('warehouse_transfer.create', compact('warehouses', 'products','brands'));
    }

    // Store a new warehouse transfer
    public function store(Request $request)
    {
        $validated = $request->validate([
        'warehouse_id'    => 'required',
        'warehouse_id2'   => 'required',
        'received_date'   => 'required|date',
        'grn_number'      => 'required|string|max:255',

        'items'           => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity'   => 'required|numeric|min:1',
        'items.*.rack'       => 'required|string|max:255',
    ]);

        DB::beginTransaction();

        try {
            // Create transfer
            $transfer = WarehouseTransfer::create([
                'from_warehouse_id' => $request->warehouse_id,
                'to_warehouse_id' => $request->warehouse_id2,
                'transfer_date' => now(),
            ]);

            foreach ($request->items as $item) {
                $productId = $item['product_id'];
                $qty = $item['quantity'];

                // Reduce from source
                $fromStock = ProductWarehouse::where('warehouse_id', $request->warehouse_id)
                    ->where('product_id', $productId)
                    ->lockForUpdate()
                    ->first();

                if (!$fromStock || $fromStock->stock < $qty) {
                    throw new \Exception("Not enough stock for product ID $productId.");
                }

                $fromStock->stock -= $qty;
                $fromStock->save();

                // Add to destination (must exist as you said, you don’t want auto-create)
                $toStock = ProductWarehouse::where('warehouse_id', $request->to_warehouse_id)
                    ->where('product_id', $productId)
                    ->lockForUpdate()
                    ->first();

              if (!$toStock) {
                // Create a new stock record in destination warehouse
                $toStock = ProductWarehouse::create([
                    'warehouse_id' => $request->warehouse_id2, // make sure to use warehouse_id2 here
                    'product_id'   => $productId,
                    'stock'        => 0,
                ]);
            }


                $toStock->stock += $qty;
                $toStock->save();

                // Save item record
                WarehouseTransferItem::create([
                    'warehouse_transfer_id' => $transfer->id,
                    'product_id' => $productId,
                    'quantity' => $qty,
                ]);
            }
            DB::commit();
            return redirect()->route('warehouse-transfer.index')->with('success', 'Transfer completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Show details of a specific transfer
    public function show($id)
    {
        $transfer = WarehouseTransfer::with(['fromWarehouse', 'toWarehouse', 'items.product'])->findOrFail($id);
        return view('warehouse_transfer.show', compact('transfer'));
    }
}
