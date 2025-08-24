<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealCtegorie extends Model

{
    use HasFactory;
     protected $fillable = [
        'name', 
    ];
    
    public function parts() {
    return $this->hasMany(Part::class);
}

public function products()
{
    return $this->hasMany(Product::class, 'real_category_id');
}

public function bikes()
{
    return $this->hasMany(Bike::class, 'real_ctegorie_id');
}

}
