<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class FrontendController extends Controller
{
    /**
     * Homepage
     */
    public function home()
    {
        // Get active sliders
        $sliders = \App\Models\Slider::getActiveSliders();
        
        // Get active rotating slogans
        $slogans = \App\Models\Slogan::getActiveSlogans();
        
        // Get featured products
        $featuredProducts = Product::where('is_featured', 1)
            ->where('status', 1)
            ->with('category', 'brand')
            ->take(8)
            ->get();
        
        // Get new products
        $newProducts = Product::where('is_new', 1)
            ->where('status', 1)
            ->with('category', 'brand')
            ->take(8)
            ->get();
        
        // Get best sellers (top selling products)
        $bestSellers = Product::where('status', 1)
            ->with('category', 'brand')
            ->take(8)
            ->get();
        
        // Get featured categories
        $categories = Category::where('status', 1)
            ->where('is_featured', 1)
            ->latest()
            ->take(12)
            ->get();

        return view('frontend.home', compact('sliders', 'slogans', 'featuredProducts', 'newProducts', 'bestSellers', 'categories'));
    }

    /**
     * Shop Page
     */
    public function shop(Request $request)
    {
        // Debug: Log the request parameters
        \Log::info('Shop page request parameters:', $request->all());
        
        $query = Product::where('status', 1)->with('category', 'brand');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->has('categories') && is_array($request->categories)) {
            $query->whereIn('category_id', $request->categories);
        }

        // Brand filter
        if ($request->has('brands') && is_array($request->brands)) {
            $query->whereIn('brand_id', $request->brands);
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price !== '' && $request->min_price !== null) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price !== '' && $request->max_price !== null) {
            $query->where('price', '<=', $request->max_price);
        }

        // Stock status filters
        if ($request->has('in_stock')) {
            $query->where('stock', '>', 0);
        }
        if ($request->has('on_sale')) {
            $query->whereNotNull('discount_price')
                  ->where('discount_price', '<', \DB::raw('price'));
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        // Get categories with product counts
        $categories = Category::where('status', 1)
            ->withCount(['products' => function($query) {
                $query->where('status', 1);
            }])
            ->get();

        // Get brands with product counts
        $brands = Brand::where('status', 1)
            ->withCount(['products' => function($query) {
                $query->where('status', 1);
            }])
            ->get();

        return view('frontend.shop', compact('products', 'categories', 'brands'));
    }

    /**
     * Product Details Page
     */
    public function product($slug)
    {
        $product = Product::where('slug', $slug)->with('category', 'brand')->firstOrFail();
        
        // Load product images from database
        $productImages = DB::table('product_images')->where('product_id', $product->id)->get();
        
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)->get();

        return view('frontend.product', compact('product', 'productImages', 'relatedProducts'));
    }

    /**
     * Cart Page
     */
    public function cart()
    {
        return view('frontend.cart');
    }

    /**
     * Contact Page
     */
    public function contact()
    {
        return view('frontend.contact-us');
    }

    /**
     * Get product details for API
     */
    public function getProduct($id)
    {
        try {
            \Log::info('API Product Request for ID: ' . $id);
            
            // First, try to get the product without relationships
            $product = Product::findOrFail($id);
            \Log::info('Product found: ' . $product->name);
            
            // Try to load relationships one by one with error handling
            try {
                $product->load('category');
                \Log::info('Category loaded successfully');
            } catch (\Exception $e) {
                \Log::error('Error loading category: ' . $e->getMessage());
            }
            
            try {
                $product->load('brand');
                \Log::info('Brand loaded successfully');
            } catch (\Exception $e) {
                \Log::error('Error loading brand: ' . $e->getMessage());
            }
            
            try {
                $product->load('images');
                \Log::info('Images loaded successfully');
            } catch (\Exception $e) {
                \Log::error('Error loading images: ' . $e->getMessage());
            }
            
            $response = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => $product->price,
                'discount_price' => $product->discount_price,
                'thumbnail' => $product->thumbnail,
                'images' => $product->images ? $product->images->pluck('image') : [],
                'category' => $product->category ? $product->category->name : 'No Category',
                'brand' => $product->brand ? $product->brand->name : 'No Brand',
                'stock' => $product->stock,
                'sku' => $product->sku,
                'is_featured' => $product->is_featured,
                'is_new' => $product->is_new,
                'status' => $product->status
            ];
            
            \Log::info('API Product Response prepared successfully');
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('API Product Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Product not found',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}