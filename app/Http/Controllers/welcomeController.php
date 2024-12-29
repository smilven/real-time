<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\Cart;

class welcomeController extends Controller
{
    public function homePage()
    {
        $products = product::all();
        $userId = auth()->id();
        $totalQuantity = Cart::where('user_id', $userId)->sum('quantity'); 
        return view('home',compact('products','totalQuantity'));
    }
}
