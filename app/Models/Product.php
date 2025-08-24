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
        'category_id',
        'supplier_id',
        'photo',
        'real_category_id',
        'bikes_id',
        'department_id',
        'low_stock',
        'rack_name',
    ];

    public function realCategory()
    {
        return $this->belongsTo(RealCtegorie::class, 'real_category_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function bike()
    {
        return $this->belongsTo(Bike::class, 'department_id');
    }
    
    public function rackDetail()
    {
        return $this->belongsTo(RackDetail::class, 'department_id');
    }
    

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
