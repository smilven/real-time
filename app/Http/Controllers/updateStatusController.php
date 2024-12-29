<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\Cart;

class updateStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(){
        $userId = auth()->id();
        $totalQuantity = Cart::where('user_id', $userId)->sum('quantity'); 
        $products = product::latest()->get();
        return view ("updateStatus",compact('products','totalQuantity'));
    }
}
