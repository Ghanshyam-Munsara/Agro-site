<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'service_id' => $this->service_id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'icon' => $this->icon,
            'price' => $this->price ? number_format($this->price, 2, '.', '') : null,
            'price_type' => $this->price_type,
            'price_type_label' => $this->getPriceTypeLabel(),
            'formatted_price' => $this->formatted_price,
            'active_clients' => $this->active_clients,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'image_url' => $this->image_url,
            'full_image_url' => $this->full_image_url,
            'is_active' => $this->isActive(),
            'created_at' => $this->created_at ? $this->created_at->toISOString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toISOString() : null,
        ];
    }

    /**
     * Get human-readable price type label.
     *
     * @return string|null
     */
    protected function getPriceTypeLabel()
    {
        if (!$this->price_type) {
            return null;
        }

        $labels = [
            'fixed' => 'Fixed Price',
            'monthly' => 'Per Month',
            'hourly' => 'Per Hour',
            'per_unit' => 'Per Unit',
        ];

        return $labels[$this->price_type] ?? ucfirst(str_replace('_', ' ', $this->price_type));
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
            'pending' => 'Pending',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }
}
