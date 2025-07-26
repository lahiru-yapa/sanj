<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\Shop;
use App\Models\Product;
use App\Models\User;
use App\Models\Invoice;
use App\Models\GRN;
use App\Models\Bike;
use App\Models\Category;
use App\Models\RealCtegorie;
use App\Models\InvoiceProduct;
class SalesReportController extends Controller
{
  
    public function suplierReport()
{
    return view('reports.suplierReport');
}


public function filterProductSales(Request $request)
{
    $query = InvoiceProduct::with([
        'product.grnItems', 
        'invoice.shop', 
        'invoice.ref'
    ])
    ->whereHas('invoice', function($q) use ($request) {
        if ($request->start_date) {
            $q->whereDate('invoice_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $q->whereDate('invoice_date', '<=', $request->end_date);
        }
        if ($request->shop_id) {
            $q->where('shop_id', $request->shop_id);
        }
        if ($request->ref_id) {
            $q->where('user_id', $request->ref_id);
        }
    });

    if ($request->product_id) {
        $query->where('product_id', $request->product_id);
    }

    if ($request->bike_id) {
        $query->whereHas('product', function($q) use ($request) {
            $q->where('bike_id', $request->bike_id);
        });
    }

    if ($request->parts_id) {
        $query->whereHas('product', function($q) use ($request) {
            $q->where('category_id', $request->parts_id);
        });
    }

    if ($request->brand_id) {
        $query->whereHas('product', function($q) use ($request) {
            $q->where('brand_id', $request->brand_id);
        });
    }

    $results = $query->get();

    // Format the data for the frontend
    $formatted = $results->map(function($item) {
        return [
            'product_name' => $item->product->name ?? '-',
            'total_qty' => $item->quantity ?? 0,
            'total_amount' => number_format(($item->final_price ?? 0) * ($item->quantity ?? 0), 2),
            'shop_name' => $item->invoice->shop->name ?? '-',
            'ref_name' => $item->invoice->ref->name ?? '-',
            'invoice_date' => $item->invoice->invoice_date ?? '-',
            'invoice_number' => $item->invoice->invoice_number ?? '-',
        ];
    });

    return response()->json($formatted);
}


public function filter(Request $request)
{
    $query = Invoice::with('shop','ref'); // 👈 Load shop data automatically


    if ($request->start_date) {
        $query->whereDate('invoice_date', '>=', $request->start_date);
    }

    if ($request->end_date) {
        $query->whereDate('invoice_date', '<=', $request->end_date);
    }

    if ($request->shop_id) {
        $query->where('shop_id', $request->shop_id);
    }

    if ($request->ref_id) {
        $query->where('user_id', $request->ref_id); // 👈 use user_id for ref
    }

    $sales = $query->get()->map(function ($sale) {
        return [
            'invoice_no' => $sale->invoice_number,
            'invoice_date' => $sale->invoice_date,
            'shop_name' => $sale->shop->name ?? '',
            'ref_name' => $sale->user->name ?? '', // if you add ref relationship (optional)
            'total_amount' => $sale->total_amount,
            'ref_name'=>$sale->ref->name,
        ];
    });

    return response()->json($sales);
}


 public function filterGRNs(Request $request)
    {

        $query = GRN::select(
                'g_r_n_s.id',
                'g_r_n_s.grn_number',
                'suppliers.name as supplier_name',
                'g_r_n_s.received_date',
                'g_r_n_s.remarks',
                DB::raw('(SELECT SUM(total_price) FROM g_r_n_items WHERE g_r_n_items.grn_id = g_r_n_s.id) as total_amount')
            )
            ->leftJoin('suppliers', 'g_r_n_s.supplier_id', '=', 'suppliers.id');

        if ($request->supplier_id) {
            $query->where('g_r_n_s.supplier_id', $request->supplier_id);
        }

        if ($request->start_date) {
            $query->whereDate('g_r_n_s.received_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('g_r_n_s.received_date', '<=', $request->end_date);
        }

        $grns = $query->get();
        return response()->json($grns);
    }

public function getProfitReport(Request $request)
{
    // Get date range from the request
    $startDate = $request->input('start_date', now()->subMonth()->toDateString());
    $endDate = $request->input('end_date', now()->toDateString());
    $interval = $request->input('interval', 'day'); // 'day' or 'month'

    // Generate date range array (for grouping by day or month)
    $dates = [];
    $currentDate = \Carbon\Carbon::parse($startDate);

    while ($currentDate->lte(\Carbon\Carbon::parse($endDate))) {
        $dates[] = $currentDate->format($interval == 'month' ? 'Y-m' : 'Y-m-d'); 
        $currentDate->add($interval == 'month' ? '1 month' : '1 day');
    }

    // Get total income (sum of total_amount from invoices table)
    $totalIncome = DB::table('invoices')
        ->whereBetween('invoices.created_at', [$startDate, $endDate])
        ->select(DB::raw('DATE_FORMAT(invoices.created_at, "' . ($interval == 'month' ? '%Y-%m' : '%Y-%m-%d') . '") as date'), DB::raw('SUM(invoices.total_amount) as total_income'))
        ->groupBy(DB::raw('DATE_FORMAT(invoices.created_at, "' . ($interval == 'month' ? '%Y-%m' : '%Y-%m-%d') . '")'))
        ->where('delete_flag',0)
        ->get();

    // Get total purchase amount (sum of product purchase prices from invoice_items table)
    $totalPurchaseAmount = DB::table('invoice_items')
        ->join('products', 'invoice_items.product_id', '=', 'products.id')
        ->whereBetween('invoice_items.created_at', [$startDate, $endDate])
        ->select(DB::raw('DATE_FORMAT(invoice_items.created_at, "' . ($interval == 'month' ? '%Y-%m' : '%Y-%m-%d') . '") as date'), DB::raw('SUM(invoice_items.quantity * products.price) as total_purchase_amount'))
        ->groupBy(DB::raw('DATE_FORMAT(invoice_items.created_at, "' . ($interval == 'month' ? '%Y-%m' : '%Y-%m-%d') . '")'))
        ->get();

    // Merge data into categories and values
    $categories = [];
    $incomeValues = [];
    $purchaseValues = [];
    $profitValues = [];

    foreach ($dates as $date) {
        $income = $totalIncome->firstWhere('date', $date);
        $purchase = $totalPurchaseAmount->firstWhere('date', $date);

        $categories[] = $date;
        $incomeValues[] = $income ? $income->total_income : 0;
        $purchaseValues[] = $purchase ? $purchase->total_purchase_amount : 0;
        $profitValues[] = ($income ? $income->total_income : 0) - ($purchase ? $purchase->total_purchase_amount : 0);
    }

    // Return data for chart
    return response()->json([
        'categories' => $categories,
        'income_values' => $incomeValues,
        'purchase_values' => $purchaseValues,
        'profit_values' => $profitValues
    ]);
}


public function getStockSummary(Request $request)
{

$stockData = DB::table('product_warehouse')
    ->join('products', 'product_warehouse.product_id', '=', 'products.id')
    ->join('warehouses', 'product_warehouse.warehouse_id', '=', 'warehouses.id') // Assuming you have a warehouses table
    ->select(
        'products.name as product_name',
        DB::raw('SUM(product_warehouse.stock) as total_stock')
    )
    ->groupBy('products.name')
    ->get();

return response()->json([
    'categories' => $stockData->pluck('product_name'),
    'values' => $stockData->pluck('total_stock'),
]);
}
  public function index()
{
   
  $products = Product::with(['category', 'grnItems' => function($query) {
        $query->select('id', 'product_id', 'set_price');
    }])
    ->select('id', 'name', 'category_id')
    ->where('delete_flag', 0)
    ->get();

    $bikes =Bike::all();
    $brands = RealCtegorie::all();
    $categorys =Category::all();
    $suppliers = Supplier::all();
    $shops = Shop::all();
     $refs = User::where('role', 'ref')->get();
    return view('reports.index', compact('suppliers','shops','refs','products','bikes','categorys','brands'));
    
}


public function getSalesReport(Request $request)
{
    $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
    $endDate = $request->input('end_date', now()->format('Y-m-d'));
    $filter = $request->input('filter', 'daily');

    // Define grouping for Daily, Weekly, Monthly
    if ($filter === 'daily') {
        $groupBy = "DATE(invoices.invoice_date)";
        $dateFormat = "%Y-%m-%d";
    } else { // Monthly
        $groupBy = "DATE_FORMAT(invoices.invoice_date, '%Y-%m')";
        $dateFormat = "%Y-%m";
    }

 $salesData = DB::table('invoices')
    ->select(
        DB::raw("DATE_FORMAT(invoices.invoice_date, '$dateFormat') as period"),
        DB::raw("SUM(invoices.total_amount) as total_sales")
    )
    ->whereBetween('invoices.invoice_date', [$startDate, $endDate])
    ->groupBy(DB::raw("DATE_FORMAT(invoices.invoice_date, '$dateFormat')")) // Ensure this matches SELECT
    ->where('delete_flag',0)
    ->orderBy('period')
    ->get();

    return response()->json($salesData);
}

public function getProductSalesReport(Request $request)
{
    $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
    $endDate = $request->input('end_date', now()->format('Y-m-d'));

    // Fetch product-wise sales data
    $productSalesData = DB::table('invoice_items')
        ->join('products', 'invoice_items.product_id', '=', 'products.id')
        ->select(
            'products.name as product_name',
            DB::raw("SUM(invoice_items.quantity) as total_sold")
        )
        ->whereBetween('invoice_items.created_at', [$startDate, $endDate])
        ->groupBy('products.name')
        ->orderBy('total_sold', 'desc')
        ->get();

    return response()->json($productSalesData);
}
}
