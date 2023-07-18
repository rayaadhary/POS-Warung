<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_sales_detail';
    protected $guarded = [];
    protected $table = 'sales_detail';

    public function produk()
    {
        return $this->hasOne(Product::class, 'id_products', 'id_products');
    }
}
