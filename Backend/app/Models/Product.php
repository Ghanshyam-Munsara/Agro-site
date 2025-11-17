<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'currency',
        'image_url',
        'stock_quantity',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];

    /**
     * Category constants
     */
    const CATEGORY_SEEDS = 'seeds';
    const CATEGORY_FERTILIZERS = 'fertilizers';
    const CATEGORY_EQUIPMENT = 'equipment';
    const CATEGORY_TOOLS = 'tools';

    /**
     * Status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_OUT_OF_STOCK = 'out_of_stock';

    /**
     * Scope: Filter by category
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: Filter by status
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter by price range
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float|null $minPrice
     * @param float|null $maxPrice
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    /**
     * Scope: Search in name and description
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchTerm
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('description', 'LIKE', "%{$searchTerm}%");
        });
    }

    /**
     * Scope: Active products only
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope: In stock products
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0)
                     ->where('status', '!=', self::STATUS_OUT_OF_STOCK);
    }

    /**
     * Accessor: Get formatted price with currency
     *
     * @return string
     */
    public function getFormattedPriceAttribute()
    {
        return $this->currency . ' ' . number_format($this->price, 2);
    }

    /**
     * Accessor: Get full image URL
     *
     * @return string|null
     */
    public function getFullImageUrlAttribute()
    {
        if (!$this->image_url) {
            return null;
        }

        // If image_url is already a full URL, return it
        if (filter_var($this->image_url, FILTER_VALIDATE_URL)) {
            return $this->image_url;
        }

        // Otherwise, prepend the storage URL
        return asset('storage/products/' . $this->image_url);
    }

    /**
     * Mutator: Set price (ensure it's always positive)
     *
     * @param mixed $value
     * @return void
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = max(0, (float) $value);
    }

    /**
     * Mutator: Set currency (uppercase)
     *
     * @param string $value
     * @return void
     */
    public function setCurrencyAttribute($value)
    {
        $this->attributes['currency'] = strtoupper($value);
    }

    /**
     * Check if product is in stock
     *
     * @return bool
     */
    public function isInStock()
    {
        return $this->stock_quantity > 0 && $this->status !== self::STATUS_OUT_OF_STOCK;
    }

    /**
     * Check if product is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
