<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments'; // Optional if table name is 'customers'
    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_date',
        'payment_method',
        'shop_id',
    ];
    // Relationship to Invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

public function shop()
{
    return $this->belongsTo(Shop::class, 'shop_id');
}

}

