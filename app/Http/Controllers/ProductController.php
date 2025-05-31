<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class ProductController extends BaseController
{
    /**
     * GET /api/products
     */
    public function index(Request $request)
    {
        $query = Product::query()->with(['images', 'category']);

        // filtering
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->string('category'));
            });
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%{$search}%")
                  ->orWhere('description', 'ILIKE', "%{$search}%");
            });
        }

        // sorting
        switch ($request->string('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('id');
        }

        $limit = (int) $request->input('limit', 20);
        $page  = (int) $request->input('page', 1);

        $paginator = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'products' => ProductResource::collection($paginator->items()),
            'total'    => $paginator->total(),
            'page'     => $paginator->currentPage(),
            'limit'    => $paginator->perPage(),
        ]);
    }

    /**
     * GET /api/products/{product}
     */
    public function show(Product $product)
    {
        $product->load(['images', 'category']);
        return new ProductResource($product);
    }
}
