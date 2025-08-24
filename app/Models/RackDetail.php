<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RackDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'rack_code', 
        'rack_name',
        'warehouse_id',
        'row_number',
        'column_number'
    ];
 
    public function warehouse()
{
    return $this->belongsTo(Warehouse::class);
}

public function products()
{
    return $this->hasMany(Product::class, 'rack_name');
}

}
