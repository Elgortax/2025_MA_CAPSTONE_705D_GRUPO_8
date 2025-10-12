<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class ProductController extends Controller
{
    /**
     * Display a listing of products in the admin panel.
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'status' => $request->string('status')->toString(),
            'category' => $request->string('category')->lower()->toString(),
        ];

        $query = Product::query()
            ->withCount('variants')
            ->with('primaryImage')
            ->when($filters['search'], function ($builder, string $search) {
                $builder->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($filters['category'], function ($builder, string $category) {
                $builder->where('category', $category);
            });

        $query->when($filters['status'], function ($builder, string $status) {
            return match ($status) {
                'published' => $builder->where('is_active', true),
                'draft' => $builder->where('is_active', false),
                'archived' => $builder->onlyTrashed(),
                default => $builder,
            };
        });

        $products = $query
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        $metrics = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
            'archived' => Product::onlyTrashed()->count(),
        ];

        $categories = $this->categories();

        return view('admin.products.index', [
            'products' => $products,
            'filters' => $filters,
            'metrics' => $metrics,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => $this->categories(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $tags = $this->extractTags($data['tags'] ?? '');

        $product = DB::transaction(function () use ($data, $tags, $request) {
            $product = Product::create([
                'name' => $data['name'],
                'sku' => $data['sku'] ?? null,
                'category' => $data['category'] ? Str::of($data['category'])->trim()->lower()->toString() : null,
                'summary' => $data['summary'] ?? null,
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'stock' => $data['stock'],
                'is_active' => $request->boolean('is_active', true),
                'metadata' => ['tags' => $tags],
            ]);

            $product->variants()->create([
                'name' => $product->name . ' estándar',
                'price' => $data['price'],
                'stock' => $data['stock'],
                'is_default' => true,
            ]);

            if ($request->hasFile('primary_image')) {
                $path = $this->uploadToSupabase($request->file('primary_image'));

                $product->images()->create([
                    'path' => $path,
                    'disk' => 'supabase',
                    'alt_text' => $product->name,
                    'position' => 0,
                    'is_primary' => true,
                ]);
            }

            return $product;
        });

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('status', 'Producto creado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $producto): View
    {
        $producto->load('primaryImage');

        return view('admin.products.edit', [
            'product' => $producto,
            'categories' => $this->categories(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $producto): RedirectResponse
    {
        $data = $request->validated();
        $tags = $this->extractTags($data['tags'] ?? '');

        DB::transaction(function () use ($producto, $data, $tags, $request) {
            $producto->update([
                'name' => $data['name'],
                'sku' => $data['sku'] ?? null,
                'category' => $data['category'] ? Str::of($data['category'])->trim()->lower()->toString() : null,
                'summary' => $data['summary'] ?? null,
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'stock' => $data['stock'],
                'is_active' => $request->boolean('is_active', false),
                'metadata' => ['tags' => $tags],
            ]);

            $defaultVariant = $producto->variants()->where('is_default', true)->first();
            if ($defaultVariant) {
                $defaultVariant->update([
                    'name' => $producto->name . ' estándar',
                    'price' => $data['price'],
                    'stock' => $data['stock'],
                ]);
            } else {
                $producto->variants()->create([
                    'name' => $producto->name . ' estándar',
                    'price' => $data['price'],
                    'stock' => $data['stock'],
                    'is_default' => true,
                ]);
            }

            if ($request->hasFile('primary_image')) {
                $path = $this->uploadToSupabase($request->file('primary_image'));

                $primaryImage = $producto->primaryImage;
                if ($primaryImage && $primaryImage->path) {
                    $this->deleteImageFile($primaryImage);
                    $primaryImage->update([
                        'path' => $path,
                        'disk' => 'supabase',
                        'alt_text' => $producto->name,
                    ]);
                } else {
                    $producto->images()->create([
                        'path' => $path,
                        'disk' => 'supabase',
                        'alt_text' => $producto->name,
                        'position' => 0,
                        'is_primary' => true,
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.products.edit', $producto)
            ->with('status', 'Producto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $producto): RedirectResponse
    {
        DB::transaction(function () use ($producto): void {
            $producto->load('images');

            foreach ($producto->images as $image) {
                $this->deleteImageFile($image);
            }

            $producto->variants()->delete();
            $producto->forceDelete();
        });

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Producto eliminado.');
    }

    /**
     * Upload an image file to Supabase Storage and return the stored path.
     */
    protected function uploadToSupabase(UploadedFile $file): string
    {
        $bucket = config('services.supabase.bucket');
        $baseUrl = rtrim((string) config('services.supabase.url'), '/');
        $token = config('services.supabase.service_role');

        if (! $bucket || ! $baseUrl || ! $token) {
            throw new RuntimeException('Supabase Storage no está configurado correctamente.');
        }

        $path = 'products/' . Str::uuid() . '.' . $file->getClientOriginalExtension();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => $file->getMimeType() ?: 'application/octet-stream',
            'x-upsert' => 'true',
        ])->send('POST', "{$baseUrl}/storage/v1/object/{$bucket}/" . ltrim($path, '/'), [
            'body' => $file->get(),
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Error al subir la imagen a Supabase: ' . $response->body());
        }

        return $path;
    }

    /**
     * Delete the physical file associated with the image record.
     */
    protected function deleteImageFile(ProductImage $image): void
    {
        if (! $image->path) {
            return;
        }

        if ($image->disk === 'supabase') {
            $this->deleteFromSupabase($image->path);

            return;
        }

        $disk = $image->disk ?? 'public';

        if (Storage::disk($disk)->exists($image->path)) {
            Storage::disk($disk)->delete($image->path);
        }
    }

    /**
     * Remove an object from Supabase Storage.
     */
    protected function deleteFromSupabase(string $path): void
    {
        $bucket = config('services.supabase.bucket');
        $baseUrl = rtrim((string) config('services.supabase.url'), '/');
        $token = config('services.supabase.service_role');

        if (! $bucket || ! $baseUrl || ! $token) {
            return;
        }

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete("{$baseUrl}/storage/v1/object/{$bucket}/" . ltrim($path, '/'));
    }

    /**
     * Get available categories.
     */
    protected function categories()
    {
        return Product::query()
            ->select('category')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->map(fn ($category) => Str::lower($category))
            ->unique()
            ->sort()
            ->values();
    }

    /**
     * Normalize tags from the request.
     */
    protected function extractTags(?string $tags): array
    {
        return collect(explode(',', (string) $tags))
            ->map(fn ($tag) => trim(Str::lower($tag)))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
