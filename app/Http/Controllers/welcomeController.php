<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;

class welcomeController extends Controller
{
    public function homePage()
    {
        $products = product::all();
        return view('home',compact('products'));
    }
}
