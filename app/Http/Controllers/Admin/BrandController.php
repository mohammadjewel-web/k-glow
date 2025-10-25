<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::withCount('products');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Featured filter
        if ($request->has('featured') && $request->featured) {
            if ($request->featured === 'featured') {
                $query->where('featured', true);
            } elseif ($request->featured === 'regular') {
                $query->where('featured', false);
            }
        }

        // Navigation filter
        if ($request->has('nav') && $request->nav) {
            if ($request->nav === 'nav') {
                $query->where('nav', true);
            } elseif ($request->nav === 'hidden') {
                $query->where('nav', false);
            }
        }

        $brands = $query->latest()->paginate(20)->withQueryString();

        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:brands,name',
            'slug' => 'nullable|string|unique:brands,slug',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'website' => 'nullable|url',
            'is_active' => 'nullable|boolean',
            'featured' => 'nullable|boolean',
            'nav' => 'nullable|boolean',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
        ]);

        $data = $request->only([
            'name', 'slug', 'description', 'website', 
            'meta_title', 'meta_description'
        ]);
        
        $data['slug'] = $data['slug'] ?: \Str::slug($request->name);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['featured'] = $request->has('featured') ? 1 : 0;
        $data['nav'] = $request->has('nav') ? 1 : 0;

        if($request->hasFile('logo')){
            $file = $request->file('logo');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('admin-assets/brands'), $filename);
            $data['logo'] = $filename;
        }

        Brand::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand created successfully!'
            ]);
        }

        return redirect()->route('admin.brands.index')->with('success','Brand added successfully');
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|unique:brands,name,'.$brand->id,
            'slug' => 'nullable|string|unique:brands,slug,'.$brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'website' => 'nullable|url',
            'is_active' => 'nullable|boolean',
            'featured' => 'nullable|boolean',
            'nav' => 'nullable|boolean',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
        ]);

        $data = $request->only([
            'name', 'slug', 'description', 'website', 
            'meta_title', 'meta_description'
        ]);
        
        $data['slug'] = $data['slug'] ?: \Str::slug($request->name);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['featured'] = $request->has('featured') ? 1 : 0;
        $data['nav'] = $request->has('nav') ? 1 : 0;

        if($request->hasFile('logo')){
            $file = $request->file('logo');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('admin-assets/brands'), $filename);

            if($brand->logo && file_exists(public_path('admin-assets/brands/'.$brand->logo))){
                unlink(public_path('admin-assets/brands/'.$brand->logo));
            }
            $data['logo'] = $filename;
        }

        $brand->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand updated successfully!'
            ]);
        }

        return redirect()->route('admin.brands.index')->with('success','Brand updated successfully');
    }

    public function show(Brand $brand)
    {
        $brand->loadCount('products');
        return view('admin.brands.show', compact('brand'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function destroy(Brand $brand)
    {
        if($brand->logo && file_exists(public_path('admin-assets/brands/'.$brand->logo))){
            unlink(public_path('admin-assets/brands/'.$brand->logo));
        }

        $brand->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand deleted successfully!'
            ]);
        }
        
        return redirect()->route('admin.brands.index')->with('success','Brand deleted successfully');
    }
}