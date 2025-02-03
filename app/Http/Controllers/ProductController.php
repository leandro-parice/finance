<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('product.index', compact('products'));
    }

    public function create()
    {
        $product = new Product;
        return view('product.form', compact('product'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'barcode' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->barcode = $request->barcode;
        $product->description = $request->description;
        $product->save();

        return redirect()->route('product.index')->with('success', 'Dados criados com sucesso.');
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $product = Product::findOrfail($id);
        return view('product.form', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'barcode' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $product = Product::findOrfail($id);
        $product->name = $request->name;
        $product->barcode = $request->barcode;
        $product->description = $request->description;
        $product->save();

        return redirect()->route('product.index')->with('success', 'Dados editados com sucesso.');
    }

    public function destroy($id)
    {
        $product = Product::findOrfail($id);
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Dados removidos com sucesso.');
    }
}
