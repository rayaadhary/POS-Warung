<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_purchases';
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_suppliers', 'id_suppliers');
    }
}
