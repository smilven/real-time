<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class adminSettingController extends Controller
{
    public function indexAdminSetting() 
    {
        // 获取现有的图片设置
        $bannerImage = Setting::where('key', 'banner_image')->first();
        $middleBannerImage = Setting::where('key', 'middle_banner_image')->first();
        $bottomBannerLeftImage = Setting::where('key', 'bottom_banner_left_image')->first();
        $bottomBannerRightImage = Setting::where('key', 'bottom_banner_right_image')->first();
        
        // 将图片数据传递到视图
        return view('adminSetting', compact('bannerImage', 'middleBannerImage', 'bottomBannerLeftImage', 'bottomBannerRightImage'));
    }
    
    public function update(Request $request)
    {
        // 验证图片
        $request->validate([
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'middle_banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'bottom_banner_left_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'bottom_banner_right_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        // 上传图片并存储路径
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('banner_images', 'public');
            Setting::updateOrCreate(['key' => 'banner_image'], ['value' => $path]);
        }

        if ($request->hasFile('middle_banner_image')) {
            $path = $request->file('middle_banner_image')->store('banner_images', 'public');
            Setting::updateOrCreate(['key' => 'middle_banner_image'], ['value' => $path]);
        }

        if ($request->hasFile('bottom_banner_left_image')) {
            $path = $request->file('bottom_banner_left_image')->store('banner_images', 'public');
            Setting::updateOrCreate(['key' => 'bottom_banner_left_image'], ['value' => $path]);
        }

        if ($request->hasFile('bottom_banner_right_image')) {
            $path = $request->file('bottom_banner_right_image')->store('banner_images', 'public');
            Setting::updateOrCreate(['key' => 'bottom_banner_right_image'], ['value' => $path]);
        }

        return redirect()->back()->with('success', 'Banner images updated successfully!');
    }
}

