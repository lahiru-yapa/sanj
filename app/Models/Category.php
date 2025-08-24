<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
     protected $table = 'categories';
    protected $fillable = [
        'name', 
        'sku', 
        'description', 
        'delete_flag',
    ];

     /**
     * A Category has many Departments
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }
    
   public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id'); // Foreign key should be 'category_id'
    }
    public function bikes() {
    return $this->hasMany(Bike::class);
}

}
