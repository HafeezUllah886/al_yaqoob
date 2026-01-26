<?php

namespace App\Http\Controllers;

use App\Models\claim;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\products;
use App\Models\stock;
use App\Models\warehouses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClaimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $claims = claim::with('customer', 'vendor', 'product')->orderBy('id', 'desc')->get();
        $customers = accounts::customer()->get();
        $vendors = accounts::vendor()->get();
        $products = products::all();
        $warehouses = warehouses::all();

        return view('claims.index', compact('claims', 'customers', 'vendors', 'products', 'warehouses'));
    }
    
    /**
     * Show the form for creating a new resource.
 */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    try{
    $refID = getRef();
    $request->merge([
        'refID' => $refID,
    ]);
    claim::create($request->all());

    createStock($request->productID, 0, $request->qty, $request->date, "Claim : $request->notes", $refID,$request->warehouseID);
    DB::commit();
    return redirect()->route('claims.index')->with('success', "Claim Received by Customer");
}
catch(\Exception $e)
{
    DB::rollback();
    return redirect()->route('claims.index')->with('error', $e->getMessage());
}
    
}
    
    /**
     * Display the specified resource.
 */
    public function show(claim $claim)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(claim $claim)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, claim $claim)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($ref)
    {
        try
        {
            claim::where('refID', $ref)->delete();
            stock::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return redirect()->route('claims.index')->with('success', "Claim Received by Customer");
        }
        catch(\Exception $e)
        {
            DB::rollback();
            session()->forget('confirmed_password');
            return redirect()->route('claims.index')->with('error', $e->getMessage());
        }
    }

    public function status($status, $ref)
    {
        try
        {
            $claim=  claim::where('refID', $ref)->first();
           if($status == "Reported to Vendor")
           {
            $claim->update(
                [
                    'status' => "Reported to Vendor",
                ]
            );
           }
           if($status == "Received from Vendor")
           {
            $claim->update(
                [
                    'status' => "Received from Vendor",
                ]
            );

            createStock($claim->productID, $claim->qty, 0, now(), "Claim: Received from Vendor", $claim->refID, $claim->warehouseID);
           }
            DB::commit();
            return redirect()->route('claims.index')->with('success', "Claim Status Updated");
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->route('claims.index')->with('error', $e->getMessage());
        } 
    }
}
