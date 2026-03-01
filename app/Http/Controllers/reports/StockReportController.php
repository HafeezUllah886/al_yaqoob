<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\Branches;
use App\Models\Products;
use App\Models\purchase_details;
use App\Models\SaleDetail;
use App\Models\stock;
use App\Models\stockAdjustment;
use App\Models\StockTransfer;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    public function index()
    {
        $branches = auth()->user()->branches;

        return view('reports.stock.index', compact('branches'));
    }

    public function details(Request $request)
    {
        $branch_ids = $request->branches ?? 'All';
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($branch_ids == 'All') {
            $branch_ids = auth()->user()->branch_ids();
            $branch_name = 'All Assigned Branches';
        } else {
            $branch_ids = [$request->branches];
            $branch_name = Branches::whereIn('id', $branch_ids)->pluck('name')->implode(', ');
        }

        $products = Products::with(['units' => function ($q) {
            $q->orderBy('id', 'asc'); // Use the first unit as per requirement
        }])->active()->get();

        $report_data = [];

        foreach ($products as $product) {
            $first_unit = $product->units->first();
            $pack_size = $first_unit ? $first_unit->value : 1;

            $purchases = purchase_details::where('product_id', $product->id)
                ->whereBetween('date', [$start_date, $end_date])
                ->wherehas('purchases', function ($q) use ($branch_ids) {
                    $q->whereIn('branch_id', $branch_ids);
                })
                ->select('qty * unit_value as qty')
                ->sum('qty');

            $sales = SaleDetail::where('product_id', $product->id)
                ->whereBetween('date', [$start_date, $end_date])
                ->wherehas('sale', function ($q) use ($branch_ids) {
                    $q->whereIn('branch_id', $branch_ids);
                })
                ->select('qty * unit_value as qty')
                ->sum('qty');

            $stock_adj_in = stockAdjustment::where('product_id', $product->id)
                ->whereBetween('date', [$start_date, $end_date])
                ->whereIn('branch_id', $branch_ids)
                ->where('type', 'Stock-In')
                ->select('qty * unit_value as qty')
                ->sum('qty');

            $stock_adj_out = stockAdjustment::where('product_id', $product->id)
                ->whereBetween('date', [$start_date, $end_date])
                ->whereIn('branch_id', $branch_ids)
                ->where('type', 'Stock-Out')
                ->select('qty * unit_value as qty')
                ->sum('qty');

            $stock_transfers_in = StockTransfer::where('product_id', $product->id)
                ->whereBetween('date', [$start_date, $end_date])
                ->whereIn('branch_to_id', $branch_ids)
                ->select('pcs * unit_value as qty')
                ->sum('pcs');

            $stock_transfers_out = stockTransfer::where('product_id', $product->id)
                ->whereBetween('date', [$start_date, $end_date])
                ->whereIn('branch_from_id', $branch_ids)
                ->select('pcs * unit_value as qty')
                ->sum('pcs');

            $opening = stock::where('product_id', $product->id)
                ->whereIn('branch_id', $branch_ids)->whereDate('date', '<', $start_date)
                ->sum('cr') - stock::where('product_id', $product->id)
                ->whereIn('branch_id', $branch_ids)->whereDate('date', '<', $start_date)
                ->sum('db');

            $closing = stock::where('product_id', $product->id)
                ->whereIn('branch_id', $branch_ids)->whereDate('date', '<=', $end_date)
                ->sum('cr') - stock::where('product_id', $product->id)
                ->whereIn('branch_id', $branch_ids)->whereDate('date', '<=', $end_date)
                ->sum('db');

            $current_stock = stock::where('product_id', $product->id)
                ->whereIn('branch_id', $branch_ids)
                ->sum('cr') - stock::where('product_id', $product->id)
                ->whereIn('branch_id', $branch_ids)
                ->sum('db');

                $total_in = $purchases + $stock_adj_in + $stock_transfers_in;
                $total_out = $sales + $stock_adj_out + $stock_transfers_out;



            $report_data[] = [
                'product' => $product->name,
                'unit' => $first_unit ? $first_unit->unit_name : 'N/A',
                'pack_size' => $pack_size,
                'opening' => $opening,
                'purchase' => $purchases,
                'sales' => $sales,
                'adj_in' => $stock_adj_in,
                'adj_out' => $stock_adj_out,
                'stock_transfers_in' => $stock_transfers_in,
                'stock_transfers_out' => $stock_transfers_out,
                'closing' => $closing,
                'current_stock' => $current_stock,
                'total_in' => $total_in,
                'total_out' => $total_out,
            ];
        }

        return view('reports.stock.details', compact('report_data', 'branch_name', 'start_date', 'end_date'));
    }
}
