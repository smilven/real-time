<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Events\ProductCreate;
class productController extends Controller
{
    public function index(){
        $products = product::latest()->get();
        return view ("products",compact('products'));
    }
    public function userIndex(){
        $products = product::latest()->get();
        $products = product::paginate(8);
        return view ("userProducts",compact('products'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            "productName" => "required",
            "productPrice" => "required",
            "productQuantity" => "required",
            "productImage" => "nullable|image|mimes:jpeg,png,jpg,gif"
        ]);
    
        $imagePath = null;
    
        if ($request->hasFile('productImage')) {
            $imagePath = $request->file('productImage')->store('product_images', 'public');
        }
    
        $product = Product::create([
            "productName" => $request->productName,
            "productPrice" => $request->productPrice,
            "productQuantity" => $request->productQuantity,
            "productImage" => $imagePath
        ]);
    
        event(new ProductCreate($product));
    
        return redirect()->back();
    }
    
}
