<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\Cart;
use App\Models\Setting;

class welcomeController extends Controller
{
    public function homePage()
    {

        $products = product::latest()->get();
        $userId = auth()->id();
        $totalQuantity = Cart::where('user_id', $userId)->sum('quantity');

        $bannerImage =  Setting::where('key', 'banner_image')->first();
        $middleBannerImage = Setting::where('key', 'middle_banner_image')->first();
        $bottomBannerLeftImage = Setting::where('key', 'bottom_banner_left_image')->first();
        $bottomBannerRightImage = Setting::where('key', 'bottom_banner_right_image')->first();
        
        return view('home',compact('products','totalQuantity','bannerImage','middleBannerImage','bottomBannerLeftImage', 'bottomBannerRightImage'));
    }
}
