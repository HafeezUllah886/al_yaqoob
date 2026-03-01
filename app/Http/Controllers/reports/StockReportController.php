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
use Illuminate\Support\Facades\DB;

class StockReportController extends Controller
{
    public function index()
    {
        $branches = auth()->user()->branches;

        return view('reports.stock.index', compact('branches'));
    }

    public function details(Request $request)
    {
        $branch_ids = $request->branches ?? 'all';
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($branch_ids == 'all') {
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
                ->sum(DB::raw('qty * unit_value'));

            $sales = SaleDetail::where('product_id', $product->id)
                ->whereBetween('date', [$start_date, $end_date])
                ->wherehas('sale', function ($q) use ($branch_ids) {
                    $q->whereIn('branch_id', $branch_ids);
                })
                ->sum(DB::raw('qty * unit_value'));

            $stock_adj_in = stockAdjustment::where('product_id', $product->id)
                ->whereBetween('date', [$start_date, $end_date])
                ->whereIn('branch_id', $branch_ids)
                ->where('type', 'Stock-In')
                ->sum(DB::raw('qty * unit_value'));

            $stock_adj_out = stockAdjustment::where('product_id', $product->id)
                ->whereBetween('date', [$start_date, $end_date])
                ->whereIn('branch_id', $branch_ids)
                ->where('type', 'Stock-Out')
                ->sum(DB::raw('qty * unit_value'));

            $stock_transfers_in = StockTransfer::where('product_id', $product->id)
                ->whereBetween('date', [$start_date, $end_date])
                ->whereIn('branch_to_id', $branch_ids)
                ->sum(DB::raw('pcs * unit_value'));

            $stock_transfers_out = StockTransfer::where('product_id', $product->id)
                ->whereBetween('date', [$start_date, $end_date])
                ->whereIn('branch_from_id', $branch_ids)
                ->sum(DB::raw('pcs * unit_value'));

            $opening = stock::where('product_id', $product->id)
                ->whereIn('branch_id', $branch_ids)->where('date', '<', $start_date)
                ->selectRaw('SUM(cr) - SUM(db) as balance')
                ->first()->balance ?? 0;

            $closing = stock::where('product_id', $product->id)
                ->whereIn('branch_id', $branch_ids)->where('date', '<=', $end_date)
                ->selectRaw('SUM(cr) - SUM(db) as balance')
                ->first()->balance ?? 0;

            $current_stock = stock::where('product_id', $product->id)
                ->whereIn('branch_id', $branch_ids)
                ->selectRaw('SUM(cr) - SUM(db) as balance')
                ->first()->balance ?? 0;

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
        $totals = [
            'opening' => 0, 'purchase' => 0, 'adj_in' => 0, 'adj_out' => 0, 
            'stock_transfers_in' => 0, 'stock_transfers_out' => 0,
            'sales' => 0, 'total_in' => 0, 'total_out' => 0, 'closing' => 0, 'current_stock' => 0
        ];

        foreach ($report_data as $row) {
            $ps = $row['pack_size'];
            $totals['opening'] += $row['opening'] / $ps;
            $totals['purchase'] += $row['purchase'] / $ps;
            $totals['adj_in'] += $row['adj_in'] / $ps;
            $totals['adj_out'] += $row['adj_out'] / $ps;
            $totals['stock_transfers_in'] += $row['stock_transfers_in'] / $ps;
            $totals['stock_transfers_out'] += $row['stock_transfers_out'] / $ps;
            $totals['sales'] += $row['sales'] / $ps;
            $totals['total_in'] += $row['total_in'] / $ps;
            $totals['total_out'] += $row['total_out'] / $ps;
            $totals['closing'] += $row['closing'] / $ps;
            $totals['current_stock'] += $row['current_stock'] / $ps;
        }

        return view('reports.stock.details', compact('report_data', 'branch_name', 'start_date', 'end_date', 'totals'));
    }
}
