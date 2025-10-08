<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    /**
     * Display the public product catalog.
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->string('search')->toString() ?: null,
            'category' => $request->string('category')->lower()->toString() ?: null,
            'sort' => $request->string('sort')->toString() ?: null,
        ];

        $query = Product::query()
            ->with(['primaryImage', 'images', 'variants'])
            ->active();

        if ($filters['search']) {
            $query->where(function ($builder) use ($filters) {
                $builder->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('summary', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        if ($filters['category']) {
            $query->where('category', $filters['category']);
        }

        if ($filters['sort']) {
            $query = match ($filters['sort']) {
                'price_low' => $query->orderBy('price', 'asc'),
                'price_high' => $query->orderBy('price', 'desc'),
                'recent' => $query->latest(),
                default => $query->orderBy('name'),
            };
        } else {
            $query->orderBy('name');
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Product::query()
            ->select('category')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->map(fn (string $category) => Str::of($category)->lower()->toString())
            ->unique()
            ->sort()
            ->values()
            ->map(fn (string $category) => [
                'value' => $category,
                'label' => Str::headline($category),
            ]);

        return view('pages.catalog', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $filters,
        ]);
    }
}
