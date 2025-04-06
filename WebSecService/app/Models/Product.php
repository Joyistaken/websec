<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model  {

	protected $fillable = [
        'code',
        'name',
        'price',
        'model',
        'description',
        'photo',
        'stock_quantity'
    ];
    
    /**
     * Get the purchases associated with the product.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    
    /**
     * Get all users who purchased this product.
     */
    public function purchasedByUsers()
    {
        return $this->belongsToMany(User::class, 'purchases')
            ->withPivot('price_paid')
            ->withTimestamps();
    }
}