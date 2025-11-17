<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Create a new product.
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        // Handle image upload if present
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image_url'] = $this->uploadImage($data['image']);
            unset($data['image']);
        }

        return Product::create($data);
    }

    /**
     * Update a product.
     *
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function updateProduct(Product $product, array $data): Product
    {
        // Handle image upload if present
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            // Delete old image if exists
            if ($product->image_url) {
                $this->deleteImage($product->image_url);
            }
            $data['image_url'] = $this->uploadImage($data['image']);
            unset($data['image']);
        }

        $product->update($data);
        return $product->fresh();
    }

    /**
     * Delete a product (soft delete).
     *
     * @param Product $product
     * @return bool
     */
    public function deleteProduct(Product $product): bool
    {
        // Optionally delete image file
        // if ($product->image_url) {
        //     $this->deleteImage($product->image_url);
        // }

        return $product->delete();
    }

    /**
     * Upload product image.
     *
     * @param UploadedFile $file
     * @return string
     */
    public function uploadImage(UploadedFile $file): string
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('products', $filename, 'public');

        return $filename; // Return just the filename, full URL handled by model accessor
    }

    /**
     * Delete product image.
     *
     * @param string $imageUrl
     * @return bool
     */
    public function deleteImage(string $imageUrl): bool
    {
        // If it's a full URL, extract filename
        if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            $imageUrl = basename($imageUrl);
        }

        return Storage::disk('public')->delete('products/' . $imageUrl);
    }

    /**
     * Search products with filters.
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchProducts(array $filters = [])
    {
        $query = Product::query();

        // Apply category filter
        if (isset($filters['category'])) {
            $query->byCategory($filters['category']);
        }

        // Apply status filter
        if (isset($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        // Apply search term
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Apply price range
        if (isset($filters['min_price']) || isset($filters['max_price'])) {
            $query->byPriceRange(
                $filters['min_price'] ?? null,
                $filters['max_price'] ?? null
            );
        }

        // Apply sorting
        $sortBy = $filters['sort'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';

        if (in_array($sortBy, ['name', 'price', 'created_at'])) {
            $query->orderBy($sortBy, $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    /**
     * Update product stock.
     *
     * @param Product $product
     * @param int $quantity
     * @param string $operation (add, subtract, set)
     * @return Product
     */
    public function updateStock(Product $product, int $quantity, string $operation = 'set'): Product
    {
        switch ($operation) {
            case 'add':
                $product->increment('stock_quantity', $quantity);
                break;
            case 'subtract':
                $product->decrement('stock_quantity', $quantity);
                // Auto-update status if out of stock
                if ($product->fresh()->stock_quantity <= 0) {
                    $product->update(['status' => Product::STATUS_OUT_OF_STOCK]);
                }
                break;
            case 'set':
            default:
                $product->update(['stock_quantity' => $quantity]);
                // Auto-update status
                if ($quantity <= 0) {
                    $product->update(['status' => Product::STATUS_OUT_OF_STOCK]);
                } elseif ($product->status === Product::STATUS_OUT_OF_STOCK && $quantity > 0) {
                    $product->update(['status' => Product::STATUS_ACTIVE]);
                }
                break;
        }

        return $product->fresh();
    }

    /**
     * Get products by category.
     *
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProductsByCategory(string $category)
    {
        return Product::byCategory($category)->active()->get();
    }

    /**
     * Get in-stock products.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInStockProducts()
    {
        return Product::inStock()->get();
    }
}

