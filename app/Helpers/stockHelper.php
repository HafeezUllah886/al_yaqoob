<?php

use App\Models\Products;
use App\Models\purchase_details;
use App\Models\SaleDetail;
use App\Models\stock;

function createStock($productID, $cr, $db, $date, $notes, $refID, $warehouseID)
{
    $stock = stock::create(
        [
            'product_id' => $productID,
            'branch_id' => $warehouseID,
            'date' => $date,
            'cr' => $cr,
            'db' => $db,
            'notes' => $notes,
            'refID' => $refID,
        ]
    );
}

function getProductBranchStock($product_id, $branch_id)
{
    return stock::where('product_id', $product_id)->where('branch_id', $branch_id)->sum('cr') - stock::where('product_id', $product_id)->where('branch_id', $branch_id)->sum('db');
}

function getBranchStockValue($branch)
{
    if ($branch == 'All') {
        $branch_ids = Auth()->user()->branch_ids();
    } else {
        $branch_ids = [$branch];
    }

    $products = Products::with('units')->active()->get();
    $stock_value = 0;
     foreach ($products as $product) {
            // Purchase Price calculation
            $purchase = purchase_details::where('product_id', $product->id)
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
            
            // Stock calculation
           
            $product->stock = stock::where('product_id', $product->id)
            ->whereIn('branch_id', $branch_ids)
            ->selectRaw('SUM(cr) - SUM(db) as balance')
            ->first()->balance / $product->units->first()->value ?? 0; 
            $product->stock_value = $product->stock  * $purchase_price;

            $stock_value += $product->stock_value;
        }


    return $stock_value;
}
