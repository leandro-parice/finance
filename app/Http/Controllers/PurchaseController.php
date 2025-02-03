<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::orderBy('date', 'desc')->get();
        return view('purchase.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $purchase = new Purchase;
        return view('purchase.form', compact('purchase'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'market' => 'required|string',
            'value' => 'required|numeric|min:0',
            'date' => 'required|date_format:d/m/Y',
        ]);

        $date = Carbon::createFromFormat('d/m/Y', $request->date);

        $purchase = new Purchase;
        $purchase->market = $request->market;
        $purchase->value = $request->value;
        $purchase->date = $date;
        $purchase->save();

        return redirect()->route('purchase.index')->with('success', 'Dados criados com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        return view('purchase.form', compact('purchase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'market' => 'required|string',
            'value' => 'required|numeric|min:0',
            'date' => 'required|date_format:d/m/Y',
        ]);
        
        $date = Carbon::createFromFormat('d/m/Y', $request->date);
        
        $purchase = Purchase::findOrFail($id);
        $purchase->market = $request->market;
        $purchase->value = $request->value;
        $purchase->date = $date;
        $purchase->save();

        return redirect()->route('purchase.index')->with('success', 'Dados editados com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();

        return redirect()->route('purchase.index')->with('success', 'Dados removidos com sucesso.');
    }
}
