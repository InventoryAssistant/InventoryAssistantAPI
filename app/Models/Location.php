<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Location extends Model
{
    use Searchable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'address'
    ];

    /**
     * Get the users the role has
     */
    public function users()
    {
        return $this->hasMany(User::class, 'location_id', 'id');
    }

    /**
     * Get the products that the location has through LocationProduct
     */
    public function location_products()
    {
        return $this->belongsToMany(Product::class);
    }
}
