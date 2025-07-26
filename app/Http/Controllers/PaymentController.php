<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
      public function index(Request $request){
           $payments = Payment::with('shop', 'invoice')->latest()->get();
             return view('payments.index', compact('payments'));
      }


public function getProfitLoss(Request $request)
{
   
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');
    $invoiceStatus = $request->query('invoice_status'); // e.g., 'approved'
    $paidStatus = $request->query('paid_status');       // e.g., 'partial'

    // Build the invoice query with optional filters
    $invoiceQuery = DB::table('invoices')
        ->whereBetween('invoice_date', [$startDate, $endDate]);

    if ($invoiceStatus) {
        $invoiceQuery->where('description', $invoiceStatus);
    }

    if ($paidStatus) {
        $invoiceQuery->where('paid_status', $paidStatus);
    }

    $salesRevenue = (clone $invoiceQuery)->sum('total_amount');

    // COGS - join invoice_items
    $cogs = DB::table('invoice_items')
        ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
        ->whereBetween('invoices.invoice_date', [$startDate, $endDate]);

    if ($invoiceStatus) {
        $cogs->where('invoices.description', $invoiceStatus);
    }

    if ($paidStatus) {
        $cogs->where('invoices.paid_status', $paidStatus);
    }
 
    $cogsTotal = $cogs->select(DB::raw('SUM(invoice_items.total) as total_cogs'))->value('total_cogs');

    // Expenses
    $expenses = DB::table('expenses')
        ->whereBetween('expense_date', [$startDate, $endDate])
        ->sum('amount');

    // Outstanding Invoices (total_amount > paid_amount)
    $outstanding = (clone $invoiceQuery)
        ->whereColumn('total_amount', '>', 'paid_amount')
        ->select(DB::raw('SUM(total_amount - paid_amount) as total_outstanding'))
        ->value('total_outstanding');

    // Calculations
    $grossProfit = $salesRevenue - $cogsTotal;
    $netProfit = $grossProfit - $expenses;

    return response()->json([
        'salesRevenue' => $salesRevenue,
        'cogs' => $cogsTotal,
        'grossProfit' => $grossProfit,
        'expenses' => $expenses,
        'netProfit' => $netProfit,
        'outstandingInvoices' => $outstanding,
    ]);
}


     public function create()
    {
        $shops = Shop::all();
        return view('payments.create', compact('shops'));
    }
    // PaymentController.php
public function getInvoices($shop_id)
{
    // Get all invoices for the selected shop
         $invoices = Invoice::where('shop_id', $shop_id)
            ->get();


    // Calculate the total remaining balance
    $remainingBalanceSum = Shop::where('id', $shop_id)->value('current_balance');


    // Return the data as a JSON response
    return response()->json([
        'invoices' => $invoices,
        'remaining_balance_sum' => $remainingBalanceSum
    ]);
}


public function store(Request $request)
    {
        $request->validate([
            'shope_id' => 'required|exists:shops,id',
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
        ]);
        DB::beginTransaction();

        try {
            // Save payment
            $payment = Payment::create([
                'shop_id' => $request->shope_id,
                'invoice_id' => $request->invoice_id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'paid_at' => now(),
            ]);

            // Update invoice status
            $invoice = Invoice::find($request->invoice_id);
            $invoice->paid_amount += $request->amount;
             // Calculate remaining balance
                $invoice->remaining_balance = $invoice->total_amount - $invoice->paid_amount;
            if ($invoice->paid_amount >= $invoice->total_amount) {
                $invoice->paid_status = 1;
                //paid
            } elseif ($invoice->paid_amount > 0) {
                $invoice->paid_status = 2;
                //partial paid
            }

            $invoice->save();
              // 3. Update shop balance (can become negative)
                $shop = Shop::find($request->shope_id);
                $shop->current_balance -= $request->amount;
                $shop->save();

            DB::commit();

            return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error recording payment.');
        }
    }
    
}