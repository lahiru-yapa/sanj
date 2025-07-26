<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    use HasFactory;
    protected $table = 'invoice_items'; // Adjust to your actual table name
    protected $fillable = ['invoice_id', 'product_id', 'quantity', 'price', 'total','discount','final_price','grn_item_id'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
     // Relationship with GRNItem
    public function grnItem()
    {
        return $this->belongsTo(GRNItem::class, 'grn_item_id');
    }
}
