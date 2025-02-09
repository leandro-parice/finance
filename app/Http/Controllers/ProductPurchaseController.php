<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;

class ProductPurchaseController extends Controller
{
    public function index($id)
    {
        $purchase = Purchase::with('products')->findOrFail($id);
        return view('purchase.product.index', compact('purchase'));
        
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($purchase)
    {
        //
    }

    public function edit($purchase)
    {
        //
    }

    public function update(Request $request, $purchase)
    {
        //
    }

    public function destroy($purchase)
    {
        //
    }
}
