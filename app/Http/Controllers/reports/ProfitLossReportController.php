<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\Branches;
use App\Models\expenses;
use App\Models\NonBusinessExpenses;
use App\Models\Products;
use App\Models\purchase_details;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Helpers\getProductBranchStock;

class ProfitLossReportController extends Controller
{
    public function index()
    {
        $branches = auth()->user()->branches;
        return view('reports.profit_loss.index', compact('branches'));
    }

    public function details(Request $request)
    {
        $from = $request->start_date;
        $to = $request->end_date;
        $branch_ids = $request->branches ?? 'all';

        $products = Products::with('units')->active()->get();
        $product_profit = 0;
        $stock_value = 0;

         if ($branch_ids == 'all') {
            $branch_ids = auth()->user()->branch_ids();
            $branch_name = 'All Assigned Branches';
        } else {
            $branch_ids = [$request->branches];
            $branch_name = Branches::whereIn('id', $branch_ids)->pluck('name')->implode(', ');
        }

        foreach ($products as $product) {
            // Purchase Price calculation
            $purchase = purchase_details::where('product_id', $product->id)
                ->whereBetween('date', [$from, $to])
                ->wherehas('purchases', function ($q) use ($branch_ids) {
                    $q->whereIn('branch_id', $branch_ids);
                })->get();

            if ($purchase->count() > 0) {
                $total_amount = $purchase->sum('amount');
                $total_qty = $purchase->sum(fn($item) => $item->qty * $item->unit_value);
                $purchase_price = $total_qty > 0 ? $total_amount / $total_qty : 0;
            } else {
                // Fallback to latest purchase if none in date range
                $last_purchase = purchase_details::where('product_id', $product->id)->wherehas('purchases', function ($q) use ($branch_ids) {
                    $q->whereIn('branch_id', $branch_ids);
                })->orderBy('date', 'desc')->first();
                
                if ($last_purchase) {
                    $purchase_price = $last_purchase->amount / ($last_purchase->qty * $last_purchase->unit_value);
                } else {
                    $purchase_price = 0; // No pprice available in model
                }
            }

            // Sale Price calculation
            $sales = SaleDetail::where('product_id', $product->id)
                ->whereBetween('date', [$from, $to])
                ->wherehas('sale', function ($q) use ($branch_ids) {
                    $q->whereIn('branch_id', $branch_ids);
                })->get();

            if ($sales->count() > 0) {
                $total_amount = $sales->sum('amount');
                $total_sold_qty = $sales->sum(fn($item) => $item->qty * $item->unit_value) / $product->units->first()->value;
                $sale_price = $total_sold_qty > 0 ? ($total_amount / $total_sold_qty) / $product->units->first()->value : 0;
            } else {
                // Fallback to latest sale
                $last_sale = SaleDetail::where('product_id', $product->id)->wherehas('sale', function ($q) use ($branch_ids) {
                    $q->whereIn('branch_id', $branch_ids);
                })->orderBy('date', 'desc')->first();
                
                if ($last_sale) {
                    $sale_price = $last_sale->amount / ($last_sale->qty * $last_sale->unit_value);
                } else {
                    $sale_price = 0;
                }
                $total_sold_qty = 0;
            }

            $product->purchase_price = $purchase_price;
            $product->sale_price = $sale_price;
            $product->profit = $sale_price - $purchase_price;
            $product->sold = $total_sold_qty;
            $product->net_profit = $product->profit * $product->sold;
            
            // Stock calculation
           
            $product->stock = stock::where('product_id', $product->id)
            ->whereIn('branch_id', $branch_ids)
            ->selectRaw('SUM(cr) - SUM(db) as balance')
            ->first()->balance / $product->units->first()->value ?? 0; 
            $product->stock_value = $product->stock  * $purchase_price;

            $product_profit += $product->net_profit;
            $stock_value += $product->stock_value;
        }

        // Expenses and other metrics
        $expenses = expenses::whereBetween('date', [$from, $to])->whereIn('branch_id', $branch_ids)->sum('amount');
        $non_business_expenses = NonBusinessExpenses::whereBetween('date', [$from, $to])->whereIn('branch_id', $branch_ids)->sum('amount');

        return view('reports.profit_loss.details', compact(
            'from', 'to', 'branch_name', 'products', 
            'product_profit', 'stock_value',
            'expenses', 'non_business_expenses'
        ));
    }
}
