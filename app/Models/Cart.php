<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'quantity'];

    // 定义与 Product 模型的关系
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
