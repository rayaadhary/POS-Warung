<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sales extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_sales';
    protected $guarded = [];
    protected $table = 'sales';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
