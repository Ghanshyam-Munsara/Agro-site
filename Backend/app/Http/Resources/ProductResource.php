<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'category_label' => $this->getCategoryLabel(),
            'price' => number_format($this->price, 2, '.', ''),
            'currency' => $this->currency ?? 'USD',
            'formatted_price' => $this->formatted_price,
            'image_url' => $this->image_url,
            'full_image_url' => $this->full_image_url,
            'stock_quantity' => $this->stock_quantity,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'is_in_stock' => $this->isInStock(),
            'is_active' => $this->isActive(),
            'created_at' => $this->created_at ? $this->created_at->toISOString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toISOString() : null,
        ];
    }

    /**
     * Get human-readable category label.
     *
     * @return string
     */
    protected function getCategoryLabel()
    {
        $labels = [
            'seeds' => 'Seeds',
            'fertilizers' => 'Fertilizers',
            'equipment' => 'Equipment',
            'tools' => 'Tools',
        ];

        return $labels[$this->category] ?? ucfirst($this->category);
    }

    /**
     * Get human-readable status label.
     *
     * @return string
     */
    protected function getStatusLabel()
    {
        $labels = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'out_of_stock' => 'Out of Stock',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }
}
