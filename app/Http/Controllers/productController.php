<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\product;
use App\Events\ProductCreate;
use App\Events\ProductQuantityUpdated;

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

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // 验证输入
        $validator = Validator::make($request->all(), [
            'productName' => 'required|string|max:255',
            'productPrice' => 'required|numeric',
            'productQuantity' => 'required|integer|min:0',
            'productImage' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        // 更新产品
        $product->productName = $request->productName;
        $product->productPrice = $request->productPrice;
        $product->productQuantity = $request->productQuantity;

        // 更新产品图片
        if ($request->hasFile('productImage')) {
            // 删除旧图片
            if ($product->productImage) {
                Storage::disk('public')->delete($product->productImage);
            }
            $product->productImage = $request->file('productImage')->store('product_images', 'public');
        }

        $product->save();

        // 触发事件，广播产品更新信息
        event(new ProductQuantityUpdated($product));

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'product' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->productImage) {
            Storage::disk('public')->delete($product->productImage);
        }

        $product->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted successfully!']);
    }
    
}
