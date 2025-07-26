<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bike extends Model
{
    use HasFactory;
    protected $fillable = [
        'brand_id', 
        'name', 
        'categorie_id',
    ];
    public function categorie() {
    return $this->belongsTo(Category::class);
    }

    public function parts() {
        return $this->belongsToMany(Part::class);
    }

}
