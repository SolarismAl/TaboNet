<?php

namespace App\Models;

class Product extends Listing
{
    public const CATEGORIES = [
        'Root Crops',
        'Fruits',
        'Grains & Cereals',
        'Vegetables',
        'Aquaculture',
    ];

    public const UNITS = [
        'kg' => 'Kilogram (kg)',
        'kaing' => 'Kaing (Basket)',
        'sako' => 'Sako (Bag/50kg)',
        'bunch' => 'Bunch / Cluster',
    ];

    /**
     * Create listing bridging legacy product attributes
     */
    public static function create(array $attributes = [])
    {
        if (!isset($attributes['commodity_id']) && isset($attributes['name'])) {
            $catName = $attributes['category'] ?? 'Grains & Cereals';
            $category = Category::firstOrCreate(['name' => $catName], [
                'description' => $catName . ' agricultural category',
            ]);

            $unit = $attributes['unit'] ?? 'kg';
            $commodity = Commodity::firstOrCreate(
                ['name' => $attributes['name'], 'category_id' => $category->category_id],
                [
                    'unit_of_measure' => $unit,
                    'description' => $attributes['description'] ?? $attributes['name'],
                ]
            );

            $attributes['commodity_id'] = $commodity->commodity_id;
            $attributes['title'] = $attributes['name'];
        }

        if (isset($attributes['user_id']) && !isset($attributes['farmer_id'])) {
            $attributes['farmer_id'] = $attributes['user_id'];
        }

        if (isset($attributes['price']) && !isset($attributes['price_per_unit'])) {
            $attributes['price_per_unit'] = $attributes['price'];
        }

        if (isset($attributes['quantity']) && !isset($attributes['available_quantity'])) {
            $attributes['available_quantity'] = $attributes['quantity'];
        }

        unset(
            $attributes['name'],
            $attributes['category'],
            $attributes['quantity'],
            $attributes['unit'],
            $attributes['price'],
            $attributes['barangay'],
            $attributes['user_id'],
            $attributes['pickup_location'],
            $attributes['harvest_date']
        );

        return (new static)->newQuery()->create($attributes);
    }
}
