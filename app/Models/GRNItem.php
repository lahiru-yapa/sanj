<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GRNItem extends Model
{
    use HasFactory;

  protected $fillable = [
        'grn_id', 
        'product_id', 
        'quantity', 
        'unit_price', 
        'total_price',
        'purchase_price',  // Added
        'supplier_discount',  // Added
        'set_price',  // Added
        'retail_sell_discount',  // Added
        'wholesale_discount',  // Added
        'wholesale_price',  // Added
        'retail_price',  // Added
        'warranty_period',
        'rack_id',  // Added
    ];
    
    public function grn()
    {
        return $this->belongsTo(GRN::class, 'grn_id'); // Explicitly define foreign key
    }
    public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

    // Relationship with InvoiceProduct
    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::class, 'grn_item_id');
    }

}
