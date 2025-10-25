<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Product_image;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // ✅ This line should be here


class ProductController extends Controller
{
    /**
     * Display a listing of the products with filtering.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'subcategory', 'brand', 'images', 'inventory']);

        // Search by product name, SKU, or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        // Filter by price range
        if ($request->filled('price_from')) {
            $query->where('price', '>=', $request->price_from);
        }

        if ($request->filled('price_to')) {
            $query->where('price', '<=', $request->price_to);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            $query->whereHas('inventory', function($q) use ($request) {
                switch ($request->stock_status) {
                    case 'in_stock':
                        $q->where('current_stock', '>', 0);
                        break;
                    case 'low_stock':
                        $q->whereColumn('current_stock', '<=', 'minimum_stock')
                          ->where('current_stock', '>', 0);
                        break;
                    case 'out_of_stock':
                        $q->where('current_stock', '<=', 0);
                        break;
                }
            });
        }

        // Sort products
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['name', 'price', 'sku', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $products = $query->paginate(20)->withQueryString();

        // Prepare JS-friendly collection safely
        $productsForJs = $products->map(function($p, $i){
            return [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'discount_price' => $p->discount_price,
                'thumbnail' => $p->thumbnail,
                'category' => $p->category->name ?? null,
                'subcategory' => $p->subcategory->name ?? null,
                'brand' => $p->brand->name ?? null,
                'colors' => $p->colors,
                'sizes' => $p->sizes,
                'material' => $p->material,
                'tags' => $p->tags,
                'status' => (bool)$p->is_active,
                'index' => $i + 1,
                'images' => $p->images ? $p->images->pluck('image')->toArray() : [],
            ];
        });

        // Fetch categories, subcategories, brands for dropdowns
        $categories = Category::where('status', 1)->get();
        $subcategories = Subcategory::where('status', 1)->get();
        $brands = Brand::where('status', 1)->get();

        return view('admin.products.index', compact(
            'products', 
            'productsForJs', 
            'categories', 
            'subcategories', 
            'brands'
        ));
    }


    /**
     * Show the form for creating a new product.
     */
    public function create()
{
    // Fetch categories, subcategories, and brands for the dropdowns
    $categories = Category::where('status', 1)->get();
    $subcategories = Subcategory::where('status', 1)->get();
    $brands = Brand::where('status', 1)->get();

    // Return the create product view
    return view('admin.products.create', compact('categories', 'subcategories', 'brands'));
}


    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
{
    // Validation
    $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'subcategory_id' => 'nullable|exists:subcategories,id',
        'brand_id' => 'nullable|exists:brands,id',
        'price' => 'required|numeric',
        'discount_price' => 'nullable|numeric',
        'sku' => 'nullable|string|max:255',
        'barcode' => 'nullable|string|max:255',
        'stock' => 'nullable|integer',
        'weight' => 'nullable|numeric',
        'dimensions' => 'nullable|string|max:255',
        'colors' => 'nullable|string|max:255',
        'sizes' => 'nullable|array',
        'material' => 'nullable|string|max:255',
        'tags' => 'nullable|string|max:255',
        'short_description' => 'nullable|string',
        'description' => 'nullable|string',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'status' => 'nullable|boolean',
        'is_featured' => 'nullable|boolean',
        'is_new' => 'nullable|boolean',
        'is_best_seller' => 'nullable|boolean',
        'is_flash_sale' => 'nullable|boolean',
        'flash_sale_price' => 'nullable|numeric',
        'flash_sale_start' => 'nullable|date',
        'flash_sale_end' => 'nullable|date',
    ]);

    // Create Product
    $product = new Product();
    $product->name = $request->name;
    $product->slug = \Str::slug($request->name);
    $product->category_id = $request->category_id;
    $product->subcategory_id = $request->subcategory_id;
    $product->brand_id = $request->brand_id;
    $product->price = $request->price;
    $product->discount_price = $request->discount_price;
    $product->sku = $request->sku;
    $product->barcode = $request->barcode;
    $product->stock = $request->stock ?? 0;
    $product->weight = $request->weight;
    $product->dimensions = $request->dimensions;
    $product->colors = $request->colors;
    $product->sizes = $request->sizes ? json_encode($request->sizes) : null;
    $product->material = $request->material;
    $product->tags = $request->tags;
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->meta_title = $request->meta_title;
    $product->meta_description = $request->meta_description;
    $product->status = $request->status ?? true;
    $product->is_featured = $request->is_featured ?? false;
    $product->is_new = $request->is_new ?? false;
    $product->is_best_seller = $request->is_best_seller ?? false;
    $product->is_flash_sale = $request->is_flash_sale ?? false;
    $product->flash_sale_price = $request->flash_sale_price;
    $product->flash_sale_start = $request->flash_sale_start;
    $product->flash_sale_end = $request->flash_sale_end;

    // Upload thumbnail
    if ($request->hasFile('thumbnail')) {
        $thumbnailName = time() . '_' . $request->thumbnail->getClientOriginalName();
        $request->thumbnail->move(public_path('admin-assets/products'), $thumbnailName);
        $product->thumbnail = $thumbnailName;
    }

    $product->save();

    // Upload multiple images
    if ($request->hasFile('images')) {
    foreach ($request->file('images') as $img) {
        $filename = time() . '_' . $img->getClientOriginalName();
        $img->move(public_path('admin-assets/products'), $filename);

        \App\Models\ProductImage::create([
            'product_id' => $product->id,
            'image' => $filename, // must match column name in DB
        ]);
    }
}

    return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
}



    /**
     * Display the specified product.
     */
    

public function show($id)
{
    // Fetch product
    $product = Product::with(['category', 'subcategory', 'brand'])->findOrFail($id);

    // Fetch multiple images from the correct table
    $productImages = DB::table('product_images')->where('product_id', $product->id)->get();

    // Pass to view
    return view('admin.products.show', compact('product', 'productImages'));
}


    /**
     * Show the form for editing the specified product.
     */
    

public function edit($id)
{
    // Fetch product
    $product = DB::table('products')->where('id', $id)->first();

    // Fetch related images from products_image table
    $productImages = DB::table('product_images')
        ->where('product_id', $id)
        ->get();

    $categories = DB::table('categories')->where('status', 1)->get();
    $subcategories = DB::table('subcategories')->where('status', 1)->get();
    $brands = DB::table('brands')->where('status', 1)->get();

    return view('admin.products.edit', compact('product', 'productImages', 'categories', 'subcategories', 'brands'));
}



    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);
    // Update product table
    DB::table('products')->where('id', $id)->update([
        'name' => $request->name,
        'slug' => $request->slug,
        'category_id' => $request->category_id,
        'subcategory_id' => $request->subcategory_id,
        'brand_id' => $request->brand_id,
        'price' => $request->price,
        'discount_price' => $request->discount_price,
        'stock' => $request->stock,
        'weight' => $request->weight,
        'dimensions' => $request->dimensions,
        'colors' => $request->colors,
        'sizes' => json_encode($request->sizes), // checkbox values
        'material' => $request->material,
        'tags' => $request->tags,
        'short_description' => $request->short_description,
        'description' => $request->description,
        'meta_title' => $request->meta_title,
        'meta_description' => $request->meta_description,
        'status' => $request->status ? 1 : 0,
        'is_featured' => $request->is_featured ? 1 : 0,
        'is_new' => $request->is_new ? 1 : 0,
        'is_best_seller' => $request->is_best_seller ? 1 : 0,
        'is_flash_sale' => $request->is_flash_sale ? 1 : 0,
        'flash_sale_price' => $request->flash_sale_price,
        'flash_sale_start' => $request->flash_sale_start,
        'flash_sale_end' => $request->flash_sale_end,
        'updated_at' => now(),
    ]);

     // Update thumbnail
    if ($request->hasFile('thumbnail')) {
        // Delete old thumbnail
        if ($product->thumbnail && file_exists(public_path('admin-assets/products/'.$product->thumbnail))) {
            unlink(public_path('admin-assets/products/'.$product->thumbnail));
        }

        $thumbnailName = time().'_'.$request->thumbnail->getClientOriginalName();
        $request->thumbnail->move(public_path('admin-assets/products'), $thumbnailName);
        $product->thumbnail = $thumbnailName;
    }

    $product->save();
    


    // Handle multiple images
    if($request->hasFile('images')) {
        // Fetch old images
        $oldImages = DB::table('product_images')->where('product_id', $id)->get();

        // Delete old images from storage
        foreach($oldImages as $img) {
            $oldPath = public_path('admin-assets/products/'.$img->image);
            if(file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // Delete old records from DB
        DB::table('product_images')->where('product_id', $id)->delete();

        // Insert new images
        foreach($request->file('images') as $file) {
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('admin-assets/products'), $filename);

            DB::table('product_images')->insert([
                'product_id' => $id,
                'image' => $filename,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
}




    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
{
    $product = Product::findOrFail($id);

    // Delete thumbnail
    if ($product->thumbnail && file_exists(public_path('admin-assets/products/'.$product->thumbnail))) {
        unlink(public_path('admin-assets/products/'.$product->thumbnail));
    }

    // Delete multiple images from products_image table
    $images = DB::table('product_images')->where('product_id', $product->id)->get();
    foreach($images as $img) {
        if(file_exists(public_path('admin-assets/products/'.$img->image))) {
            unlink(public_path('admin-assets/products/'.$img->image));
        }
    }
    DB::table('product_images')->where('product_id', $product->id)->delete();

    // Delete product
    $product->delete();

    return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
}
public function getSubcategories($categoryId)
{
    try {
        \Log::info('Fetching subcategories for category ID: ' . $categoryId);
        
        // Get all subcategories for debugging
        $allSubcategories = Subcategory::all();
        \Log::info('Total subcategories in database: ' . $allSubcategories->count());
        
        // Get subcategories for specific category
        $subcategories = Subcategory::where('category_id', $categoryId)
                            ->where('status', 1)
                            ->get(['id', 'name']);
        
        \Log::info('Found subcategories for category ' . $categoryId . ': ' . $subcategories->count());
        \Log::info('Subcategories data: ', $subcategories->toArray());
        
        // If no subcategories found, let's check what categories exist
        if ($subcategories->count() == 0) {
            $categories = \App\Models\Category::all();
            \Log::info('Available categories: ', $categories->pluck('id', 'name')->toArray());
        }
        
        return response()->json($subcategories);
    } catch (\Exception $e) {
        \Log::error('Error fetching subcategories: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch subcategories', 'message' => $e->getMessage()], 500);
    }
}

}