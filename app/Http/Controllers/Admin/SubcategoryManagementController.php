<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Support\Str;

class SubcategoryManagementController extends Controller
{
    // Display all subcategories
    public function index(Request $request)
    {
        $query = Subcategory::with(['category', 'products']);
        
        // Filter by category if specified
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status == 'active');
        }
        
        // Search
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $subcategories = $query->withCount('products')->latest()->paginate(20)->withQueryString();
        $categories = Category::where('status', true)->get();
        
        return view('admin.subcategories.index', compact('subcategories', 'categories'));
    }

    // Show create form
    public function create()
    {
        $categories = Category::where('status', true)->get();
        return view('admin.subcategories.create', compact('categories'));
    }

    // Store new subcategory
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subcategories,name',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('admin-assets/subcategories'), $imageName);
        }

        $subcategory = Subcategory::create([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $imageName,
            'status' => $request->status == '1' ? true : false,
            'featured' => $request->featured == '1' ? true : false,
            'nav' => $request->nav == '1' ? true : false,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subcategory created successfully.',
                'subcategory' => $subcategory
            ]);
        }

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory created successfully.');
    }

    // Show subcategory details
    public function show(Subcategory $subcategory)
    {
        $subcategory->load(['category', 'products']);
        return view('admin.subcategories.show', compact('subcategory'));
    }

    // Show edit form
    public function edit(Subcategory $subcategory)
    {
        $categories = Category::where('status', true)->get();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    // Update existing subcategory
    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subcategories,name,' . $subcategory->id,
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = $subcategory->image;

        if ($request->hasFile('image')) {
            // Delete old image
            $oldImagePath = public_path('admin-assets/subcategories/' . $subcategory->image);
            if ($subcategory->image && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('admin-assets/subcategories'), $imageName);
        }

        $subcategory->update([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $imageName,
            'status' => $request->status == '1' ? true : false,
            'featured' => $request->featured == '1' ? true : false,
            'nav' => $request->nav == '1' ? true : false,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subcategory updated successfully.',
                'subcategory' => $subcategory
            ]);
        }

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    // Delete subcategory
    public function destroy(Subcategory $subcategory)
    {
        $imagePath = public_path('admin-assets/subcategories/' . $subcategory->image);
        if ($subcategory->image && file_exists($imagePath)) {
            unlink($imagePath);
        }

        $subcategory->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subcategory deleted successfully.'
            ]);
        }
        
        return redirect()->back()->with('success', 'Subcategory deleted successfully.');
    }
}
