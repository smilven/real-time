<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;

class updateStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(){
        $products = product::latest()->get();
        return view ("updateStatus",compact('products'));
    }
}
