<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['paid_by','expense_type','warehouse_id', 'category_id', 'amount', 'expense_date', 'description', 'user_id','created_by','note','paid_to'];

   public function user()
    {
        return $this->belongsTo(User::class);
    }
}
  