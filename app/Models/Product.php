<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products'; // Optional if table name is 'customers'
    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'stock',
        'category_id',
        'supplier_id',
        'photo',
        'sell_price',
        'real_category',
        'bike',
    ];
    
      public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id'); 
    }
    
    public function invoices()
    {
        return $this->belongsTo(Shop::class);
    }
   // Relationship to ReturnItem
   public function returnItems(): HasMany
   {
       return $this->hasMany(ReturnItem::class, 'product_id');
   }

    public function grnItems()
    {
        return $this->hasMany(GRNItem::class, 'product_id');
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'product_warehouse')
                    ->withPivot('stock')
                    ->withTimestamps();
    }
}
