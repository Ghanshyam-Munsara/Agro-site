<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_id',
        'name',
        'description',
        'category',
        'icon',
        'price',
        'price_type',
        'active_clients',
        'status',
        'image_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'active_clients' => 'integer',
    ];

    /**
     * Status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_PENDING = 'pending';

    /**
     * Price type constants
     */
    const PRICE_TYPE_FIXED = 'fixed';
    const PRICE_TYPE_MONTHLY = 'monthly';
    const PRICE_TYPE_HOURLY = 'hourly';
    const PRICE_TYPE_PER_UNIT = 'per_unit';

    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            // Auto-generate service_id if not provided
            if (empty($service->service_id)) {
                $service->service_id = static::generateServiceId();
            }
        });
    }

    /**
     * Generate a unique service ID
     *
     * @return string
     */
    protected static function generateServiceId()
    {
        $lastService = static::withTrashed()
            ->where('service_id', 'LIKE', 'S%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastService && preg_match('/S(\d+)/', $lastService->service_id, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
            return 'S' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return 'S001';
    }

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
              ->orWhere('description', 'LIKE', "%{$searchTerm}%")
              ->orWhere('service_id', 'LIKE', "%{$searchTerm}%");
        });
    }

    /**
     * Scope: Active services only
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Accessor: Get formatted price with type
     *
     * @return string|null
     */
    public function getFormattedPriceAttribute()
    {
        if ($this->price === null) {
            return null;
        }

        $formattedPrice = number_format($this->price, 2);
        $priceTypeLabel = $this->getPriceTypeLabel();

        return '$' . $formattedPrice . ($priceTypeLabel ? ' / ' . $priceTypeLabel : '');
    }

    /**
     * Get price type label
     *
     * @return string
     */
    public function getPriceTypeLabel()
    {
        $labels = [
            self::PRICE_TYPE_FIXED => '',
            self::PRICE_TYPE_MONTHLY => 'month',
            self::PRICE_TYPE_HOURLY => 'hour',
            self::PRICE_TYPE_PER_UNIT => 'unit',
        ];

        return $labels[$this->price_type] ?? '';
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
        return asset('storage/services/' . $this->image_url);
    }

    /**
     * Mutator: Set price (ensure it's always positive or null)
     *
     * @param mixed $value
     * @return void
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value === null ? null : max(0, (float) $value);
    }

    /**
     * Increment active clients count
     *
     * @param int $amount
     * @return void
     */
    public function incrementActiveClients($amount = 1)
    {
        $this->increment('active_clients', $amount);
    }

    /**
     * Decrement active clients count
     *
     * @param int $amount
     * @return void
     */
    public function decrementActiveClients($amount = 1)
    {
        $this->decrement('active_clients', $amount);
    }

    /**
     * Check if service is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
