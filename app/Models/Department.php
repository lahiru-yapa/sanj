<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
    ];
    
    /**
     * A Department belongs to a Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
