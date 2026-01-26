<?php

namespace App\Http\Controllers;

use App\Models\scrapeSale;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\scrapStock;
use App\Models\transactions;
use Illuminate\Http\Request;

class ScrapeSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scrapeSales = scrapeSale::orderBy('id', 'desc')->get();
        $accounts = accounts::business()->get();

        $cr= scrapStock::sum('cr');
        $db= scrapStock::sum('db');
        $stock = $cr - $db;
        return view('scrape.sale.index', compact('scrapeSales', 'accounts', 'stock'));
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
        $refID = getRef();
    $request->merge(['refID' => $refID]);
    $sale = scrapeSale::create($request->except('accountID'));

    scrapStock::create(
        [
            'date' => $request->date,
            'db' => $request->weight,
            'refID' => $refID,
            'notes' => $request->notes,
        ]
    );

  
        createTransaction($request->accountID, $request->date, $request->amount, 0,  "Payment of Scrap Sale",$refID, 'Scrap Sale');

return redirect()->route('scrap_sale.index')->with('success', 'Scrap Sale Created');
}
    
    /**
     * Display the specified resource.
     */
    public function show(scrapeSale $scrapeSale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(scrapeSale $scrapeSale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, scrapeSale $scrapeSale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ref)
    {
        scrapeSale::where('refID', $ref)->delete();
        scrapStock::where('refID', $ref)->delete();
        transactions::where('refID', $ref)->delete();
        session()->forget('confirmed_password');
        return redirect()->route('scrap_sale.index')->with('success', 'Scrap Sale Deleted');
    }
}
