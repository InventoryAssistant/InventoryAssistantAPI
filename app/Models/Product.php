<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'barcode',
        'category_id',
        'content'
    ];

    /**
     * Get the category the product belongs to
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Get the locations that the product has through LocationProduct
     */
    public function location_products()
    {
        return $this->belongsToMany(Location::class)
            ->withPivot('stock')
            ->withPivot('shelf_amount');
    }
}
