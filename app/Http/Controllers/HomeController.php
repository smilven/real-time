<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\Cart;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = product::latest()->get();
        $userId = auth()->id();
        $totalQuantity = Cart::where('user_id', $userId)->sum('quantity');
        return view('home',compact('products','totalQuantity'));
    }
  
    
}
