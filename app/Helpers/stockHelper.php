<?php

namespace App\Helpers;

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
